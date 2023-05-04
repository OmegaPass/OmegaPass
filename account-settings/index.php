<?php

// Include the database file that contains the DataBase class.
include_once "../db.php";

// When not logged in you the client gets redirected to the homepage
if (!isset($_SESSION['masterpass']) && !isset($_SESSION['username'])) {
    header('Location: /');
}

// Create a new instance of the DataBase class.
$database = new DataBase();

// Initialize the $errorMsg variable to null.
$errorMsg = null;

// If the 'oldPassword' and 'newPassword' variables are set in the $_POST array.
if (isset($_POST['oldPassword']) && isset($_POST['newPassword'])) {

    // Attempt to change the master password for the current user with the given old and new passwords.
    try {
        $success = $database->changeMasterPass($database->getUserId(), $_POST['oldPassword'], $_POST['newPassword']);
    } catch (Exception $e) {
        // TODO:
    }

    // If the password change was successful.
    if ($success) {

        // Unset the 'masterpass' key in the $_SESSION array.
        unset($_SESSION['masterpass']);

        // Redirect the user to the homepage.
        header('Location: /');
    }

    // Otherwise, set the $errorMsg variable to indicate that the password was incorrect.
    $errorMsg = "You entered the wrong password.";
}

// If the 'newUsername' variable is set in the $_POST array.
if (isset($_POST['newUsername'])) {

    // Attempt to change the username for the current user to the given new username.
    try {
        $database->changeUsername($database->getUserId(), trim($_POST['newUsername']));
    } catch (Exception $e) {
        // TODO:
    }

    // Redirect the user to the overview page.
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
