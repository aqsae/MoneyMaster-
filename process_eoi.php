?php

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["jobref"])) {
    header("Location: apply.php");
    exit();
}

?>
