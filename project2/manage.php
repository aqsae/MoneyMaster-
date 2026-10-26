<?php
// only logged-in managers can see this page
session_start();
// no username in the session means they never logged in, so send them to login.php
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// escape text for display without double-escaping data that was cleaned on the way in
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8", false);
}

// connect using the details in settings.php
require_once "settings.php";
mysqli_report(MYSQLI_REPORT_OFF); // turn off mysqli crashing so we can show our own error messages
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

$message = "";       // shows after a delete or status change
$db_error = "";      // shows if something goes wrong with the database
$results = array();  // all the EOI rows we'll put in the table

// allowed sort fields (column name => label) column names can't use prepared statement placeholders
$sort_fields = array(
    "EOInumber"      => "EOI number",
    "job_ref"        => "Job reference",
    "first_name"     => "First name",
    "last_name"      => "Last name",
    "dob"            => "Date of birth",
    "gender"         => "Gender",
    "street_address" => "Street address",
    "suburb"         => "Suburb",
    "state"          => "State",
    "postcode"       => "Postcode",
    "email"          => "Email",
    "phone"          => "Phone",
    "skills"         => "Skills",
    "other_skills"   => "Other skills",
    "status"         => "Status"
);
$statuses = array("New", "Current", "Final"); // only statuses allowed, so no one can edit the values and input a fake one

// search values from the form (kept so the form remembers them)
$search_jobref = isset($_GET["jobref"])    ? trim($_GET["jobref"])    : "";
$search_first  = isset($_GET["firstname"]) ? trim($_GET["firstname"]) : "";
$search_last   = isset($_GET["lastname"])  ? trim($_GET["lastname"])  : "";
// only accept a sort column from the list above, otherwise just sort by EOI number
$sort  = (isset($_GET["sort"]) && array_key_exists($_GET["sort"], $sort_fields)) ? $_GET["sort"] : "EOInumber";
$order = (isset($_GET["order"]) && $_GET["order"] == "DESC") ? "DESC" : "ASC"; // can only ever be DESC or ASC

