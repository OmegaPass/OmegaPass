<?php

include_once "../db.php";

$errorMsg = null;


if (isset($_POST['oldPassword']) && isset($_POST['newPassword'])) {
    $success = changeMasterPass(getUserId(), $_POST['oldPassword'], $_POST['newPassword']);

    if ($success) {
        unset($_SESSION['masterpass']);
        header('Location: /');
    }

    $errorMsg = "You entered the wrong password.";
}

if (isset($_POST['newUsername'])) {
    changeUsername(getUserId(), trim($_POST['newUsername']));
    header('Location: /overview/');
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>OmegaPass</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/account-settings.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    </head>
    <body>
        <h1>Account</h1>
        <section class="change-username">
            <h2>Change your account username</h2>
            <form class="change-username-form" method="post" action="">
                <label>New username</label>
                <input type="text" name="newUsername" required>
                <button type="submit">Change</button>
            </form>
        </section>
        <section class="change-masterpass">
            <h2>Change your account password</h2>
            <?php echo "<p class='errorMsg'>$errorMsg</p>"?>
            <form class="change-masterpass-form" method="post" action="">
                <label>Old password</label>
                <div class="change-masterpass-form-input">
                    <input type="password" name="oldPassword" required class="password-input">
                    <span toggle="#password-field" class="toggle-password bi-eye"></span>
                </div>
                <label>New password</label>
                <div class="change-masterpass-form-input">
                    <input type="password" name="newPassword" required class="password-input">
                    <span toggle="#password-field" class="toggle-password bi-eye"></span>
                </div>
                <button type="submit">Change</button>
            </form>
        </section>



        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script src="../js/account-settings.js"></script>
    </body>
</html>
