-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2026 at 01:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moneymaster_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_members`
--

CREATE TABLE `about_members` (
  `member_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `student_id` varchar(12) NOT NULL,
  `part1_work` text NOT NULL,
  `part2_work` text NOT NULL,
  `quote` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_members`
--

INSERT INTO `about_members` (`member_id`, `name`, `student_id`, `part1_work`, `part2_work`, `quote`) VALUES
(1, 'Aqsa Shiyana Baanu', '106512925', 'Developed the Home page, logo, styles and navigation bar implemented across all pages.', 'Worked on header.inc, footer.inc, logout.php, login.php, debugging, updating database and the presentation.', 'என்ன செய்வது என்று தெரியாத பொழுது என்ன செய்கிறாயோ அதுதான் உன் வெற்றிக்கு வழிவகுக்கும்'),
(2, 'Sukhmanpreet Kaur', '106390891', 'Developed the About page.', 'Worked on eoi_options.php, proofreading and presentation slides.', 'ਮਨੁ ਜੀਤੈ ਜਗੁ ਜੀਤੁ'),
(3, 'Noah Puglia', '105925250', 'Developed the Jobs page and database setup.', 'Worked on db.php, settings.php, functions.php, testing, bug fixes, updating database and the search function.', 'Si votre nom ne figure pas dans le chapeau, comment pouvez-vous espérer être tiré au sort ? C\'est impossible.'),
(4, 'Enricko Hartanto', '106705152', 'Built the job application page and its form validation.', 'Built process_eoi, manage.php and the EOI database functionality and helped integrate the database.', 'Gantungkan cita-citamu setinggi langit! Bermimpilah setinggi langit. Jika engkau jatuh, engkau akan jatuh di antara bintang-bintang.');

-- --------------------------------------------------------

--
-- Table structure for table `about_member_facts`
--

CREATE TABLE `about_member_facts` (
  `fact_id` int(11) NOT NULL,
  `member_name` varchar(100) NOT NULL,
  `favorite_app` varchar(100) NOT NULL,
  `favorite_book` varchar(100) NOT NULL,
  `hobby` varchar(100) NOT NULL,
  `dream_job` varchar(100) NOT NULL,
  `team_comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_member_facts`
--

INSERT INTO `about_member_facts` (`fact_id`, `member_name`, `favorite_app`, `favorite_book`, `hobby`, `dream_job`, `team_comment`) VALUES
(1, 'Aqsa Shiyana Baanu', 'Pinterest', 'Giovanni\'s Room', 'Drawing', 'Embedded Systems Engineer', 'Everyone is very kind and respectful.'),
(2, 'Sukhmanpreet Kaur', 'YouTube', 'Can\'t Hurt Me', 'Singing', 'AI Engineer', 'Everyone is very helpful and supportive.'),
(3, 'Noah Puglia', 'PlayStation 3', 'Clear and Present Danger', 'Playing drums', 'Cybersecurity Architect', 'We communicate well.'),
(4, 'Enricko Hartanto', 'TikTok', 'The Setting Sun', 'Swimming', 'Data Analyst', 'Everyone is very easy to talk to.');

-- --------------------------------------------------------

--
-- Table structure for table `about_team`
--

CREATE TABLE `about_team` (
  `id` int(11) NOT NULL,
  `team_name` varchar(50) NOT NULL,
  `intro` text NOT NULL,
  `class_day` varchar(30) NOT NULL,
  `class_time` varchar(30) NOT NULL,
  `tutor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_team`
--

INSERT INTO `about_team` (`id`, `team_name`, `intro`, `class_day`, `class_time`, `tutor`) VALUES
(1, 'The Moneymasters', 'Welcome to our team! We are a group of dedicated students passionate about what we do.', 'Wednesday', '12:30 PM to 2:30 PM', 'Feixue Yan');

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `job_ref` varchar(6) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street_address` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` char(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `job_ref`, `first_name`, `last_name`, `dob`, `gender`, `street_address`, `suburb`, `state`, `postcode`, `email`, `phone`, `skills`, `other_skills`, `status`) VALUES
(1, 'ENG4A2', 'Alice', 'Nguyen', '1998-03-14', 'female', '12 Glenferrie Rd', 'Hawthorn', 'VIC', '3122', 'alice.nguyen@example.com', '0400111222', 'software-development', '', 'New'),
(2, 'ENG4A2', 'Ben', 'Smith', '1995-07-02', 'male', '8 George St', 'Sydney', 'NSW', '2000', 'ben.smith@example.com', '0400222333', 'software-development, digital-payments', '', 'Current'),
(3, 'FCA8B1', 'Chloe', 'Smith', '2001-11-20', 'female', '45 Queen St', 'Brisbane', 'QLD', '4000', 'chloe.smith@example.com', '0400333444', 'financial-security, financial-analysis', '', 'New'),
(4, 'FCA8B1', 'Priya', 'Patel', '2004-02-20', 'female', '10 Swanston St', 'Melbourne', 'VIC', '3000', 'priya.patel@example.com', '0400999888', 'financial-analysis', '', 'Final'),
(5, 'PMX7A3', 'Daniel', 'Lee', '1990-01-30', 'male', '3 Hay St', 'Perth', 'WA', '6000', 'daniel.lee@example.com', '0400444555', '', 'Product roadmaps and stakeholder management', 'Final'),
(6, 'PMX7A3', 'Emma', 'Brown', '1999-05-09', 'other', '27 Rundle Mall', 'Adelaide', 'SA', '5000', 'emma.brown@example.com', '0400555666', 'budgeting-tools, customer-service', '', 'New'),
(7, 'PMX7A3', 'NULL', 'NULL', '2000-02-02', 'male', 'NULL', 'NULL', 'NSW', '2200', 'n@a.s', '00000000', 'customer-service', '', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `ref` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `salary_min` int(10) UNSIGNED NOT NULL,
  `salary_max` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `ref`, `title`, `salary_min`, `salary_max`, `description`, `created_at`) VALUES
(1, 'ENG4A2', 'Senior Software Engineer', 105000, 135000, 'In this role you will design, build, and maintain scalable software systems that power MoneyMaster\'s core financial platform, collaborating with cross-functional teams to deliver high-quality solutions in a fast-paced fintech environment.', '2026-10-26 11:09:55'),
(2, 'FCA8B1', 'Financial Crime Analyst', 110000, 150000, 'In this position you will work with the cyber security team to identify and analyze potential financial crimes while ensuring compliance with anti-money laundering (AML) and counter-terrorism financing (CTF) laws', '2026-10-26 11:09:55'),
(3, 'PMX7A3', 'Product Manager - Lending', 90000, 120000, 'In this role you will own the product strategy and roadmap for MoneyMaster\'s lending products, working closely with engineering, design, and risk teams to deliver seamless borrowing experiences for our customers.', '2026-10-26 11:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `job_items`
--

CREATE TABLE `job_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `type` enum('responsibility','essential','preferable') NOT NULL,
  `content` varchar(255) NOT NULL,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_items`
--

INSERT INTO `job_items` (`id`, `job_id`, `type`, `content`, `sort_order`) VALUES
(1, 1, 'responsibility', 'Design and develop robust, scalable back-end services and APIs.', 1),
(2, 1, 'responsibility', 'Collaborate with product managers and designers to deliver new features.', 2),
(3, 1, 'responsibility', 'Conduct code reviews and mentor junior engineers.', 3),
(4, 1, 'responsibility', 'Identify and resolve performance bottlenecks and technical debt.', 4),
(5, 1, 'responsibility', 'Contribute to architectural decisions and technical roadmaps.', 5),
(6, 1, 'essential', '5+ years of professional software development experience.', 1),
(7, 1, 'essential', 'Proficiency in one or more languages such as Python, Java, or Go.', 2),
(8, 1, 'essential', 'Strong understanding of system design, data structures, and algorithms.', 3),
(9, 1, 'essential', 'Experience working with relational and non-relational databases.', 4),
(10, 1, 'preferable', 'Experience in the fintech or financial services industry.', 1),
(11, 1, 'preferable', 'Familiarity with cloud platforms such as AWS, GCP, or Azure.', 2),
(12, 1, 'preferable', 'Knowledge of CI/CD pipelines and DevOps practices.', 3),
(13, 2, 'responsibility', 'Monitor transaction queues for unusual or high-risk patterns.', 1),
(14, 2, 'responsibility', 'Conduct Know Your Customer (KYC) and Know Your Business (KYB) checks.', 2),
(15, 2, 'responsibility', 'Analyze and investigate potential financial crimes.', 3),
(16, 2, 'responsibility', 'Investigate internal fraud, scams, and cyber threats.', 4),
(17, 2, 'essential', 'Strong analytical and data interpretation abilities.', 1),
(18, 2, 'essential', 'Experience with financial crime detection tools and techniques.', 2),
(19, 2, 'essential', 'Problem-solving and clear communication skills.', 3),
(20, 2, 'essential', 'Relevant study in criminology, accounting, or risk management.', 4),
(21, 2, 'preferable', '1-3 years working in risk management, banking operations, audit, or regulatory compliance.', 1),
(22, 2, 'preferable', 'Experience with financial crime detection software and tools.', 2),
(23, 2, 'preferable', 'Knowledge of financial regulations and compliance standards.', 3),
(24, 3, 'responsibility', 'Define and prioritize the product roadmap for lending features and services.', 1),
(25, 3, 'responsibility', 'Gather and translate customer insights into clear product requirements.', 2),
(26, 3, 'responsibility', 'Collaborate with engineering, design, and compliance teams through the full product lifecycle.', 3),
(27, 3, 'responsibility', 'Monitor key product metrics and use data to drive continuous improvement.', 4),
(28, 3, 'responsibility', 'Engage with stakeholders to align product direction with business goals.', 5),
(29, 3, 'essential', 'Proven experience as a Product Manager, ideally in financial services or fintech.', 1),
(30, 3, 'essential', 'Strong understanding of lending products such as personal loans, BNPL, or credit.', 2),
(31, 3, 'essential', 'Excellent communication and stakeholder management skills.', 3),
(32, 3, 'essential', 'Ability to use data and research to inform product decisions.', 4),
(33, 3, 'preferable', 'Experience working in an agile development environment.', 1),
(34, 3, 'preferable', 'Familiarity with credit risk, regulatory compliance, or responsible lending obligations.', 2),
(35, 3, 'preferable', 'Background in UX research or customer discovery methods.', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$SSCjxKH8vEZI6MVgFmTfyOE6/.NcNe5VYheuFPmqy5xGeUvh6xMiS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_members`
--
ALTER TABLE `about_members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `about_member_facts`
--
ALTER TABLE `about_member_facts`
  ADD PRIMARY KEY (`fact_id`);

--
-- Indexes for table `about_team`
--
ALTER TABLE `about_team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref` (`ref`);

--
-- Indexes for table `job_items`
--
ALTER TABLE `job_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_members`
--
ALTER TABLE `about_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `about_member_facts`
--
ALTER TABLE `about_member_facts`
  MODIFY `fact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `about_team`
--
ALTER TABLE `about_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_items`
--
ALTER TABLE `job_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `job_items`
--
ALTER TABLE `job_items`
  ADD CONSTRAINT `job_items_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
