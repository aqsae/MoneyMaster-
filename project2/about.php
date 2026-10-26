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
    <p>Welcome to our team! We are a group of dedicated students passionate about what we do. </p>

    <ul>
     <li>
        <span>What we are called : </span>  
        <h2 class="team-name">The Moneymasters</h2>
        <ul>
          <li>
            <span>Tutorial Day :</span>
            <strong>Wednesday</strong>
          </li>
         <li>
            <span>Tutorial Time</span>
            <strong>12:30 PM to 2:30 PM</strong>
          </li>
        </ul>
      </li>
   </ul>
  </section>
 
        
 <section class="contributions-section">
    <h1 class="section-title">Member Contributions</h1>

    <dl class="contributions-list">
    
      <div class="contribution-card">
        <dt class="member-header">
         <span class="member-name">Aqsa Shiyana Baanu</span>
          <span class="student-id">ID: 106512925</span>
        </dt>
        <dd class="member-details">
         Developed the Home page, including the logo, styles and navigation bar implemented across all pages. Did header.inc, footer.inc, logout.php, login.php, debugging and presentation.
       </dd>
      </div>

     <div class="contribution-card">
       <dt class="member-header">
          <span class="member-name">Sukhmanpreet Kaur</span>
         <span class="student-id">ID: 106390891</span>
        </dt>
       <dd class="member-details">
         Developed the About page and eoi_options.php as well as proofread and edited side and presentation slides.
        </dd>
      </div>

     <div class="contribution-card">
        <dt class="member-header">
         <span class="member-name">Noah Puglia</span>
          <span class="student-id">ID: 105925250</span>
        </dt>
       <dd class="member-details">
         Developed the Jobs page, moneymaster_db.sql, db.php settings.php, functions.php as well as tested out the website and fixed bugs. He was behind the creation of the SQL database and added a search function.
        </dd>
     </div>

      <div class="contribution-card">
       <dt class="member-header">
          <span class="member-name">Enricko Hartanto</span>
          <span class="student-id">ID: 106705152</span>
        </dt>
        <dd class="member-details">
        Developed the Apply form, organised files and created process_eoi, manage.php and the Expression of Interest table for the database, also debugged and fixed errors and added a search bar to the Apply page.

        </dd>
     </div> 
    </dl>
  </section>
        
  <section>
    <h1>What our team has to say</h1>
    <h1 class="name">Aqsa Shiyana Baanu</h1>
    <blockquote>
      <p>"என்ன செய்வது என்று தெரியாத பொழுது என்ன செய்கிறாயோ அதுதான் உன் வெற்றிக்கு வழிவகுக்கும்"</p>
     <footer>
        <span>English:</span> "When you don't know what to do, what you do will lead to your success."
     </footer>
    </blockquote>

    <h1 class="name">Sukhmanpreet Kaur</h1>
    <blockquote>
      <p>"ਮਨੁ ਜੀਤੈ ਜਗੁ ਜੀਤੁ"</p>
      <footer>
       <span>English:</span> "One who conquers the mind conquers the world."
     </footer>
   </blockquote>
        
    <h1 class="name">Noah Puglia</h1>
    <blockquote>
      <p>"Si votre nom ne figure pas dans le chapeau, comment pouvez-vous espérer être tiré au sort ? C'est impossible." </p>
      <footer>
          <span>English:</span> "If your name is not in the hat, how can you hope to be drawn? It's impossible."
        </footer>
   </blockquote>

    <h1 class="name">Enricko Hartanto</h1>
    <blockquote>
     <p>"Gantungkan cita-citamu setinggi langit! Bermimpilah setinggi langit. Jika engkau jatuh, engkau akan jatuh di antara bintang-bintang."</p>
      <footer>
       <span>English:</span> "Hang your dreams as high as the sky! Dream as high as the sky. If you fall, you'll fall among the stars."
      </footer>
   </blockquote>
  </section>
        
<figure>
    <img src="images/group.png" alt="Our team members">
    <figcaption>Our Crew Members</figcaption>
</figure>

    
  <table>
    <caption style="font-size: 18px; font-weight: bold; color: #0f4c5c; background-color: #d1e7cc; padding: 8px 16px; border-radius: 6px; margin-bottom: 15px;
     letter-spacing: 1px;"> Fun Facts about team members</caption> <!--Inline style css -->
    <tr>
      <th>Name</th>
      <th>Favorite App/Technology to use</th>
      <th>Favorite Book</th>
      <th>Hobby</th>
      <th>Dream job</th>
      <th>Comments about the team</th>
    </tr>

   <tr>
      <td>Aqsa Shiyana Baanu</td>
      <td>Pinterest</td>
      <td>Giovanni's Room</td>
      <td>Drawing</td>
      <td>Embedded Systems Engineer</td>
      <td>Everyone is very kind and respectful.</td>
   </tr>

    <tr>
      <td>Sukhmanpreet Kaur</td>
     <td>Youtube</td>
     <td>Can't Hurt Me</td>
     <td>Singing</td>
      <td>AI Engineer</td>
      <td>Everyone is very helpful and supportive.</td>
    </tr>

    <tr>
      <td>Noah Puglia</td>
      <td>PlayStation 3</td>
      <td>Clear and Present Danger</td>
      <td>Playing drums</td>
      <td>Cybersecurity Architect</td>
      <td>We communicate well.</td>
    </tr>

    <tr>
      <td>Enricko Hartanto</td>
      <td>Tiktok</td>
      <td>The Setting Sun</td>
      <td>Swimming</td>
      <td>Data Analyst</td>
      <td>Everyone is very easy to talk to.</td>
    </tr>
  </table>
  </main> 

  <?php include __DIR__ . '/footer.inc'; ?>
</body>
</html>