if (!$conn) {
    $db_error = "Unable to connect to the database.";
} else {

    // handle changes: delete by job reference, or change a status
    // the hidden "action" field in each form tells us which form was used
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

        if ($_POST["action"] == "delete") {
            $delete_jobref = isset($_POST["delete_jobref"]) ? trim($_POST["delete_jobref"]) : "";

            // checkbox only gets sent if it's ticked, so no tick = no delete
            if (!isset($_POST["confirm_delete"])) {
                $message = "Please tick the confirmation box to delete EOIs.";
            } elseif (!preg_match("/^[A-Za-z0-9]{6}$/", $delete_jobref)) {
                $message = "Job reference must be exactly 6 letters or numbers.";
            } else {
                // prepared statement so the job ref can't mess with the sql (stops sql injection)
                $stmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref = ?");
                if (!$stmt) {
                    $message = "The EOI table does not exist yet.";
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $delete_jobref);
                    mysqli_stmt_execute($stmt);
                    $count = mysqli_stmt_affected_rows($stmt); // how many EOIs actually got deleted
                    mysqli_stmt_close($stmt);
                    if ($count > 0) {
                        $message = "Deleted $count EOI(s) for job reference " . e($delete_jobref) . ".";
                    } else {
                        $message = "No EOIs found for job reference " . e($delete_jobref) . ".";
                    }
                }
            }

        } elseif ($_POST["action"] == "status") {
            $eoi_id     = isset($_POST["eoi_number"]) ? (int)$_POST["eoi_number"] : 0; // (int) turns anything weird into 0
            $new_status = isset($_POST["new_status"]) ? $_POST["new_status"] : "";

            // make sure it's a real EOI number and the status is one of New, Current or Final
            if ($eoi_id <= 0 || !in_array($new_status, $statuses)) {
                $message = "Invalid status change.";
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
                if (!$stmt) {
                    $message = "The EOI table does not exist yet.";
                } else {
                    mysqli_stmt_bind_param($stmt, "si", $new_status, $eoi_id); // "si" = a string then an integer
                    mysqli_stmt_execute($stmt);
                    $count = mysqli_stmt_affected_rows($stmt);
                    mysqli_stmt_close($stmt);
                    if ($count > 0) {
                        $message = "EOI $eoi_id status changed to $new_status.";
                    } else {
                        // nothing changed, usually because it already had that status
                        $message = "EOI $eoi_id was not changed (it may already be $new_status).";
                    }
                }
            }
        }
    }

    // build the list query: all EOIs, or filtered by job reference and/or name
    // each search box that's filled in adds a "?" condition, and its value goes into $params
    $sql = "SELECT * FROM eoi";
    $conditions = array();
    $types = "";
    $params = array();

    if ($search_jobref != "") {
        $conditions[] = "job_ref = ?";
        $types .= "s";
        $params[] = $search_jobref;
    }
    // the % on both sides means partial matches work, e.g. "al" finds "Alice"
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
    $sql .= " ORDER BY $sort $order"; // safe because both were checked against fixed lists earlier

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $db_error = "The EOI table does not exist yet. Submit an application first.";
    } else {
        if ($types != "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params); // ... unpacks the list so each value gets passed separately
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        // grab each matching row one at a time and add it to the results
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoneyMaster - HR manager EOI management">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>Manage EOIs | MoneyMaster</title>
</head>

<body>
    <?php include 'header.inc'; ?>

    <main class="manage-page">
        <h1>Manage Expressions of Interest</h1>

        <div class="manage-bar">
            <span>Logged in as <strong><?php echo e($_SESSION["username"]); ?></strong></span>
            <a href="logout.php">Log out</a> <?php /* logout.php wipes the session and sends them back to login */ ?>
        </div>

        <?php if ($message != "") { ?>
            <div class="manage-message"><?php echo $message; ?></div>
        <?php } ?>

        <section>
            <h2>Find EOIs</h2>
            <form action="manage.php" method="GET">
                <div class="form-row">
                    <div>
                        <label for="jobref">Job reference</label>
                        <input type="text" id="jobref" name="jobref" maxlength="6" value="<?php echo e($search_jobref); ?>">
                    </div>
                    <div>
                        <label for="firstname">First name</label>
                        <input type="text" id="firstname" name="firstname" maxlength="20" value="<?php echo e($search_first); ?>">
                    </div>
                    <div>
                        <label for="lastname">Last name</label>
                        <input type="text" id="lastname" name="lastname" maxlength="20" value="<?php echo e($search_last); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label for="sort">Sort by</label>
                        <select id="sort" name="sort">
                            <?php foreach ($sort_fields as $field => $label) { ?>
                                <option value="<?php echo $field; ?>" <?php if ($sort == $field) echo "selected"; ?>><?php echo $label; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label for="order">Order</label>
                        <select id="order" name="order">
                            <option value="ASC" <?php if ($order == "ASC") echo "selected"; ?>>Ascending</option>
                            <option value="DESC" <?php if ($order == "DESC") echo "selected"; ?>>Descending</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button type="submit">Search</button>
                    <a href="manage.php">List all EOIs</a>
                </div>
            </form>
        </section>

        <section>
            <h2>Delete EOIs by Job Reference</h2>
            <form action="manage.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <label for="delete_jobref">Job reference</label>
                <input type="text" id="delete_jobref" name="delete_jobref" maxlength="6">
                <p class="confirm-text">
                    <label for="confirm_delete">I understand this will delete ALL EOIs for this job reference</label>
                </p>
                <div class="delete-row">
                    <button type="submit">Delete EOIs</button>
                    <input type="checkbox" id="confirm_delete" name="confirm_delete" value="yes">
                </div>
            </form>
        </section>

        <section>
            <h2>Results (<?php echo count($results); ?>)</h2>

            <?php if ($db_error != "") { ?>
                <div class="manage-message"><?php echo $db_error; ?></div>
            <?php } elseif (count($results) == 0) { ?>
                <div class="manage-message">No EOIs found.</div>
            <?php } else { ?>
                <div class="manage-table-wrap">
                    <table class="manage-table">
                        <thead>
                            <tr>
                                <th>EOI #</th>
                                <th>Job ref</th>
                                <th>Name</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Skills</th>
                                <th>Other skills</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row) { ?>
                                <tr>
                                    <td><?php echo e($row["EOInumber"]); ?></td>
                                    <td><?php echo e($row["job_ref"]); ?></td>
                                    <td><?php echo e($row["first_name"] . " " . $row["last_name"]); ?></td>
                                    <td><?php echo e($row["dob"]); ?></td>
                                    <td><?php echo e($row["gender"]); ?></td>
                                    <td><?php echo e($row["street_address"] . ", " . $row["suburb"] . " " . $row["state"] . " " . $row["postcode"]); ?></td>
                                    <td><?php echo e($row["email"]); ?></td>
                                    <td><?php echo e($row["phone"]); ?></td>
                                    <td><?php echo e($row["skills"]); ?></td>
                                    <td><?php echo e($row["other_skills"]); ?></td>
                                    <td>
                                        <?php /* keeps the current search in the url so you stay on the same results after updating */ ?>
                                        <form action="manage.php?<?php echo e(isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : ""); ?>" method="POST">
                                            <input type="hidden" name="action" value="status">
                                            <input type="hidden" name="eoi_number" value="<?php echo e($row["EOInumber"]); ?>">
                                            <select name="new_status" aria-label="Status for EOI <?php echo e($row["EOInumber"]); ?>">
                                                <?php foreach ($statuses as $status) { ?>
                                                    <option value="<?php echo $status; ?>" <?php if ($row["status"] == $status) echo "selected"; ?>><?php echo $status; ?></option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </section>
    </main>

    <?php include 'footer.inc'; ?>
</body>
</html>
