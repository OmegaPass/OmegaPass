<?php
include 'db.php';
session_start();
$_SESSION['masterpass'] = $_POST['masterpass'];
$_SESSION['username'] = $_POST['username'];


if (isset($_POST['username']) && isset($_POST['masterpass'])) {
    switch (login($_POST['username'], $_SESSION['masterpass'])) {
        case 'Success':
            echo "<script> location.href='/overview/overview.php'; </script>";
            exit;
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
?>

<form method="post" action="index.php">
    <h4>Username</h4>
    <input type="text" name="username">
    <h4>Master password</h4>
    <input type="password" name="masterpass">
    <input type="submit" value="Login">
</form>