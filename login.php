<?php
// startsession with security
session_start([
    'cookie_lifetime' => 0,
    'cookie_secure'   => isset($_SERVER['HTTPS']), // checks if the request entry exists in HTTPS, if so, makes cookie secure!
    'cookie_httponly' => true,  // makes it harder for hackers to steal the session cookie with XSS
    'cookie_samesite' => 'Strict' // makes it so that the cookie isn't sent when you use links from other unrelated sites to prevents CRSF attacks!!
]);

require_once 'settings.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // clean up basic inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$sql_db;charset=utf8mb4", $user, $pwd, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // sql query to get user by username
            $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = :username LIMIT 1');
            $stmt->execute(['username' => $username]);
            $found_user = $stmt->fetch();

            if ($found_user && password_verify($password, $found_user['password_hash'])) {
                // clear any existing session data to prevent a session from being hijacked!
                session_regenerate_id(true);

                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['last_activity'] = time(); // store the time of the last successful login so wecan implement session timeout if needed

                header("Location: manage.php");
                exit();
            } else {
                // try to avoid giving away too much information about the error, so we don't tell the user if the username or password was wrong
                $error = "Invalid username or password.";
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
    <link rel="stylesheet" href="styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoneyMaster - HR manager login">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>Login | MoneyMaster</title>
    <style>
        main > section:has(form) { max-width: 450px; }
        main > section > p.error {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-left: 5px solid #b3261e;
            border-radius: 8px;
            background-color: #fdecea;
            color: #7a1c15;
            font-weight: bold; }
        input[type="password"] {
            width: 100%;
            max-width: 400px;
            padding: 0.75rem;
            border: 1.5px solid var(--mid-green);
            border-radius: 8px;
            color: var(--dark-green);
            background-color: var(--cream);
            font-size: 1rem; }
        input[type="password"]:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(72, 139, 60, 0.15); }
    </style>
</head>
<body>
    <?php include 'header.inc'; ?>

    <main>
        <h1>System Access</h1>

        <section>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); /* dont alow html injection in error messages */ ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php" autocomplete="off">
                <p>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </p>
                <p>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </p>
                <p>
                    <button type="submit">Login</button>
                </p>
            </form>
        </section>
    </main>

    <?php include 'footer.inc'; ?>
</body>
</html>
