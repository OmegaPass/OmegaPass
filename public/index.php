<?php
session_start(); // Start a new or existing session
include '../db.php'; // Include the file with the database connection
$database = new DataBase(); // Create a new instance of the database connection class

$errormsg = ""; // Initialize the error message variable as an empty string

// Check if username and password are set in the $_POST superglobal array
if (isset($_POST['username']) && isset($_POST['pswd']))
{
    // If the 'btnSignup' button was pressed, attempt to add a new user to the database
    if(isset($_POST['btnSignup']))
    {
        // Check if the password and confirm password fields match
        if ($_POST['conf_pswd'] == $_POST['pswd']){
            // Call the add_user method of the database object to add a new user and
            // store the result
            switch ($database->add_user($_POST['username'], $_POST['pswd'])) {
                // If the user was added successfully, set the session variables and
                // redirect to the overview page
                case 'Success':
                    $_SESSION['masterpass'] = $_POST['pswd'];
                    $_SESSION['username'] = $_POST['username'];

                    header("Location: /overview/");
                    exit();
                    break;
                // If the username is already taken, set the error message variable
                case 'Username already taken':
                    $errormsg = 'Username already taken';
                    break;
                // If an unknown error occurred, set the error message variable
                default:
                    $errormsg = 'error';
            }
        }
        // If the password and confirm password fields don't match, set the error message
        else {
            $errormsg = 'Passwords don\'t match each other';
        }
    }

    // If the 'btnLogin' button was pressed, attempt to log in the user
    if(isset($_POST['btnLogin']))
    {
        // Call the login method of the database object to authenticate the user and
        // store the result
        switch ($database->login($_POST['username'], $_POST['pswd'])) {
            // If the login was successful, set the session variables and redirect
            // to the overview page
            case 'Success':
                $_SESSION['masterpass'] = $_POST['pswd'];
                $_SESSION['username'] = $_POST['username'];

                header("Location: /overview/");
                exit();
                break;
            // If the password is incorrect, set the error message variable
            case 'Wrong password':
                $errormsg = 'Wrong password';
                break;
            // If the username is not found, set the error message variable
            case 'Username not found':
                $errormsg = 'Username not found';
                break;
            // If an unknown error occurred, set the error message variable
            default:
                $errormsg = 'error';
        }
    }
}

// If the user is already logged in, redirect to the overview page
if (isset($_SESSION['username']) && isset($_SESSION['masterpass'])) {
    header("Location: /overview/");
    exit();
}

?>

<!-- The following HTML code defines a login and signup form -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>OmegaPass</title>
	<link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link rel="icon" href="omegapass.jpg">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>
</head>

<body>
	<div class="main">

		<input type="checkbox" id="chk" aria-hidden="true">
            <span id="errormsg"> <?php echo $errormsg; ?> </span>
			<div class="login">
				<form method="post" action="index.php">
					<label for="chk" aria-hidden="true">Login</label>
					<input type="text" name="username" placeholder="Username" required="">
					<input type="password" name="pswd" placeholder="Password" required="">
					<button type="submit" name="btnLogin">Login</button>
				</form>
			</div>

            <div class="signup">
				<form method="post" action="index.php">
					<label for="chk" aria-hidden="true">Sign up</label>
					<input type="text" name="username" placeholder="Username" required="">
                    <?php // For later implementation of reset password feature: <input type="email" name="email" placeholder="Email" required=""> ?>
					<input type="password" name="pswd" placeholder="Password" required="">
                    <input type="password" name="conf_pswd" placeholder="Confirm password" required="">
					<button type="submit" name="btnSignup">Sign up</button>
				</form>
			</div>
	</div>
    <footer>
        <a href="/imprint/">Imprint</a>
        <a href="/privacy-policy/">Privacy policy</a>
    </footer>
</body>
</html>
