<?php
session_start();
include 'db.php';

if (isset($_POST['username']) && isset($_POST['masterpass'])) {

    $_SESSION['masterpass'] = $_POST['masterpass'];
    $_SESSION['username'] = $_POST['username'];

    switch (login($_POST['username'], $_SESSION['masterpass'])) {
        case 'Success':
            header("Location: /overview/overview.php");
            exit();
            break;
        case 'Wrong password':
            echo 'Wrong password';
            break;
        case 'Username not found':
            echo 'Username not found';
            break;
        default:
            echo 'error';
    }
}

if (isset($_SESSION['username']) && isset($_SESSION['masterpass'])) {
    header("Location: /overview/overview.php");
    exit();
}

?>

<form method="post" action="index.php">
    <h4>Username</h4>
    <input type="text" name="username">
    <h4>Master password</h4>
    <input type="password" name="masterpass">
    <input type="submit" value="Login">
</form>
