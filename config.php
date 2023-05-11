<?php

// Set the session cookie parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);

// Set a custom session name
session_name('OP_SESSION');

// Start the session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>