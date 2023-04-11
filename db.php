<?php
session_start();
require 'vendor/autoload.php';
include 'crypt.php';

// Using Medoo namespace.
use Medoo\Medoo;

// Connect the database.
$database = new Medoo([
    'type' => 'mysql',
    'host' => getenv('HOST'),
    'database' => getenv('DATABASE'),
    'username' => getenv('USERNAME'),
    'password' => getenv('PASSWORD'),
    'port' => getenv('PORT')
]);

function add_password($userid, $website, $username, $password) {
    global $database;
    $database->insert('passwords', [
        'user_id' => $userid,
        'website' => $website,
        'username' => $username,
        'password' => encrypt($password, $_SESSION['masterpass'])
    ]);
}

function add_user($username, $password) {
    global $database;

    $query = $database->select('users', ['username'], ['username' => $username]);

    if ($query !== []) {
        return "Username already taken";
    } else {
        $database->insert('users', [
            'user_id' => generate_userid(),
            'username' => $username,
            'password' => hash_pw($password)
        ]);
        return "Success";
    }
}

function get_password($website) {
    global $database;
    $result = $database->select('passwords', [
                'website'
        ], [
                'website' => $website
        ]);
    return json_encode($result);
}

function get_all_websites($userid) {
    global $database;
    $result = $database->select('passwords', [
                'website'
        ], [
                'user_id' => $userid
        ]);
    return json_encode($result);
}

function get_all_entries($userid, $mode = null) {
    global $database;
    $results = $database->select('passwords', [
                'website',
                'username',
                'password',
                'id'
        ], [
                'user_id' => $userid,
                'trash' => $mode === 'trash' ? true : null,
                'favorite' => $mode === 'favorite' ? true : null
    ]);

    foreach ($results as &$result) {
        if ($result['password'] != '') {
            $result['password'] = decrypt($result['password'], $_SESSION['masterpass']);
        }
    }

    return $results;
}

function login($username, $password) {
    global $database;

    $query = $database->select('users', ['password'], ['username' => $username]);
    if ($query !== []) {
        if (check_pw($password, $query[0]['password'])) {
            return "Success";
        } else {
            return "Wrong password";
        }
    } else {
        return "Username not found";
    }
}

function getUserId() {
    global $database;

    $query = $database->select('users', [
        'user_id'
    ], [
        'username' => $_SESSION['username']
    ]);

    return $query[0]['user_id'];
}

function changeUsername($userId, $newUsername) {
    global $database;

    $database->update('users', [
        'username' => $newUsername
    ], [
        'user_id' => $userId
    ]);
}

function changeEntry($userId, $website, $username, $newPassword, $entryId) {
    global $database;

    $database->update('passwords', [
        'website' => $website,
        'username' => $username,
        'password' => encrypt($newPassword, $_SESSION['masterpass'])
    ],
    [
        'id' => $entryId
    ]);
}

function moveEntryToTrash($entryId) {
    global $database;

    $database->update('passwords', [
        'trash' => true
    ], [
        'id' => $entryId
    ]);
}

function moveEntryOutOfTrash($entryId) {
    global $database;

    $database->update('passwords', [
        'trash' => null
    ], [
        'id' => $entryId
    ]);
}

function moveEntryToFavorite($entryId) {
    global $database;

    $database->update('passwords', [
        'favorite' => true
    ], [
        'id' => $entryId
    ]);
}

function moveEntryOutOfFavorite($entryId) {
    global $database;

    $database->update('passwords', [
        'favorite' => null
    ], [
        'id' => $entryId
    ]);
}
