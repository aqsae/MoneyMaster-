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
?>
