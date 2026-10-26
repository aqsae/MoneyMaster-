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
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoneyMaster - HR manager login">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>Login | MoneyMaster</title>
    <style>
       
/* login page / form styling */

main > h1 {
    color: var(--dark-green);
    text-align: center;
    margin-bottom: 2rem;
}

/* login form !!! */
main > section:has(form) {
    max-width: 450px;
    margin: 0 auto 3rem;
    padding: 2.5rem;
    background-color: var(--soft-green);
    border: 1px solid var(--light-green);
    border-top: 5px solid var(--mid-green);
    border-radius: var(--radius);
    box-shadow: 0 10px 30px rgba(0, 53, 53, 0.12);
}

/* username and password labels!! */
main > section label {
    display: block;
    margin-bottom: 0.4rem;
    color: var(--dark-green);
    font-weight: bold;
}

/* login inputs of username and password!! */
main > section input[type="text"],
main > section input[type="password"] {
    width: 100%;
    max-width: 100%;
    padding: 0.85rem 1rem;
    border: 1.5px solid var(--mid-green);
    border-radius: 8px;
    background-color: var(--cream);
    color: var(--dark-green);
    font-size: 1rem;
    transition: border-color 0.3s, box-shadow 0.3s;
}

main > section input[type="text"]:focus,
main > section input[type="password"]:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(72, 139, 60, 0.15);
}

/* login button */
main > section button[type="submit"] {
    width: 100%;
    padding: 0.85rem;
    margin-top: 0.5rem;
    border: 1.5px solid var(--dark-green);
    border-radius: 20px;
    background-color: var(--dark-green);
    color: var(--cream);
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

main > section button[type="submit"]:hover {
    background-color: var(--accent-color);
    border-color: var(--accent-color);
    transform: translateY(-2px);
}

/* error message */
main > section > p.error {
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    border-left: 5px solid #b3261e;
    border-radius: 8px;
    background-color: #fdecea;
    color: #7a1c15;
    font-weight: bold;
}

/* mobile layout for accessibility !!!*/
@media (max-width: 720px) {
    main > section:has(form) {
        padding: 1.5rem;
    }
}

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
