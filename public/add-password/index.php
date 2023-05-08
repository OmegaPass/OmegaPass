<?php
// Import required dependencies
include_once '../../config.php';
// include the database connection file
include "../../db.php";

// When not logged in you the client gets redirected to the homepage
if (!isset($_SESSION['masterpass']) && !isset($_SESSION['username'])) {
    header('Location: /');
}

// instantiate a new database object
$database = new DataBase();

// check if the form has been submitted
if (isset($_POST['website']) && isset($_POST['username']) && isset($_POST['password'])) {
    // if the form has been submitted, add the password to the database
    // using the add_password() function of the database object
    $database->add_password($database->getUserId(), trim($_POST['website']), trim($_POST['username']), trim($_POST['password']));
    // redirect the user to the overview page
    echo "<script> location.href='/overview/'; </script>";
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
        <link rel="stylesheet" href="../css/add-password.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
        <link rel="icon" href="../omegapass.jpg">
    </head>
    <body>
        <section>
            <form class="add-password-card" id="add-password-form" action="" method="post">
                <label>Website</label>
                <input type="text" placeholder="Website" required id="form-website" name="website">
                <label>Username</label>
                <input type="text" placeholder="Username" required id="form-username" name="username">
                <label>Password</label>
                <div class="form-password-field">
                    <div>
                        <input type="password" placeholder="Password" required id="form-password" name="password">
                        <span toggle="#password-field" class="toggle-password bi-eye"></span>
                    </div>
                    <div id="progress">
                        <div id="progressBar"></div>
                    </div>
                    <p>Generate a password</p>
                    <div class="gen-field">
                        <div>
                            <input type="number" id="gen-length">
                            <label>Number of characters</label>
                            <input type="checkbox" id="gen-digits">
                            <label>Numbers</label>
                            <input type="checkbox" id="gen-special">
                            <label>Special characters</label>
                        </div>
                        <button id="generate" type="button">Generate and fill</button>
                    </div>
                </div>
                <button type="submit" id="submitButton">Save</button>
            </form>
        </section>


        <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

        <script src="../js/add-password.js"></script>
    </body>
</html>



