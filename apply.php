<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="MoneyMaster - A financial technology company developing secure and user-friendly platforms for digital payments, budgeting tools, account management and financial education.">
    <meta name="keywords"
        content="MoneyMaster, financial technology, digital payments, budgeting, account management, financial education">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>MoneyMaster</title>
    <style> /* styles that are just for the application page */ 
         .logo { width: 260px; height: auto; } /* making the form look like a proper application box */
          main > section { background-color: var(--white); 
          padding: 2rem; border-radius: var(--radius); 
          border-top: 3px solid var(--mid-green); 
          box-shadow: 0 10px 30px rgba(0, 53, 53, 0.08); } /* little intro text above the form */ 
          #form-intro { font-style: italic; color: var(--mid-green); } /* separating the different parts of the form */ 
          form h2 { margin-top: 2rem; 
          padding-bottom: 0.5rem; 
          border-bottom: 2px solid rgba(72, 139, 60, 0.2);
        color: var(--dark-green); } form h2:first-of-type { margin-top: 0; 
        } 
        </style>
    <!--<img src="logo.png" class="logo"> -->
</head>

    <body>
        <?php include 'header.inc'; ?>  

        <main>
            <br>
            <h1>Apply for a Job Here</h1>
            <p id="form-intro" style="font-style: italic; color: #4a7a5a;">Apply below to join our team.
            </p>

            <section>
                <form action="process_eoi.php" method="POST">

                    <h2>Job Details</h2>

                    <p>
                        <label for="jobref">Job reference number (6 alphanumeric characters):</label><br>
                        <input type="text" id="jobref" name="jobref" pattern="[A-Za-z0-9]{6}" maxlength="6" minlength="6"
                            required>
                    </p>
                    <h2>Personal Details</h2>

                    <p>
                        <label for="firstname">First name (max 20 letters):</label><br>
                        <input type="text" id="firstname" name="firstname" pattern="[A-Za-z]{1,20}" maxlength="20" required>
                    </p>
                    <p>
                        <label for="lastname">Last name (max 20 letters):</label><br>
                        <input type="text" id="lastname" name="lastname" pattern="[A-Za-z]{1,20}" maxlength="20" required>
                    </p>
                    <p>
                        <label for="dob">Date of birth (dd/mm/yyyy):</label><br>
                        <input type="date" id="dob" name="dob" required>
                    </p>
                    <fieldset>
                        <legend>Gender</legend>
                        <p>
                            <input type="radio" id="genderfemale" name="gender" value="female" required>
                            <label for="genderfemale">Female</label>
                        </p>
                        <p>
                            <input type="radio" id="gendermale" name="gender" value="male" required>
                            <label for="gendermale">Male</label>
                        </p>
                        <p>
                            <input type="radio" id="genderother" name="gender" value="other" required>
                            <label for="genderother">Other</label>
                        </p>


                    </fieldset>

                    <h2>Contact Details</h2>

                    <p>
                        <label for="address">Street address (max 40 characters):</label><br>
                        <input type="text" id="address" name="address" maxlength="40" required>
                    </p>
                    <p>
                        <label for="suburb">Suburb/Town (max 40 characters):</label><br>
                        <input type="text" id="suburb" name="suburb" maxlength="40" required>
                    </p>

                    <p>
                        <label for="state">State:</label><br>
                        <select id="state" name="state" required>
                            <option value="" disabled selected>Select a state</option>
                            <option value="VIC">VIC</option>
                            <option value="NSW">NSW</option>
                            <option value="QLD">QLD</option>
                            <option value="NT">NT</option>
                            <option value="WA">WA</option>
                            <option value="SA">SA</option>
                            <option value="TAS">TAS</option>
                            <option value="ACT">ACT</option>
                        </select>
                    </p>

                    <p>
                        <label for="postcode">Postcode (4 digits):</label><br>
                        <input type="text" id="postcode" name="postcode" pattern="[0-9]{4}" maxlength="4" minlength="4"
                            required>
                    </p>

                    <p>
                        <label for="email">Email address:</label><br>
                        <input type="email" id="email" name="email" required>
                    </p>
                    <p>
                        <label for="phone">Phone number (8-12 digits):</label><br>
                        <input type="text" id="phone" name="phone" pattern="[0-9]{8,12}" maxlength="12" inputmode="numeric"
                            required>
                    </p>

                    <h2>Skills</h2>

                    <fieldset>
                        <legend>Skill list</legend>
                        <p>
                            <input type="checkbox" id="skillbudgeting" name="skills[]" value="budgeting-tools">
                            <label for="skillbudgeting">Budgeting tools development</label>
                        </p>
                        <p>
                            <input type="checkbox" id="skillpayments" name="skills[]" value="digital-payments">
                            <label for="skillpayments">Digital payments systems</label>
                        </p>
                        <p>
                            <input type="checkbox" id="skillsecurity" name="skills[]" value="financial-security">
                            <label for="skillsecurity">Financial data security</label>
                        </p>
                        <p>
                            <input type="checkbox" id="skillanalysis" name="skills[]" value="financial-analysis">
                            <label for="skillanalysis">Financial analysis</label>
                        </p>
                        <p>
                            <input type="checkbox" id="skillcustomer" name="skills[]" value="customer-service">
                            <label for="skillcustomer">Customer service</label>
                        </p>
                        <p>
                            <input type="checkbox" id="skillcoding" name="skills[]" value="software-development">
                            <label for="skillcoding">Software development</label>
                        </p>
                    </fieldset>

                    <h3>Other skills:</h3>
                    <p>
                        <label for="otherskills">Please describe any other skills:</label>
                        <br>
                        <textarea id="otherskills" name="otherskills" rows="5" cols="40"></textarea>
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
