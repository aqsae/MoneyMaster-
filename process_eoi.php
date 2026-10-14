<?php

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["jobref"])) {
    header("Location: apply.php");
    exit();
}
  function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
