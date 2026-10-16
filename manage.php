<?php
//blocks direct url access, bounces back to login.php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8", false);
}

require_once "settings.php";
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

$message = "";
$db_error = "";
$results = array();

$sort_fields = array(
    "EOInumber"  => "EOI number",
    "job_ref"    => "Job reference",
    "first_name" => "First name",
    "last_name"  => "Last name",
    "status"     => "Status"
);
$statuses = array("New", "Current", "Final");
?>
