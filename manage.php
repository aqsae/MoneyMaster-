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

$search_jobref = isset($_GET["jobref"])    ? trim($_GET["jobref"])    : "";
$search_first  = isset($_GET["firstname"]) ? trim($_GET["firstname"]) : "";
$search_last   = isset($_GET["lastname"])  ? trim($_GET["lastname"])  : "";
$sort  = (isset($_GET["sort"]) && array_key_exists($_GET["sort"], $sort_fields)) ? $_GET["sort"] : "EOInumber";
$order = (isset($_GET["order"]) && $_GET["order"] == "DESC") ? "DESC" : "ASC";

if (!$conn) {
    $db_error = "Unable to connect to the database.";
} else {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

        if ($_POST["action"] == "delete") {
            $delete_jobref = isset($_POST["delete_jobref"]) ? trim($_POST["delete_jobref"]) : "";

            if (!preg_match("/^[A-Za-z0-9]{6}$/", $delete_jobref)) {
                $message = "Job reference must be exactly 6 letters or numbers.";
            } else {
                $stmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref = ?");
                if (!$stmt) {
                    $message = "The EOI table does not exist yet.";
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $delete_jobref);
                    mysqli_stmt_execute($stmt);
                    $count = mysqli_stmt_affected_rows($stmt);
                    mysqli_stmt_close($stmt);
                    if ($count > 0) {
                        $message = "Deleted $count EOI(s) for job reference " . e($delete_jobref) . ".";
                    } else {
                        $message = "No EOIs found for job reference " . e($delete_jobref) . ".";
                    }
                }
            }
        } elseif ($_POST["action"] == "status") {
            $eoi_id     = isset($_POST["eoi_number"]) ? (int)$_POST["eoi_number"] : 0;
            $new_status = isset($_POST["new_status"]) ? $_POST["new_status"] : "";

            if ($eoi_id <= 0 || !in_array($new_status, $statuses)) {
                $message = "Invalid status change.";
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
                if (!$stmt) {
                    $message = "The EOI table does not exist yet.";
                } else {
                    mysqli_stmt_bind_param($stmt, "si", $new_status, $eoi_id);
                    mysqli_stmt_execute($stmt);
                    $count = mysqli_stmt_affected_rows($stmt);
                    mysqli_stmt_close($stmt);
                    if ($count > 0) {
                        $message = "EOI $eoi_id status changed to $new_status.";
                    } else {
                        $message = "EOI $eoi_id was not changed (it may already be $new_status).";
                    }
                }
            }
        }
    $sql = "SELECT * FROM eoi";
    $conditions = array();
    $types = "";
    $params = array();

    if ($search_jobref != "") {
        $conditions[] = "job_ref = ?";
        $types .= "s";
        $params[] = $search_jobref;
    }
    if ($search_first != "") {
        $conditions[] = "first_name LIKE ?";
        $types .= "s";
        $params[] = "%" . $search_first . "%";
    }
    if ($search_last != "") {
        $conditions[] = "last_name LIKE ?";
        $types .= "s";
        $params[] = "%" . $search_last . "%";
    }

    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY $sort $order";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $db_error = "The EOI table does not exist yet. Submit an application first.";
    } else {
        if ($types != "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
