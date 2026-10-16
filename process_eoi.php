?php

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

$jobref      = isset($_POST["jobref"])      ? sanitise_input($_POST["jobref"])      : "";
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

$skills = array();
if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
    foreach ($_POST["skills"] as $skill) {
        $skills[] = sanitise_input($skill);
    }
}

$errors = array();
 
if (!preg_match("/^[A-Za-z0-9]{6}$/", $jobref)) {
    $errors[] = "Job reference number must be exactly 6 letters or numbers.";
}

if ($firstname == "") {
    $errors[] = "First name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $firstname)) {
    $errors[] = "First name must be letters only, max 20 characters.";
}

if ($lastname == "") {
    $errors[] = "Last name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $lastname)) {
    $errors[] = "Last name must be letters only, max 20 characters.";
}

$dob_db = "";
if ($dob == "") {
    $errors[] = "Date of birth is required.";
} else {
    if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $dob, $parts)) {
        $year = $parts[1]; $month = $parts[2]; $day = $parts[3];
    } elseif (preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $dob, $parts)) {
        $day = $parts[1]; $month = $parts[2]; $year = $parts[3];
    } else {
        $year = $month = $day = 0;
    }
 
    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        $errors[] = "Date of birth must be a valid date (dd/mm/yyyy).";
    } else {
        $dob_db = "$year-$month-$day";
        if ($dob_db > date("Y-m-d")) {
            $errors[] = "Date of birth cannot be in the future.";
        } else {
            $age = date_diff(date_create($dob_db), date_create("today"))->y;
            if ($age < 15 || $age > 80) {
                $errors[] = "Applicants must be between 15 and 80 years old.";
            }
        }
    }
}

if (!in_array($gender, array("female", "male", "other"))) {
    $errors[] = "Please select a gender.";
}

if ($address == "") {
    $errors[] = "Street address is required.";
} elseif (strlen($address) > 40) {
    $errors[] = "Street address must be max 40 characters.";
}

if ($suburb == "") {
    $errors[] = "Suburb/town is required.";
} elseif (strlen($suburb) > 40) {
    $errors[] = "Suburb/town must be max 40 characters.";
}
                
$state_postcodes = array(
    "VIC" => array("3", "8"),
    "NSW" => array("1", "2"),
    "QLD" => array("4", "9"),
    "NT"  => array("0"),
    "WA"  => array("6"),
    "SA"  => array("5"),
    "TAS" => array("7"),
    "ACT" => array("0", "2")
);

if (!array_key_exists($state, $state_postcodes)) {
    $errors[] = "Please select a valid state.";
}

if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} elseif (array_key_exists($state, $state_postcodes) && !in_array($postcode[0], $state_postcodes[$state])) {
    $errors[] = "Postcode $postcode does not match the state $state.";
}

if ($email == "") {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email address is not valid.";

if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be 8 to 12 digits.";
}

$valid_skills = array(
    "budgeting-tools",
    "digital-payments",
    "financial-security",
    "financial-analysis",
    "customer-service",
    "software-development"
);
foreach ($skills as $skill) {
    if (!in_array($skill, $valid_skills)) {
        $errors[] = "Invalid skill selected.";
        break;
    }
}

if (count($skills) == 0 && $otherskills == "") {
    $errors[] = "Please select at least one skill or describe your other skills.";
}

$eoi_number = 0;
$db_error = "";

if (count($errors) == 0) {
    require_once "settings.php";
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        $db_error = "Unable to connect to the database.";
    } else {
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
            $skills_list = implode(", ", $skills);

            $insert = "INSERT INTO eoi (job_ref, first_name, last_name, dob, gender, street_address,
                       suburb, state, postcode, email, phone, skills, other_skills)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert);
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
                $eoi_number = mysqli_insert_id($conn);
            } else {
                $db_error = "Unable to save your application.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoneyMaster - Application result">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>MoneyMaster - Application</title>
</head>

<body>
    <?php include 'header.inc'; ?>

    <main>
        <br>
        <?php if ($eoi_number > 0) { ?>
            <h1>Application Received</h1>
            <section>
                <p>Thank you, <?php echo $firstname; ?>. Your application for job <?php echo $jobref; ?> has been submitted.</p>
                <p>Your Expression of Interest number is <strong><?php echo $eoi_number; ?></strong>. Please keep it for your records.</p>
                <p><a href="index.php">Back to Home</a></p>
            </section>
        <?php } elseif ($db_error != "") { ?>
            <h1>Something Went Wrong</h1>
            <section>
                <p><?php echo $db_error; ?> Please try again later.</p>
                <p><a href="apply.php">Back to the application form</a></p>
            </section>
        <?php } else { ?>
            <h1>Please Fix the Following</h1>
            <section>
                <ul>
                    <?php foreach ($errors as $error) { echo "<li>$error</li>"; } ?>
                </ul>
                <p><a href="apply.php">Back to the application form</a></p>
            </section>
        <?php } ?>
    </main>
    <?php include 'footer.inc'; ?>
</body>
</html>
