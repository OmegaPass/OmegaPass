<?php
// Import required dependencies
include_once '../../config.php';
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
    exit;
}
