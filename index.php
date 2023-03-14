<?php
session_start();
include 'db.php';

if (isset($_POST['username']) && isset($_POST['pswd'])) 
{
    if(isset($_POST['signup_s']))
    {
        switch (add_user($_POST['username'], $_POST['pswd'])) {
            case 'Success':
                $_SESSION['masterpass'] = $_POST['pswd'];
                $_SESSION['username'] = $_POST['username'];
    
                header("Location: /overview/overview.php");
                exit();
                break;
            case 'Username already taken':
                echo 'Username already taken';
                break;
            default:
                echo 'error';
        }
    }

    if(isset($_POST['login_s']))
    {
        switch (login($_POST['username'], $_POST['pswd'])) {
            case 'Success':
                $_SESSION['masterpass'] = $_POST['pswd'];
                $_SESSION['username'] = $_POST['username'];

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
}

if (isset($_SESSION['username']) && isset($_SESSION['masterpass'])) {
    header("Location: /overview/overview.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>OmegaPass</title>
	<link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>

<body>
	<div class="main">  	
		<input type="checkbox" id="chk" aria-hidden="true">

			<div class="signup" method="post" action="index.php">
				<form>
					<label for="chk" aria-hidden="true">Sign up</label>
					<input type="text" name="username" placeholder="Username" required="">
					<input type="email" name="email" placeholder="Email" required="">
					<input type="password" name="pswd" placeholder="Password" required="">
					<button type="submit" name="signup_s"> Sign up </button>
				</form>
			</div>

			<div class="login" method="post" action="index.php">
				<form>
					<label for="chk" aria-hidden="true">Login</label>
					<input type="text" name="username" placeholder="Username" required="">
					<input type="password" name="pswd" placeholder="Password" required="">
					<button type="submit" name="login_s"> Login </button>
				</form>
			</div>
	</div>
</body>
</html>
