<?php
session_start();
require_once "eoi_options.php";

$errors = isset($_SESSION["eoi_errors"]) ? $_SESSION["eoi_errors"] : array();
$old = isset($_SESSION["eoi_old"]) ? $_SESSION["eoi_old"] : array();
unset($_SESSION["eoi_errors"], $_SESSION["eoi_old"]);

function old_value($old, $field) {
    $value = isset($old[$field]) ? $old[$field] : "";
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8", false);
}

function show_error($errors, $field)
{
    if (isset($errors[$field])) {
        echo '<span class="field-error">'
            . htmlspecialchars((string)$errors[$field], ENT_QUOTES, 'UTF-8')
            . '</span>';
    }
}

if (isset($old["jobref"])) {
    $selected_ref = $old["jobref"];
} elseif (isset($_GET["ref"])) {
    $selected_ref = strtoupper(trim($_GET["ref"]));
} else {
    $selected_ref = "";
}

$old_skills = isset($old["skills"]) ? $old["skills"] : array();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="MoneyMaster - A financial technology company developing secure and user-friendly platforms for digital payments, budgeting tools, account management and financial education.">
    <meta name="keywords"
        content="MoneyMaster, financial technology, digital payments, budgeting, account management, financial education">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>Apply | MoneyMaster</title>
    <style> /* styles that are just for the application page */
        .logo { width: 260px; height: auto; }
        /* making the form look like a proper application box */
        main > section { background-color: var(--white);
            padding: 2rem; border-radius: var(--radius);
            border-top: 3px solid var(--mid-green);
            box-shadow: 0 10px 30px rgba(0, 53, 53, 0.08); }
        /* little intro text above the form */
        #form-intro { font-style: italic; color: var(--mid-green); }
        /* separating the different parts of the form */
        form h2 { margin-top: 2rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(72, 139, 60, 0.2);
            color: var(--dark-green); }
        form h2:first-of-type { margin-top: 0; }
        .form-errors { padding: 1rem 1.5rem; margin-bottom: 1.5rem;
            border-left: 5px solid #b3261e; border-radius: 8px;
            background-color: #fdecea; color: #7a1c15; }
        .field-error { display: block; margin-top: 0.3rem;
            color: #b3261e; font-size: 0.9rem; font-weight: bold; }
    </style>
</head>

<body>
    <?php include 'header.inc'; ?>

    <main>
        <h1>Apply for a Job Here</h1>
        <p id="form-intro">Apply below to join our team.</p>

        <section>
            <form action="process_eoi.php" method="POST">

                <?php if (count($errors) > 0) { ?>
                    <div class="form-errors">
                        <p><strong>Please fix the following:</strong></p>
                        <ul>
                            <?php foreach ($errors as $error) { ?>
                                <li><?php echo $error; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <h2>Job Details</h2>

                <p>
                    <label for="jobref">Job reference number (6 alphanumeric characters):</label>
                    <input type="text" id="jobref" name="jobref" pattern="[A-Za-z0-9]{6}" maxlength="6" minlength="6"
                        title="Exactly 6 letters or numbers"
                        value="<?php echo htmlspecialchars($selected_ref, ENT_QUOTES, "UTF-8", false); ?>" required>
                    <?php show_error($errors, "jobref"); ?>
                </p>

                <h2>Personal Details</h2>

                <p>
                    <label for="firstname">First name (max 20 letters, no spaces or symbols):</label>
                    <input type="text" id="firstname" name="firstname" pattern="[A-Za-z]{1,20}" title="Letters only, up to 20, with no spaces, numbers or symbols" maxlength="20"
                        value="<?php echo old_value($old, "firstname"); ?>" required>
                    <?php show_error($errors, "firstname"); ?>
                </p>
                <p>
                    <label for="lastname">Last name (max 20 letters, no spaces or symbols):</label>
                    <input type="text" id="lastname" name="lastname" pattern="[A-Za-z]{1,20}" title="Letters only, up to 20, with no spaces, numbers or symbols" maxlength="20"
                        value="<?php echo old_value($old, "lastname"); ?>" required>
                    <?php show_error($errors, "lastname"); ?>
                </p>
                <p>
                    <label for="dob">Date of birth (dd/mm/yyyy):</label>
                    <input type="date" id="dob" name="dob" value="<?php echo old_value($old, "dob"); ?>" required>
                    <?php show_error($errors, "dob"); ?>
                </p>

                <fieldset>
                    <legend>Gender</legend>
                    <p>
                        <input type="radio" id="genderfemale" name="gender" value="female" <?php if (old_value($old, "gender") == "female") echo "checked"; ?> required>
                        <label for="genderfemale">Female</label>
                    </p>
                    <p>
                        <input type="radio" id="gendermale" name="gender" value="male" <?php if (old_value($old, "gender") == "male") echo "checked"; ?> required>
                        <label for="gendermale">Male</label>
                    </p>
                    <p>
                        <input type="radio" id="genderother" name="gender" value="other" <?php if (old_value($old, "gender") == "other") echo "checked"; ?> required>
                        <label for="genderother">Other</label>
                    </p>
                    <?php show_error($errors, "gender"); ?>
                </fieldset>

                <h2>Contact Details</h2>

                <p>
                    <label for="address">Street address (max 40 characters):</label>
                    <input type="text" id="address" name="address" maxlength="40"
                        value="<?php echo old_value($old, "address"); ?>" required>
                    <?php show_error($errors, "address"); ?>
                </p>
                <p>
                    <label for="suburb">Suburb/Town (max 40 characters):</label>
                    <input type="text" id="suburb" name="suburb" maxlength="40"
                        value="<?php echo old_value($old, "suburb"); ?>" required>
                    <?php show_error($errors, "suburb"); ?>
                </p>

                <p>
                    <label for="state">State:</label>
                    <select id="state" name="state" required>
                        <option value="" disabled <?php if (old_value($old, "state") == "") echo "selected"; ?>>Select a state</option>
                        <?php foreach (array_keys($state_postcodes) as $state_code) { ?>
                            <option value="<?php echo $state_code; ?>" <?php if (old_value($old, "state") == $state_code) echo "selected"; ?>><?php echo $state_code; ?></option>
                        <?php } ?>
                    </select>
                    <?php show_error($errors, "state"); ?>
                </p>

                <p>
                    <label for="postcode">Postcode (4 digits):</label>
                    <input type="text" id="postcode" name="postcode" pattern="[0-9]{4}" title="Exactly 4 digits" maxlength="4" minlength="4"
                        value="<?php echo old_value($old, "postcode"); ?>" required>
                    <?php show_error($errors, "postcode"); ?>
                </p>

                <p>
                    <label for="email">Email address:</label>
                    <input type="email" id="email" name="email" value="<?php echo old_value($old, "email"); ?>" required>
                    <?php show_error($errors, "email"); ?>
                </p>
                <p>
                    <label for="phone">Phone number (8-12 digits, no spaces):</label>
                    <input type="text" id="phone" name="phone" pattern="[0-9]{8,12}" title="8 to 12 digits, with no spaces" maxlength="12" inputmode="numeric"
                        value="<?php echo old_value($old, "phone"); ?>" required>
                    <?php show_error($errors, "phone"); ?>
                </p>

                <h2>Skills</h2>

                <fieldset>
                    <legend>Skill list</legend>
                    <?php foreach ($skill_options as $skill_value => $skill_label) { ?>
                        <p>
                            <input type="checkbox" id="skill-<?php echo $skill_value; ?>" name="skills[]" value="<?php echo $skill_value; ?>"
                                <?php if (in_array($skill_value, $old_skills)) echo "checked"; ?>>
                            <label for="skill-<?php echo $skill_value; ?>"><?php echo $skill_label; ?></label>
                        </p>
                    <?php } ?>
                    <?php show_error($errors, "skills"); ?>
                </fieldset>

                <h3>Other skills:</h3>
                <p>
                    <label for="otherskills">Please describe any other skills:</label>
                    <textarea id="otherskills" name="otherskills" rows="5" cols="40"><?php echo old_value($old, "otherskills"); ?></textarea>
                </p>

                <p>
                    <button type="submit">Submit Application</button>
                    <button type="reset">Clear Form</button>
                </p>

            </form>
        </section>
    </main>

    <?php include 'footer.inc'; ?>
</body>
</html>
