<?php
// Import required dependencies
include "../db.php";
include_once "../crypt.php";

// Create a new instance of the database class
$database = new DataBase();

// Get password data by ID for the overview page
if (isset($_GET['getPass'])) {
    echo json_encode($database->get_all_entries($database->getUserId(), $_GET['mode'])[$_GET['getPass']]);
}

// Generate a password with specified length, and optional digits and special characters
if ($_POST['generate']) {
    $digits = filter_var($_POST['digits'], FILTER_VALIDATE_BOOLEAN);
    $special = filter_var($_POST['special'], FILTER_VALIDATE_BOOLEAN);

    // Call the generate_password() function and return the result
    echo generate_password($_POST['length'], $digits, $special);
}

// Check the strength of a password
if ($_POST['passwordStrength'] && is_string($_POST['password'])) {
    // Call the check_password_strength() function and return the result
    echo check_password_strength($_POST['password']);
}
