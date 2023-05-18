<?php

// Import required dependencies
require_once 'vendor/autoload.php';

use ZxcvbnPhp\Zxcvbn;
use Fernet\Fernet;

// Function to generate a random password of a given length, with optional digits and special characters
function generate_password($length, $digits, $special)
{
    // Define character sets for letters, digits, and special characters
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digitsChars = '1234567890';
    $specialChars = '#$%&\'()*+,-./:;<=>?@[\]^_`{|}~';

    // Use RandomLib to generate a random password string from the chosen character sets
    $factory = new RandomLib\Factory;
    $generator = $factory->getMediumStrengthGenerator();

    $options = [];

    switch (true) {
        case ($digits && $special):
            array_push($options, $letters, $digitsChars, $specialChars);
            break;
        case ($digits):
            array_push($options, $letters, $digitsChars);
            break;
        case ($special):
            array_push($options, $letters, $specialChars);
            break;
        default:
            $options[] = $letters;
            break;
    }

    // Iterate over options and check if generated String contains the option

    $randpass = '';
    foreach ($options as $item) {
        do {
            $randpass = $generator->generateString($length, implode('', $options));
        } while (
            !is_string(strpbrk($randpass, $item))
        );
    }

    return $randpass;
}


// Function to generate a random user ID string
function generate_userid()
{

    // Define character set for the user ID
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $userid = array();
    $alphaLength = strlen($alphabet) - 1;

    // Use rand() function to generate a random string from the defined character set
    for ($i = 0; $i < 12; $i++) {
        $n = rand(0, $alphaLength);
        $userid[] = $alphabet[$n];
    }

    // Convert the array of characters to a single string and return it
    return implode($userid);
}

// Function to hash a password string using PHP's password_hash() function
function hash_pw($string)
{

    // Convert the string to UTF-8 encoding and hash it using the PASSWORD_DEFAULT algorithm
    $password = utf8_encode($string);
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Return the hashed password
    return $hashed;
}

// Function to check if a password matches a given hash, using PHP's password_verify() function
function check_pw($string, $hash)
{

    // Use the password_verify() function to compare the string and hash
    return password_verify($string, $hash);
}

// Function to check the strength of a password using the Zxcvbn library
function check_password_strength($password)
{
    $zxcvbn = new Zxcvbn();
    $strength = $zxcvbn->passwordStrength($password)['score'];

    // Define a message for each password strength level
    $strengthMessage = [
        'very weak',
        'weak',
        'medium',
        'strong',
        'very strong'
    ];

    // Return the appropriate strength message based on the score
    return $strengthMessage[$strength] ?? 'calc not avaliable';
}

// Function to encrypt a password using the Fernet symmetric encryption algorithm
function encrypt($password, $masterPass)
{
    // Generate a SHA-256 hash of the master password and use it as the encryption key
    $key = hash('sha256', $masterPass, true);
    $fernet = new Fernet(base64url_encode($key));

    // Use the Fernet object to encrypt the password and return the encrypted result
    return $fernet->encode($password);
}

// Function to decrypt an encrypted password using the Fernet symmetric encryption algorithm
function decrypt($encryted, $masterPass)
{
    // Generate a SHA-256 hash of the master password and use it as the encryption key
    $key = hash('sha256', $masterPass, true);

    // Initialize a new Fernet object with the encoded key
    $fernet = new Fernet(base64url_encode($key));

    // Use the Fernet object to decrypt the password and return the decrypted result
    return $fernet->decode($encryted);
}

// Function to base64url encode data
function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
// Function to base64url decode data
function base64url_decode($data)
{
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}
