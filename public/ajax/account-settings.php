<?php
// Import required dependencies
include_once '../../config.php';
// Include the database file that contains the DataBase class.
include_once "../../db.php";

// When not logged in you the client gets redirected to the homepage
if (!isset($_SESSION['masterpass']) && !isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'redirect' => '/']);
    exit;
}

// Create a new instance of the DataBase class.
$database = new DataBase();

// Initialize the $errorMsg variable to null.
$errorMsg = null;

// If the 'oldPassword' and 'newPassword' variables are set in the $_POST array.
if (isset($_POST['oldPassword']) && isset($_POST['newPassword'])) {

    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    // Validate and sanitize the inputs
    $oldPassword = htmlspecialchars($oldPassword);
    $newPassword = htmlspecialchars($newPassword);

    // Attempt to change the master password for the current user with the given old and new passwords.
    try {
        $success = $database->changeMasterPass($database->getUserId(), $oldPassword, $newPassword);
    } catch (Exception $e) {
        // Log the exception for debugging purposes
        error_log($e);
        // Return a general error message to the JavaScript code
        $errorMsg = "An error occurred. Please try again later.";

        // Send an error response to the JavaScript code
        echo json_encode(['success' => false, 'error' => $errorMsg]);
        exit;
    }
    

    // If the password change was successful.
    if ($success) {

        // Unset the 'masterpass' key in the $_SESSION array.
        unset($_SESSION['masterpass']);

        // Send a success response to the JavaScript code with the correct redirect URL
        echo json_encode(['success' => true, 'redirect' => '/']);
        exit;
    }

    // Otherwise, set the $errorMsg variable to indicate that the password was incorrect.
    $errorMsg = "You entered the wrong password.";
    // Send an error response to the JavaScript code
    echo json_encode(['success' => false, 'error' => $errorMsg]);
    exit;
}

// If the 'newUsername' variable is set in the $_POST array.
if (isset($_POST['newUsername'])) {

    // Attempt to change the username for the current user to the given new username.
    try {
        $database->changeUsername($database->getUserId(), trim($_POST['newUsername']));
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
    echo json_encode(['success' => true, 'redirect' => '/overview/']);
    exit;
}
