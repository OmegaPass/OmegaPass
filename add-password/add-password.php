<?php
include "../db.php";

if (isset($_POST['website']) && isset($_POST['username']) && isset($_POST['password'])) {
    add_password(getUserId(), trim($_POST['website']), trim($_POST['username']), trim($_POST['password']));
    echo "<script> location.href='/overview/overview.php'; </script>";
}

?>

<link rel="stylesheet" type="text/css" href="./style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">

<section>
    <form class="add-password-card" id="add-password-form" action="" method="post">
        <label>Webseite</label>
        <input type="text" placeholder="Webseite" required id="form-website" name="website">
        <label>Benutzername</label>
        <input type="text" placeholder="Benutzername" required id="form-username" name="username">
        <label>Passwort</label>
        <div class="form-password-field">
            <div>
                <input type="password" placeholder="Passwort" required id="form-password" name="password">
                <span toggle="#password-field" class="toggle-password bi-eye"></span>
            </div>
            <div id="progress">
                <div id="progressBar"></div>
            </div>
            <p>Passwort generieren</p>
            <div class="gen-field">
                <div>
                    <input type="number" id="gen-length">
                    <label>Anzahl Buchstaben</label>
                    <input type="checkbox" id="gen-digits">
                    <label>Zahlen</label>
                    <input type="checkbox" id="gen-special">
                    <label>Sonderzeichen</label>
                </div>
                <button id="generate" type="button">Generieren und ausfüllen</button>
            </div>
        </div>
        <button type="submit" id="submitButton">Speichern</button>
    </form>
</section>


<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

<script src="add-password.js"></script>