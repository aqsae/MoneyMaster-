
<header>
    <nav>
        // logo links back to home page!
        <a href="index.php">
            <img src="../images/logo.png" class="logo" alt="MoneyMaster logo">
        </a>

        // navigation links to the other pages!!
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="jobs.php">Jobs</a></li>
            <li><a href="apply.php">Apply</a></li>
            <li><a href="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>#footer">Contact </a></li>       
       </ul>

    // login button takes users to the home page !!
        <div class="btns">
            <a href="login.php" class="btn">Login</a>
        </div>
    </nav>
</header>
