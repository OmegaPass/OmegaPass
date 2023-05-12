<?php
// Import required dependencies
include_once '../../config.php';
// include the database connection file
include "../../db.php";

// Start session
session_start();

// When not logged in, redirect the client to the homepage
if (!isset($_SESSION['masterpass']) && !isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'redirect' => '/']);
    exit;
}

// instantiate a new database object
$database = new DataBase();

// Initialize the $errorMsg variable to null.
$errorMsg = null;

// check if the form has been submitted
if (isset($_POST['website'], $_POST['username'], $_POST['password'])) {
    // if the form has been submitted, add the password to the database
    // using the add_password() function of the database object
    try {
        $database->add_password($database->getUserId(), trim($_POST['website']), trim($_POST['username']), trim($_POST['password']));
    } catch (Exception $e) {
        // Log the exception for debugging purposes
        error_log($e);
        // Return a general error message to the JavaScript code
        $errorMsg = "An error occurred. Please try again later.";

        // Send an error response to the JavaScript code
        echo json_encode(['success' => false, 'error' => $errorMsg]);
        exit;
    }

    // Send a success response to the JavaScript code with the correct redirect URL
    echo json_encode(['success' => true, 'redirect' => '/']);
    exit;
}

// * Other ajax calls

// Generate a password with specified length, and optional digits and special characters
if (isset($_POST['generate'])) {
    $digits = filter_var($_POST['digits'], FILTER_VALIDATE_BOOLEAN);
    $special = filter_var($_POST['special'], FILTER_VALIDATE_BOOLEAN);

    // Call the generate_password() function and return the result
    echo json_encode(['success' => true, 'password' => generate_password($_POST['length'], $digits, $special)]);
    exit;
}

// Check the strength of a password
if (isset($_POST['passwordStrength'], $_POST['password']) && is_string($_POST['password'])) {
    // Call the check_password_strength() function and return the result
    echo json_encode(['success' => true, 'password' => check_password_strength($_POST['password'])]);
    exit;
}
