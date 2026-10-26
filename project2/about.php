<?php
require_once __DIR__ . '/db.php';

$teamResult = mysqli_query(
    $conn,
    "SELECT * FROM about_team LIMIT 1"
);
$team = mysqli_fetch_assoc($teamResult);

$membersResult = mysqli_query(
    $conn,
    "SELECT * FROM about_members ORDER BY member_id"
);

$quotesResult = mysqli_query(
    $conn,
    "SELECT name, quote FROM about_members ORDER BY member_id"
);

$factsResult = mysqli_query(
    $conn,
    "SELECT * FROM about_member_facts ORDER BY fact_id"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" >
  <meta name="description" content="Demonstrates about the team of the company" >
  <meta name="keywords" content="HTML5, CSS layout" >
  <meta name="author" content="Sukh_man"  >
  <title>About our team</title>
 <link href="styles/styles.css?v=2" rel="stylesheet">   
	<style> /* small styles just for the about page */ 
    .logo { width: 260px; height: auto; } /* making the team information easier to read */
   .about-page > section:first-child { padding-top: 1rem; } /* this is for the footer text on this page */
   .footer-text { font-size: 14px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; } 
    </style>

</head>

<body>
            
<?php include __DIR__ . '/header.inc'; ?>

<main class="about-page">

	<section>
    <h1>Our Team</h1>

    <p><?= htmlspecialchars($team['intro']) ?></p>

    <h2 class="team-name">
        <?= htmlspecialchars($team['team_name']) ?>
    </h2>

    <ul>
        <li>
            <span>Tutorial Day:</span>
            <strong><?= htmlspecialchars($team['class_day']) ?></strong>
        </li>

        <li>
            <span>Tutorial Time:</span>
            <strong><?= htmlspecialchars($team['class_time']) ?></strong>
        </li>

        <li>
            <span>Tutor:</span>
            <strong><?= htmlspecialchars($team['tutor']) ?></strong>
        </li>
    </ul>
</section>
 
    
<section class="contributions-section">
    <h1 class="section-title">Member Contributions</h1>

    <dl class="contributions-list">
        <?php while ($member = mysqli_fetch_assoc($membersResult)): ?>
            <div class="contribution-card">
                <dt class="member-header">
                    <span class="member-name">
                        <?= htmlspecialchars($member['name']) ?>
                    </span>

                    <span class="student-id">
                        ID: <?= htmlspecialchars($member['student_id']) ?>
                    </span>
                </dt>

                <dd class="member-details">
                    <strong>Part 1:</strong>
                    <?= nl2br(htmlspecialchars($member['part1_work'])) ?>

                    <br><br>

                    <strong>Part 2:</strong>
                    <?= nl2br(htmlspecialchars($member['part2_work'])) ?>
                </dd>
            </div>
        <?php endwhile; ?>
    </dl>
</section>

 <section>
    <h1>What our team has to say</h1>

    <?php while ($person = mysqli_fetch_assoc($quotesResult)): ?>
        <h2 class="name">
            <?= htmlspecialchars($person['name']) ?>
        </h2>

        <blockquote>
            <p>
                <?= htmlspecialchars($person['quote']) ?>
            </p>
        </blockquote>
    <?php endwhile; ?>
</section>
        
<figure>
    <img src="images/group.png" alt="Our team members">
    <figcaption>Our Crew Members</figcaption>
</figure>

    

<table>
    <caption>Fun Facts about team members</caption>

    <thead>
        <tr>
            <th>Name</th>
            <th>Favorite App/Technology to use</th>
            <th>Favorite Book</th>
            <th>Hobby</th>
            <th>Dream job</th>
            <th>Comments about the team</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($fact = mysqli_fetch_assoc($factsResult)): ?>
            <tr>
                <td><?= htmlspecialchars($fact['member_name']) ?></td>
                <td><?= htmlspecialchars($fact['favorite_app']) ?></td>
                <td><?= htmlspecialchars($fact['favorite_book']) ?></td>
                <td><?= htmlspecialchars($fact['hobby']) ?></td>
                <td><?= htmlspecialchars($fact['dream_job']) ?></td>
                <td><?= htmlspecialchars($fact['team_comment']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

  </main> 

  <?php include __DIR__ . '/footer.inc'; ?>
</body>
</html>
