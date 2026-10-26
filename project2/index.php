<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../styles/styles.css?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="MoneyMaster - A financial technology company developing secure and user-friendly platforms for digital payments, budgeting tools, account management and financial education.">
    <meta name="keywords"
        content="MoneyMaster, financial technology, digital payments, budgeting, account management, financial education">
    <meta name="author" content="MoneyMaster Executive Team">
    <title>MoneyMaster</title>
   <style> /* this is just for the home page */
    .logo { width: 260px; height: auto; } /* making the home page sections go full width */ 
     main { max-width: 100%; padding: 0; margin: 0; } /* keeping the big home page image behind the heading */
    .image-container { margin-top: 90px; } </style>
    <!--<img src="../images/logo.png" class="logo"> -->
</head>

<body>
<?php include __DIR__ . '/header.inc'; ?>

    <div class="image-container">
        <img src="../images/thumbnail.jpg" alt="Moneymaster Preview" class="thumbnail">
        <div class="overlay-heading">we've got you.</div>

<a href="login.php" class="start">Get Started</a>
    </div>
    <div class="description">
        <SECTION id="description-text-block">
            <h2>What we can do for you</h2>
            <p>Welcome to MoneyMaster, a leading financial technology company dedicated to providing
                secure and user-friendly platforms for digital payments, budgeting tools, account management,
                and financial education.
                Our mission is to allow you to take complete control of your financial destiny with Moneymaster.</p>
        </SECTION>
        <div class="circle1">
            <img src="../images/icon1.png" alt="Banking Preview" class="icons" id="icon1">
        </div>
    </div>



    <main>

        <div class="mission-banner">
            <section id="mission-text-block">
                <h2>our mission</h2>
                <p>We believe that financial freedom shouldn't be a privilege. MoneyMaster was founded with a clear
                    vision to strip the anxiety, remove hidden banking fees, and replace complex jargon with concise and
                    easily understandable tools.</p>
                <p>By fusing high-security encryption with user-friendly management, we give you the tools to track
                    every dollar, build sustainable budgets, and scale your personal savings securely.</p>
            </section>
            <div class="circle2">
                <img src="../images/icon2.png" alt="Financial Freedom Preview" class="icons" id="icon2">
            </div>

        </div>


        <section class="features-section">
            <h2 class="section-title">Smart Tools to Master Your Money</h2>

            <div class="features-container">
                <div class="feature-card">
                    <h3>Automated Sorting</h3>
                    <p>See exactly where your money goes. MoneyMaster automatically sorts your transactions into food,
                        shopping, bills, and leisure in real time.</p>
                </div>

                <div class="feature-card">
                    <h3>Smart Spending Caps</h3>
                    <p>Set custom monthly limits based on your budgeting plan. Receive instant mobile alerts before you
                        overspend so you always stay on track.</p>
                </div>

                <div class="feature-card">
                    <h3>Predictive Insights</h3>
                    <p>Our smart algorithm analyzes your recurring spending habits over time, identifying improvements
                        to help you optimize your monthly savings.</p>
                </div>
            </div>
        </section>

        <!-- education stuff blah blah -->
        <section class="education-section">
            <div class="education-content">
                <h2 class="section-title">Financial Education</h2>
                <p class="section-subtitle">Financial literacy is the true secret to building lasting wealth. Access
                    short and interactive modules designed to help you master your money.</p>

                <div class="education-grid">
                    <div class="education-card">
                        <span class="badge">1</span>
                        <h3>Teaching you investing</h3>
                        <p>Learn how the stock market works, about compound interest and index funds in under 10
                            minutes.</p>
                    </div>

                    <div class="education-card">
                        <span class="badge">2</span>
                        <h3>Mastering Credit Scores</h3>
                        <p>Learn the exact formulas used to calculate your credit scores and how you can strategically
                            optimize it.</p>
                    </div>

                    <div class="education-card">
                        <span class="badge">3</span>
                        <h3>Debt Elimination strategies</h3>
                        <p>Compare smart, accomodating methods to confidently wipe out your debts faster.</p>
                    </div>
                </div>
            </div>
        </section>


        <div class="table-container">
            <table class="pricing-table">
                <thead>
                    <tr>
                        <th>Transaction Type</th>
                        <th class="highlight-column">MoneyMaster</th>
                        <th>Traditional Competitors</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Transaction Type" class="feature-name">Domestic P2P Transfer</td>
                        <td data-label="Our Platform" class="highlight-cell">Instant & Free</td>
                        <td data-label="Traditional Competitors">Up to 2% or 2-day delay</td>
                    </tr>
                    <tr>
                        <td data-label="Transaction Type" class="feature-name">Standard Bank Cashout</td>
                        <td data-label="Our Platform" class="highlight-cell">Free (Next Day)</td>
                        <td data-label="Traditional Competitors">1–3 Business Days</td>
                    </tr>
                    <tr>
                        <td data-label="Transaction Type" class="feature-name">Instant Debit Transfer</td>
                        <td data-label="Our Platform" class="highlight-cell">0.5% (Capped at $5)</td>
                        <td data-label="Traditional Competitors">1.5% to 2% (No cap)</td>
                    </tr>
                    <tr>
                        <td data-label="Transaction Type" class="feature-name">International FX Rate</td>
                        <td data-label="Our Platform" class="highlight-cell">Mid-Market + 1%</td>
                        <td data-label="Traditional Competitors">3.5% to 4.5% markup</td>
                    </tr>
                    <tr>
                        <td data-label="Transaction Type" class="feature-name">Credit Card Funding</td>
                        <td data-label="Our Platform" class="highlight-cell">2.5% Flat</td>
                        <td data-label="Traditional Competitors">2.9% + $0.30 fixed fee</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

<?php include __DIR__ . '/footer.inc'; ?>
</body>

</html>
