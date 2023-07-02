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

if (isset($_POST['query'])) {
    $searchQuery = $_POST['query'];

    $entries = $database->searchEntries($database->getUserId(), $searchQuery);
    foreach ($entries as $key => $entry) {
        echo "<li class='entries' id='entry-{$key}' data-id='{$entry['id']}'>";
        echo "<p>" . htmlspecialchars($entry['website'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p>" . htmlspecialchars($entry['username'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "</li>";
    }
    exit;
}

if (isset($_POST['pageNumber'])) {
    $pageNumber = (int) $_POST['pageNumber'];

    $entries = $database->get_all_entries($database->getUserId(), null, $pageNumber);

    foreach ($entries as $key => $entry) {
        echo "<li class='entries' id='entry-{$key}' data-id='{$entry['id']}'>";
        echo "<p>" . htmlspecialchars($entry['website'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p>" . htmlspecialchars($entry['username'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "</li>";
    }
    exit;
}

if (isset($_GET['getPageNumbers'])) {
    $totalCount = $database->getCountOfEntries($database->getUserId(), $_GET['mode'], $_GET['query']);
    $possiblePages = floor(($totalCount / 10));

    for ($i = 1; $i < $possiblePages + 1; $i++) {
        $selectClass = '';

        if ($i === 1) {
            $selectClass = 'selected';
        }

        echo "<button class='page_selector " . $selectClass . "' data-page_number='$i'>$i</button>";
    }
}
