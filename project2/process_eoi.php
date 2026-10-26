<?php
session_start(); // needed so we can send errors and the user's answers back to apply.php

// block direct URL access - only allow the form to post here
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["jobref"])) {
    header("Location: apply.php");
    exit();
}

require_once "eoi_options.php"; // the lists of valid states/postcodes and skills

// sanitise input: trim, strip slashes, escape html
function sanitise_input($data)
{
    // if it's not plain text (like someone sending an array instead), just treat it as empty
    if (!is_string($data)) {
        return '';
    }

    return trim($data);
}

// get the form values (empty string if missing)
$jobref      = isset($_POST["jobref"])      ? strtoupper(sanitise_input($_POST["jobref"])) : ""; // capitals so "eng4a2" still works
$firstname   = isset($_POST["firstname"])   ? sanitise_input($_POST["firstname"])   : "";
$lastname    = isset($_POST["lastname"])    ? sanitise_input($_POST["lastname"])    : "";
$dob         = isset($_POST["dob"])         ? sanitise_input($_POST["dob"])         : "";
$gender      = isset($_POST["gender"])      ? sanitise_input($_POST["gender"])      : "";
$address     = isset($_POST["address"])     ? sanitise_input($_POST["address"])     : "";
$suburb      = isset($_POST["suburb"])      ? sanitise_input($_POST["suburb"])      : "";
$state       = isset($_POST["state"])       ? sanitise_input($_POST["state"])       : "";
$postcode    = isset($_POST["postcode"])    ? sanitise_input($_POST["postcode"])    : "";
$email       = isset($_POST["email"])       ? sanitise_input($_POST["email"])       : "";
$phone       = isset($_POST["phone"])       ? sanitise_input($_POST["phone"])       : "";
$otherskills = isset($_POST["otherskills"]) ? sanitise_input($_POST["otherskills"]) : "";

// skills come in as a list because the checkboxes are named skills[]
$skills = array();
if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
    foreach ($_POST["skills"] as $skill) {
        $skills[] = sanitise_input($skill);
    }
}

// server-side validation (does not rely on the browser)
$errors = array(); // each error is saved under its field name so apply.php can show it under the right box

if (!preg_match("/^[A-Za-z0-9]{6}$/", $jobref)) {
    $errors["jobref"] = "Job reference number must be exactly 6 letters or numbers.";
}

if ($firstname == "") {
    $errors["firstname"] = "First name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $firstname)) {
    $errors["firstname"] = "First name must be letters only, max 20 characters.";
}

if ($lastname == "") {
    $errors["lastname"] = "Last name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $lastname)) {
    $errors["lastname"] = "Last name must be letters only, max 20 characters.";
}

// date input sends yyyy-mm-dd, but also accept dd/mm/yyyy
$dob_db = "";
if ($dob == "") {
    $errors["dob"] = "Date of birth is required.";
} else {
    if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $dob, $parts)) {
        $year = $parts[1]; $month = $parts[2]; $day = $parts[3];
    } elseif (preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $dob, $parts)) {
        $day = $parts[1]; $month = $parts[2]; $year = $parts[3];
    } else {
        $year = $month = $day = 0;
    }

    // checkdate catches fake dates like 31/02/2000
    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        $errors["dob"] = "Date of birth must be a valid date (dd/mm/yyyy).";
    } else {
        $dob_db = "$year-$month-$day";
        if ($dob_db > date("Y-m-d")) {
            $errors["dob"] = "Date of birth cannot be in the future.";
        } else {
            $age = date_diff(date_create($dob_db), date_create("today"))->y; // their age in full years
            if ($age < 15 || $age > 80) {
                $errors["dob"] = "Applicants must be between 15 and 80 years old.";
            }
        }
    }
}

// still checked here because the radio values can be edited in dev tools
if (!in_array($gender, array("female", "male", "other"))) {
    $errors["gender"] = "Please select a gender.";
}

if ($address == "") {
    $errors["address"] = "Street address is required.";
} elseif (strlen($address) > 40) {
    $errors["address"] = "Street address must be max 40 characters.";
}

if ($suburb == "") {
    $errors["suburb"] = "Suburb/town is required.";
} elseif (strlen($suburb) > 40) {
    $errors["suburb"] = "Suburb/town must be max 40 characters.";
}

if (!array_key_exists($state, $state_postcodes)) {
    $errors["state"] = "Please select a valid state.";
}

if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors["postcode"] = "Postcode must be exactly 4 digits.";
// first digit has to match the state, for example, VIC postcodes start with 3 or 8
} elseif (array_key_exists($state, $state_postcodes) && !in_array($postcode[0], $state_postcodes[$state])) {
    $errors["postcode"] = "Postcode $postcode does not match the state $state.";
}

if ($email == "") {
    $errors["email"] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // email checker
    $errors["email"] = "Email address is not valid.";
}

if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors["phone"] = "Phone number must be 8 to 12 digits.";
}

