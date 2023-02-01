<?php
session_start();
$_SESSION['masterpass'] = $_POST['masterpass'];
?>

<form method="post" action="">
    <input type="password" name="masterpass">
    <input type="submit">
</form>