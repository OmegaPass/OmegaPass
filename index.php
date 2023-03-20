<?php
session_start();
include 'db.php';

$errormsg = "";

if (isset($_POST['username']) && isset($_POST['pswd'])) 
{
    if(isset($_POST['btnSignup']))
    {
        if ($_POST['conf_pswd'] == $_POST['pswd']){
            switch (add_user($_POST['username'], $_POST['pswd'])) {
                case 'Success':
                    $_SESSION['masterpass'] = $_POST['pswd'];
                    $_SESSION['username'] = $_POST['username'];
        
                    header("Location: /overview/index.php");
                    exit();
                    break;
                case 'Username already taken':
                    $errormsg = 'Username already taken';
                    break;
                default:
                    $errormsg = 'error';
            }
        }
        else {
            $errormsg = 'Passwords don\'t match each other';
        }
    }

    if(isset($_POST['btnLogin']))
    {
        switch (login($_POST['username'], $_POST['pswd'])) {
            case 'Success':
                $_SESSION['masterpass'] = $_POST['pswd'];
                $_SESSION['username'] = $_POST['username'];

                header("Location: /overview/index.php");
                exit();
                break;
            case 'Wrong password':
                $errormsg = 'Wrong password';
                break;
            case 'Username not found':
                $errormsg = 'Username not found';
                break;
            default:
                $errormsg = 'error';
        }
    }
}

if (isset($_SESSION['username']) && isset($_SESSION['masterpass'])) {
    header("Location: /overview/index.php");
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
	<link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
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
</body>
</html>
