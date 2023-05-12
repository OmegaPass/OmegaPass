<?php
// Import required dependencies
include_once '../../config.php';
// include the database connection file
include "../../db.php";

// instantiate a new database object
$database = new Database();

// Initialize the $errorMsg variable to null.
$errorMsg = null;

// Check if the user has submitted a form to change an entry's information
if (isset($_POST['edit_website']) && isset($_POST['edit_username']) && isset($_POST['edit_password']) && isset($_POST['edit_id'])) {

    // Validate and sanitize the inputs
    $website = htmlspecialchars($_POST['edit_website']);
    $username = htmlspecialchars($_POST['edit_username']);
    $password = htmlspecialchars($_POST['edit_password']);
    $entry_id = htmlspecialchars($_POST['edit_id']);

    try {
        // Call the changeEntry() method to update the entry in the database
        $database->changeEntry($database->getUserId(), $website, $username, $password, $entry_id);
    } catch (Exception $e) {
        // Log the exception for debugging purposes
        error_log($e);

        // Return an error message to the JavaScript code
        $errorMsg = "An error occurred. Please try again later.";
        // Send an error response to the JavaScript code
        echo json_encode(['success' => false, 'error' => $errorMsg]);
        exit;
    }
    echo json_encode(['success' => true, 'redirect' => '/overview/']);
    exit;
}