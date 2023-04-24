<?php
include "../db.php";
include_once "../crypt.php";
$database = new DataBase();

if (isset($_GET['getPass'])) {
    echo json_encode($database->get_all_entries($database->getUserId(), $_GET['mode'])[$_GET['getPass']]);
}

if ($_POST['generate']) {
    $digits = filter_var($_POST['digits'], FILTER_VALIDATE_BOOLEAN);
    $special = filter_var($_POST['special'], FILTER_VALIDATE_BOOLEAN);

    echo generate_password($_POST['length'], $digits, $special);
}

if ($_POST['passwordStrength'] && is_string($_POST['password'])) {
    echo check_password_strength($_POST['password']);
}
