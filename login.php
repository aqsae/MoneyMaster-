<?php
// startsession with security 
session_start([
    'cookie_lifetime' => 0,
    'cookie_secure'   => isset($_SERVER['HTTPS']), // checks if the request entry exists in HTTPS, if so, makes cookie secure!
    'cookie_httponly' => true,  // makes it harder for hackers to steal the session cookie with XSS
    'cookie_samesite' => 'Strict' // makes it so that the cookie isn't sent when you use links from other unrelated sites to prevents CRSF attacks!!      
]);

require_once 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // clean up basic inputs 
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            // sql query to get user by username
            $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = :username LIMIT 1');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // clear any existing session data to prevent a session from being hijacked!
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['last_activity'] = time(); // store the time of the last successful login so wecan implement session timeout if needed

                header("Location: manage.php");
                exit();
            } else {
                // try to avoid giving away too much information about the error, so we don't tell the user if the username or password was wrong
                $error = "Invalid username or password configuration.";
            }
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $error = "An authentication system fault occurred.";
        }
    } else {
        $error = "Please complete all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Helvetica, sans-serif;
             display: flex;
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background: #f4f6f9; }
        .login-box { 
            background: white;
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
            width: 320px; }
        .error { color: #dc3545; 
         font-size: 0.9em; 
        margin-bottom: 15px; }
        input[type="text"], input[type="password"] { width: 100%;
         padding: 8px; 
         margin: 8px 0 16px; 
         border: 1px solid #ccc; 
         border-radius: 4px; 
         box-sizing: border-box; }
        button { width: 100%; 
        padding: 10px; 
        background: #007bff; 
        color: white; 
        border: none; 
        border-radius: 4px; 
        cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>System Access</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p> // dont alow html injection in error messages
    <?php endif; ?>

    <form method="POST" action="login.php" autocomplete="off"> 
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
