<?php
include "../db.php";
include_once "../crypt.php";

if (isset($_GET['getPass'])) {
    echo json_encode(get_all_entries(getUserId())[$_GET['getPass']]);
}

if ($_POST['generate']) {
    echo generate_password($_POST['length'], $_POST['numbers'], $_POST['special']);
}

if ($_POST['passwordStregth'] && is_string($_POST['password'])) {
    echo check_password_strength($_POST['password']);
}