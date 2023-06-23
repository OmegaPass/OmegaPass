<?php
session_start(); // Start a new or existing session
require 'vendor/autoload.php'; // Include the Composer-generated autoload file
include_once 'crypt.php'; // Include the file with the encryption and decryption functions
use Medoo\Medoo; // Import the Medoo namespace, which provides a simple database API

class DataBase {
    private Medoo $database; // Declare a private instance variable of type Medoo

    // The constructor method initializes the database object with the provided configuration
    public function __construct() {
        $this->database = new Medoo([
            'type' => 'mysql', // The type of database (MySQL)
            'host' => getenv('HOST'), // The host of the database
            'database' => getenv('DATABASE'), // The name of the database
            'username' => getenv('USERNAME'), // The username for the database
            'password' => getenv('PASSWORD'), // The password for the database
            'port' => getenv('PORT'), // The port for the database
            'testMode' => $phpUnitTestMode ?? false // Whether test mode is enabled
        ]);
    }

    // This method adds a new password entry to the database for the specified user
    public function add_password($userid, $website, $username, $password) {
        $this->database->insert('passwords', [
            'user_id' => $userid,
            'website' => $website,
            'username' => $username,
            'password' => encrypt($password, $_SESSION['masterpass']) // Encrypt the password before storing it
        ]);
    }

    // This method adds a new user to the database with the specified username and password
    public function add_user($username, $password) {
        // Check if the username already exists in the database
        $query = $this->database->select('users', ['username'], ['username' => $username]);

        if ($query !== []) {
            return "Username already taken";
        } else {
            // If the username is available, add the user to the database with a unique ID and hashed password
            $this->database->insert('users', [
                'user_id' => generate_userid(),
                'username' => $username,
                'password' => hash_pw($password) // Hash the password before storing it
            ]);
            return "Success";
        }
    }

    // This method retrieves the encrypted password for the specified website
    public function get_password($entryId) {
        $result = $this->database->select('passwords', [
            'website',
            'username',
            'password',
            'id'
        ], [
            'id' => $entryId
        ]);

        if ($result !== []) {
            $result = $result[0];

            if ($result['password'] != '') {
                $result['password'] = decrypt($result['password'], $_SESSION['masterpass']);
            }

            return $result;
        }

        return null;
    }

    // This method retrieves all websites for the specified user
    public function get_all_websites($userid) {
        $result = $this->database->select('passwords', [
            'website'
        ], [
            'user_id' => $userid
        ]);
        return json_encode($result);
    }

    // This method retrieves all password entries for the specified user,
    // with optional filtering by mode (trash or favorite).
    // Returns an array of associative arrays, each containing the website,
    // username, password (decrypted), and ID for a password entry that
    // belongs to the user and meets the specified filtering criteria.
    public function get_all_entries($userid, $mode = null) {
        // Select the website, username, password, and ID for all password entries
        // that belong to the user and meet the specified filtering criteria

        switch ($mode) {
            case 'trash':
                $results = $this->database->select('passwords', [
                    'website',
                    'username',
                    'password',
                    'id'
                ], [
                    'user_id' => $userid,
                    'trash' => true,
                    'OR' => [
                        'favorite' => true,
                        'favorite' => null,
                        'trash' => true,
                    ],
                ]);
                break;

            case 'favorite':
                $results = $this->database->select('passwords', [
                    'website',
                    'username',
                    'password',
                    'id'
                ], [
                    'user_id' => $userid,
                    'trash' => null,
                    'favorite' => true,
                ]);
                break;

            default:
                $results = $this->database->select('passwords', [
                    'website',
                    'username',
                    'password',
                    'id'
                ], [
                    'user_id' => $userid,
                    'trash' => null,
                    'OR' => [
                        'favorite' => true,
                        'favorite' => null,
                        'trash' => null,
                    ],
                ]);
                break;
        }

        // Decrypt the password for each result (if it's not empty) using
        // the session's master password
        foreach ($results as &$result) {
            if ($result['password'] != '') {
                $result['password'] = decrypt($result['password'], $_SESSION['masterpass']);
            }
        }

        return $results;
    }

    // This method authenticates the user with the specified username and password
    public function login($username, $password) {
        // Check if the username exists in the database and
        // if the provided password matches the hashed password
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

    // This method changes the user's master password
    public function changeMasterPass($userId, $oldPassword, $newPassword) {
        // Check if the provided old password matches the current password for the user
        $pw = $this->database->select('users', ['password'], ['user_id' => $userId]);
        if (check_pw($oldPassword, $pw[0]['password'])) {
            // If the old password is correct, update the user's password in the database
            // and in the session
            $this->database->update('users', [
                'password' => hash_pw($newPassword)
            ], [
                'user_id' => $userId
            ]);

            $_SESSION['masterpass'] = $newPassword;

            // Re-encrypt all password entries for the user using the new master password
            $this->reEncryptPasswords($userId, $oldPassword);

            return true;
        } else {
            return false;
        }
    }

    // This method re-encrypts all password entries for the specified user
    // using the new master password
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

    // This method retrieves the user ID for the currently logged-in user

    /**
     * @throws Exception
     */
    public function getUserId() {
        if (!isset($_SESSION['userId'])) {
            $query = $this->database->select('users', [
                'user_id'
            ], [
                'username' => $_SESSION['username']
            ]);

            if (!empty($query)) {
                $userId = $query[0]['user_id'];
                $_SESSION['userId'] = $userId;

                return $userId;
            }
            throw new Exception();
        }

        return $_SESSION['userId'];
    }

    // This method changes the username for the specified user
    public function changeUsername($userId, $newUsername) {
        $this->database->update('users', [
            'username' => $newUsername
        ], [
            'user_id' => $userId
        ]);
    }

    // This method updates the specified password entry with the new website, username and password
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

    // This method moves the specified password entry to the trash
    public function moveEntryToTrash($entryId) {
        $this->database->update('passwords', [
            'trash' => true,
            'trashDate' => (new DateTime())->format('Y-m-d H:i:s')
        ], [
            'id' => $entryId
        ]);
    }

    // This method moves the specified password entry out of the trash
    public function moveEntryOutOfTrash($entryId) {
        $this->database->update('passwords', [
            'trash' => null,
            'trashDate' => null
        ], [
            'id' => $entryId
        ]);
    }

    // This method marks the specified password entry as a favorite
    public function moveEntryToFavorite($entryId) {
        $this->database->update('passwords', [
            'favorite' => true
        ], [
            'id' => $entryId
        ]);
    }

    // This method removes the favorite status from the specified password entry
    public function moveEntryOutOfFavorite($entryId) {
        $this->database->update('passwords', [
            'favorite' => null
        ], [
            'id' => $entryId
        ]);
    }

    // This method deletes password entries that have been in the trash for more than 30 days

    /**
     * @throws Exception
     */
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

    public function searchEntries($userId, $query) {
        if (empty(trim($query))) {
            // If the query is empty or all spaces, return all entries for the user
            return $this->get_all_entries($userId);
        } 
        
        $query = "%$query%";
        $results = $this->database->select('passwords', [
            'website',
            'username',
            'password',
            'id'
        ], [
            'user_id' => $userId,
            'OR' => [
                'website[~]' => $query,
                'username[~]' => $query
            ]
        ]);
        return $results;
    }

}
