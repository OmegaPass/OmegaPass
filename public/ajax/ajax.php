<?php
// Import required dependencies
include "../../db.php";
include_once "../../crypt.php";

// When not logged in you the client gets redirected to the homepage
if (!isset($_SESSION['masterpass']) && !isset($_SESSION['username'])) {
    header('Location: /');
}

// Create a new instance of the database class
$database = new DataBase();

// Get password data by ID for the overview page
if (isset($_GET['getPass'])) {
    $password = $database->get_password($_GET['getPass']);

    if ($password === null) {
        // Send http code 204 = No content
        http_response_code('204');
    }

    echo json_encode($password);
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
