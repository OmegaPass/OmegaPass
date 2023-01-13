<?php
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
    // TODO: Password encryption
    return encrypt($password);

    $database->insert('passwords', [
        'user_id' => $userid,
        'website' => $website,
        'username' => $username,
        'password' => encrypt($password)
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
    // TODO: Password decryption
    $result = $database->select('passwords', [
                'website',
                'username',
                'password'
        ], [
                'user_id' => $userid
        ]);
    return json_encode($result);
}

function login($username, $password) {
    global $database;

    $query = $database->select('users', ['password'], ['username' => $username]);
    if ($query !== []) {
        if (check_pw($password, $query[0])) {
            return "Sucess";
        } else {
            return "Wrong password";
        }
    } else {
        return "Username not found";
    }
}

echo login("test", "test");