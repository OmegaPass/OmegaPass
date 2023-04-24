<?php
session_start();
require 'vendor/autoload.php';
include_once 'crypt.php';

use Medoo\Medoo;

class DataBase {
    private Medoo $database;

    public function __construct() {
        $this->database = new Medoo([
            'type' => 'mysql',
            'host' => getenv('HOST'),
            'database' => getenv('DATABASE'),
            'username' => getenv('USERNAME'),
            'password' => getenv('PASSWORD'),
            'port' => getenv('PORT'),
            'testMode' => $phpUnitTestMode ?? false
        ]);
    }

    public function add_password($userid, $website, $username, $password) {
        $this->database->insert('passwords', [
            'user_id' => $userid,
            'website' => $website,
            'username' => $username,
            'password' => encrypt($password, $_SESSION['masterpass'])
        ]);
    }

    public function add_user($username, $password) {
        $query = $this->database->select('users', ['username'], ['username' => $username]);

        if ($query !== []) {
            return "Username already taken";
        } else {
            $this->database->insert('users', [
                'user_id' => generate_userid(),
                'username' => $username,
                'password' => hash_pw($password)
            ]);
            return "Success";
        }
    }

    public function get_password($website) {
        $result = $this->database->select('passwords', [
            'website'
        ], [
            'website' => $website
        ]);
        return json_encode($result);
    }

    public function get_all_websites($userid) {
        $result = $this->database->select('passwords', [
            'website'
        ], [
            'user_id' => $userid
        ]);
        return json_encode($result);
    }

    public function get_all_entries($userid, $mode = null) {
        $results = $this->database->select('passwords', [
            'website',
            'username',
            'password',
            'id'
        ], [
            'user_id' => $userid,
            'trash' => $mode === 'trash' ? true : null,
            'favorite' => $mode === 'favorite' ? true : Medoo::raw('favorite IS NULL OR favorite = true')
        ]);

        foreach ($results as &$result) {
            if ($result['password'] != '') {
                $result['password'] = decrypt($result['password'], $_SESSION['masterpass']);
            }
        }

        return $results;
    }

    public function login($username, $password) {
        $query = $this->database->select('users', ['password'], ['username' => $username]);
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

    public function changeMasterPass($userId, $oldPassword, $newPassword) {
        $pw = $this->database->select('users', ['password'], ['user_id' => $userId]);
        if (check_pw($oldPassword, $pw[0]['password'])) {
            $this->database->update('users', [
                'password' => hash_pw($newPassword)
            ], [
                'user_id' => $userId
            ]);

            $_SESSION['masterpass'] = $newPassword;

            $this->reEncryptPasswords($userId, $oldPassword);

            return true;
        } else {
            return false;
        }
    }

    private function reEncryptPasswords($userId, $oldMasterPass) {
        $passwords = $this->database->select('passwords', ['id', 'password'], ['user_id' => $userId]);

        foreach ($passwords as $password) {
            $decryptedPassword = decrypt($password['password'], $oldMasterPass);

            $this->database->update('passwords', [
                'password' => encrypt($decryptedPassword, $_SESSION['masterpass'])
            ], [
                'id' => $password['id']
            ]);
        }
    }

    public function getUserId() {
        $query = $this->database->select('users', [
            'user_id'
        ], [
            'username' => $_SESSION['username']
        ]);

        return $query[0]['user_id'];
    }

    public function changeUsername($userId, $newUsername) {
        $this->database->update('users', [
            'username' => $newUsername
        ], [
            'user_id' => $userId
        ]);
    }

    public function changeEntry($userId, $website, $username, $newPassword, $entryId) {
        $this->database->update('passwords', [
            'website' => $website,
            'username' => $username,
            'password' => encrypt($newPassword, $_SESSION['masterpass'])
        ],
            [
                'id' => $entryId
            ]);
    }

    public function moveEntryToTrash($entryId) {
        $this->database->update('passwords', [
            'trash' => true,
            'trashDate' => (new DateTime())->format('Y-m-d H:i:s')
        ], [
            'id' => $entryId
        ]);
    }

    public function moveEntryOutOfTrash($entryId) {
        $this->database->update('passwords', [
            'trash' => null,
            'trashDate' => null
        ], [
            'id' => $entryId
        ]);
    }

    public function moveEntryToFavorite($entryId) {
        $this->database->update('passwords', [
            'favorite' => true
        ], [
            'id' => $entryId
        ]);
    }

    public function moveEntryOutOfFavorite($entryId) {
        $this->database->update('passwords', [
            'favorite' => null
        ], [
            'id' => $entryId
        ]);
    }

    public function deleteAfterThirtyDays() {
        $trashedPasswords = $this->database->select('passwords', [
            'id',
            'trashDate'
        ], [
            'trash' => true,
            'user_id' => $this->getUserId()
        ]);

        foreach ($trashedPasswords as $trashedPassword) {
            if (strtotime($trashedPassword['trashDate']) < strtotime('-30 days')) {
                $this->database->delete('passwords', [
                    "AND" => [
                        'id' => $trashedPassword['id']
                    ]
                ]);
            }
        }
    }
}
