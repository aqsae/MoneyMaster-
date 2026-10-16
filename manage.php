<?php
//blocks direct url access, bounces back to login.php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>
