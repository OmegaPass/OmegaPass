<?php
session_start();
require 'vendor/autoload.php';
include 'crypt.php';

// Using Medoo namespace.
use Medoo\Medoo;

// Connect the database.
$database = new Medoo([
    'type' => 'mysql',
    'host' => '',
    'database' => 'pwmanager',
    'username' => '',
    'password' => '',
    'port' => '3306'
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
        return "Sucess";
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

function get_all_entries($userid) {
    global $database;
    $results = $database->select('passwords', [
                'website',
                'username',
                'password'
        ], [
                'user_id' => $userid
    ]);

    foreach ($results as &$result) {
        if ($result['password'] != '') {
            $result['password'] = decrypt($result['password'], $_SESSION['masterpass']);
        }
    }

    return json_encode($results);
}

function login($username, $password) {
    global $database;

    $query = $database->select('users', ['password'], ['username' => $username]);
    if ($query !== []) {
        if (check_pw($password, $query[0]['password'])) {
            return "Sucess";
        } else {
            return "Wrong password";
        }
    } else {
        return "Username not found";
    }
}