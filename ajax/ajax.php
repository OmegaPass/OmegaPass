<?php
include "../db.php";
include_once "../crypt.php";

if (isset($_GET['getPass'])) {
    echo json_encode(get_all_entries(getUserId(), $_GET['mode'])[$_GET['getPass']]);
}

if ($_POST['generate']) {
    $digits = filter_var($_POST['digits'], FILTER_VALIDATE_BOOLEAN);
    $special = filter_var($_POST['special'], FILTER_VALIDATE_BOOLEAN);
    
    echo generate_password($_POST['length'], $digits, $special);
}

if ($_POST['passwordStrength'] && is_string($_POST['password'])) {
    echo check_password_strength($_POST['password']);
}