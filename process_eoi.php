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
?>
