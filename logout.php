<?php
session_start();

// get rid of all session stuff 
$_SESSION = array();

// if session was using cookies, delete session cookie by changing its expiration time to a past time (cause deletion of cookie)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// destroy session!!!
session_destroy();

// redirect page back to login
header("Location: login.php");
exit();
?>