foreach ($skills as $skill) {
    if (!array_key_exists($skill, $skill_options)) {
        $errors["skills"] = "Invalid skill selected.";
        break; // stop after the first bad one so the error only shows once
    }
}

// they need to tick at least one skill OR write something in other skills
if (count($skills) == 0 && $otherskills == "") {
    $errors["skills"] = "Please select at least one skill or describe your other skills.";
}

// only touch the database if everything is valid
$eoi_number = 0;
$db_error = "";
$conn = false;

if (count($errors) == 0) {
    require_once "settings.php";
    mysqli_report(MYSQLI_REPORT_OFF); // turn off mysqli crashing so we can show our own error messages
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        $db_error = "Unable to connect to the database.";
    } else {
        // check the job ref is actually a real job in the jobs table
        $job_check = mysqli_prepare($conn, "SELECT ref FROM jobs WHERE ref = ?");
        if (!$job_check) {
            $db_error = "Unable to check the job reference.";
        } else {
            mysqli_stmt_bind_param($job_check, "s", $jobref);
            mysqli_stmt_execute($job_check);
            mysqli_stmt_store_result($job_check);
            if (mysqli_stmt_num_rows($job_check) == 0) {
                $errors["jobref"] = "Job reference $jobref does not match any of our current jobs. Please enter a valid job reference number.";
            }
            mysqli_stmt_close($job_check);
        }
    }
}

// if anything's wrong, save the errors and their answers in the session and send them back to the form
// so they don't have to retype everything
if (count($errors) > 0) {
    if ($conn) {
        mysqli_close($conn);
    }
    $_SESSION["eoi_errors"] = $errors;
    $_SESSION["eoi_old"] = array(
        "jobref"      => $jobref,
        "firstname"   => $firstname,
        "lastname"    => $lastname,
        "dob"         => $dob,
        "gender"      => $gender,
        "address"     => $address,
        "suburb"      => $suburb,
        "state"       => $state,
        "postcode"    => $postcode,
        "email"       => $email,
        "phone"       => $phone,
        "skills"      => $skills,
        "otherskills" => $otherskills
    );
    header("Location: apply.php");
    exit();
}

if ($conn && $db_error == "") {
    // create the eoi table if it does not exist
    $create_table = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        job_ref VARCHAR(6) NOT NULL,
        first_name VARCHAR(20) NOT NULL,
        last_name VARCHAR(20) NOT NULL,
        dob DATE NOT NULL,
        gender VARCHAR(10) NOT NULL,
        street_address VARCHAR(40) NOT NULL,
        suburb VARCHAR(40) NOT NULL,
        state VARCHAR(3) NOT NULL,
        postcode CHAR(4) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(12) NOT NULL,
        skills VARCHAR(255),
        other_skills TEXT,
        status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New'
    )";

    if (!mysqli_query($conn, $create_table)) {
        $db_error = "Unable to create the EOI table.";
    } else {
        $skills_list = implode(", ", $skills); // joins the skills into one bit of text, e.g. "budgeting-tools, financial-analysis"

        // prepared statement so input can't break the SQL
        $insert = "INSERT INTO eoi (job_ref, first_name, last_name, dob, gender, street_address,
                   suburb, state, postcode, email, phone, skills, other_skills)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert);
        if (!$stmt) {
            $db_error = "Unable to save your application.";
        } else {
            // 13 "s" = 13 values, all strings, in the same order as the columns
            mysqli_stmt_bind_param(
                $stmt,
                "sssssssssssss",
                $jobref,
                $firstname,
                $lastname,
                $dob_db,
                $gender,
                $address,
                $suburb,
                $state,
                $postcode,
                $email,
                $phone,
                $skills_list,
                $otherskills
            );

            if (mysqli_stmt_execute($stmt)) {
                $eoi_number = mysqli_insert_id($conn); // the EOI number the database just made for this application
            } else {
                $db_error = "Unable to save your application.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if ($conn) {
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoneyMaster - Application result">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>Application | MoneyMaster</title>
</head>

<body>
    <?php include 'header.inc'; ?>

    <main>
        <?php if ($eoi_number > 0) { ?>
            <h1>Application Received</h1>
            <section>
                <?php /* htmlspecialchars so nothing they typed can run as html on this page */ ?>
                <p> Thank you, <?= htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8') ?>. Your application for job <?= htmlspecialchars($jobref, ENT_QUOTES, 'UTF-8') ?> has been submitted. </p>
                <p>Your Expression of Interest number is <strong><?php echo $eoi_number; ?></strong>. Please keep it for your records.</p>
                <p><a href="index.php">Back to Home</a></p>
            </section>
        <?php } else { ?>
            <h1>Something Went Wrong</h1>
            <section>
                <p><?php echo $db_error; ?> Please try again later.</p>
                <p><a href="apply.php">Back to the application form</a></p>
            </section>
        <?php } ?>
    </main>

    <?php include 'footer.inc'; ?>
</body>
</html>
