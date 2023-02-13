<?php
    session_start();
    $_SESSION['masterpass'] = $_POST['masterpass'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ΩPass</title>
</head>

<body>
    <form method="post" action="">
        <input type="password" name="masterpass">
        <input type="submit">
    </form>
</body>
</html>