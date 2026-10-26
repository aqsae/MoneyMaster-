 -- MoneyMaster database
-- Import via phpMyAdmin (Import tab) or: mysql -u root < moneymaster_db.sql
-- Creates the moneymaster_db database used by settings.php

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `moneymaster_db`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `moneymaster_db`;

DROP TABLE IF EXISTS `job_items`;
DROP TABLE IF EXISTS `eoi`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `jobs`;

-- --------------------------------------------------------
-- Jobs (jobs.php)
-- --------------------------------------------------------

CREATE TABLE `jobs` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ref`         VARCHAR(10)  NOT NULL UNIQUE,
  `title`       VARCHAR(100) NOT NULL,
  `salary_min`  INT UNSIGNED NOT NULL,
  `salary_max`  INT UNSIGNED NOT NULL,
  `description` TEXT         NOT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- One table holds all three bullet lists (responsibilities / essential / preferable)
CREATE TABLE `job_items` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `job_id`     INT UNSIGNED NOT NULL,
  `type`       ENUM('responsibility','essential','preferable') NOT NULL,
  `content`    VARCHAR(255) NOT NULL,
  `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `jobs` (`id`, `ref`, `title`, `salary_min`, `salary_max`, `description`) VALUES
(1, 'ENG4A2', 'Senior Software Engineer', 105000, 135000,
 'In this role you will design, build, and maintain scalable software systems that power MoneyMaster''s core financial platform, collaborating with cross-functional teams to deliver high-quality solutions in a fast-paced fintech environment.'),
(2, 'FCA8B1', 'Financial Crime Analyst', 110000, 150000,
 'In this position you will work with the cyber security team to identify and analyze potential financial crimes while ensuring compliance with anti-money laundering (AML) and counter-terrorism financing (CTF) laws'),
(3, 'PMX7A3', 'Product Manager - Lending', 90000, 120000,
 'In this role you will own the product strategy and roadmap for MoneyMaster''s lending products, working closely with engineering, design, and risk teams to deliver seamless borrowing experiences for our customers.');

INSERT INTO `job_items` (`job_id`, `type`, `content`, `sort_order`) VALUES
(1, 'responsibility', 'Design and develop robust, scalable back-end services and APIs.', 1),
(1, 'responsibility', 'Collaborate with product managers and designers to deliver new features.', 2),
(1, 'responsibility', 'Conduct code reviews and mentor junior engineers.', 3),
(1, 'responsibility', 'Identify and resolve performance bottlenecks and technical debt.', 4),
(1, 'responsibility', 'Contribute to architectural decisions and technical roadmaps.', 5),
(1, 'essential', '5+ years of professional software development experience.', 1),
(1, 'essential', 'Proficiency in one or more languages such as Python, Java, or Go.', 2),
(1, 'essential', 'Strong understanding of system design, data structures, and algorithms.', 3),
(1, 'essential', 'Experience working with relational and non-relational databases.', 4),
(1, 'preferable', 'Experience in the fintech or financial services industry.', 1),
(1, 'preferable', 'Familiarity with cloud platforms such as AWS, GCP, or Azure.', 2),
(1, 'preferable', 'Knowledge of CI/CD pipelines and DevOps practices.', 3),

(2, 'responsibility', 'Monitor transaction queues for unusual or high-risk patterns.', 1),
(2, 'responsibility', 'Conduct Know Your Customer (KYC) and Know Your Business (KYB) checks.', 2),
(2, 'responsibility', 'Analyze and investigate potential financial crimes.', 3),
(2, 'responsibility', 'Investigate internal fraud, scams, and cyber threats.', 4),
(2, 'essential', 'Strong analytical and data interpretation abilities.', 1),
(2, 'essential', 'Experience with financial crime detection tools and techniques.', 2),
(2, 'essential', 'Problem-solving and clear communication skills.', 3),
(2, 'essential', 'Relevant study in criminology, accounting, or risk management.', 4),
(2, 'preferable', '1-3 years working in risk management, banking operations, audit, or regulatory compliance.', 1),
(2, 'preferable', 'Experience with financial crime detection software and tools.', 2),
(2, 'preferable', 'Knowledge of financial regulations and compliance standards.', 3),

(3, 'responsibility', 'Define and prioritize the product roadmap for lending features and services.', 1),
(3, 'responsibility', 'Gather and translate customer insights into clear product requirements.', 2),
(3, 'responsibility', 'Collaborate with engineering, design, and compliance teams through the full product lifecycle.', 3),
(3, 'responsibility', 'Monitor key product metrics and use data to drive continuous improvement.', 4),
(3, 'responsibility', 'Engage with stakeholders to align product direction with business goals.', 5),
(3, 'essential', 'Proven experience as a Product Manager, ideally in financial services or fintech.', 1),
(3, 'essential', 'Strong understanding of lending products such as personal loans, BNPL, or credit.', 2),
(3, 'essential', 'Excellent communication and stakeholder management skills.', 3),
(3, 'essential', 'Ability to use data and research to inform product decisions.', 4),
(3, 'preferable', 'Experience working in an agile development environment.', 1),
(3, 'preferable', 'Familiarity with credit risk, regulatory compliance, or responsible lending obligations.', 2),
(3, 'preferable', 'Background in UX research or customer discovery methods.', 3);


-- Expressions of interest (apply.php, process_eoi.php, manage.php)


CREATE TABLE `eoi` (
  `EOInumber`      INT AUTO_INCREMENT PRIMARY KEY,
  `job_ref`        VARCHAR(6)   NOT NULL,
  `first_name`     VARCHAR(20)  NOT NULL,
  `last_name`      VARCHAR(20)  NOT NULL,
  `dob`            DATE         NOT NULL,
  `gender`         VARCHAR(10)  NOT NULL,
  `street_address` VARCHAR(40)  NOT NULL,
  `suburb`         VARCHAR(40)  NOT NULL,
  `state`          VARCHAR(3)   NOT NULL,
  `postcode`       CHAR(4)      NOT NULL,
  `email`          VARCHAR(100) NOT NULL,
  `phone`          VARCHAR(12)  NOT NULL,
  `skills`         VARCHAR(255),
  `other_skills`   TEXT,
  `status`         ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `eoi` (`EOInumber`, `job_ref`, `first_name`, `last_name`, `dob`, `gender`, `street_address`, `suburb`, `state`, `postcode`, `email`, `phone`, `skills`, `other_skills`, `status`) VALUES
(1, 'ENG4A2', 'Alice', 'Nguyen', '1998-03-14', 'female', '12 Glenferrie Rd', 'Hawthorn', 'VIC', '3122', 'alice.nguyen@example.com', '0400111222', 'software-development', '', 'New'),
(2, 'ENG4A2', 'Ben', 'Smith', '1995-07-02', 'male', '8 George St', 'Sydney', 'NSW', '2000', 'ben.smith@example.com', '0400222333', 'software-development, digital-payments', '', 'Current'),
(3, 'FCA8B1', 'Chloe', 'Smith', '2001-11-20', 'female', '45 Queen St', 'Brisbane', 'QLD', '4000', 'chloe.smith@example.com', '0400333444', 'financial-security, financial-analysis', '', 'New'),
(4, 'FCA8B1', 'Priya', 'Patel', '2004-02-20', 'female', '10 Swanston St', 'Melbourne', 'VIC', '3000', 'priya.patel@example.com', '0400999888', 'financial-analysis', '', 'Final'),
(5, 'PMX7A3', 'Daniel', 'Lee', '1990-01-30', 'male', '3 Hay St', 'Perth', 'WA', '6000', 'daniel.lee@example.com', '0400444555', '', 'Product roadmaps and stakeholder management', 'Final'),
(6, 'PMX7A3', 'Emma', 'Brown', '1999-05-09', 'other', '27 Rundle Mall', 'Adelaide', 'SA', '5000', 'emma.brown@example.com', '0400555666', 'budgeting-tools, customer-service', '', 'New');


-- Manager accounts (login.php) - admin / admin, password stored as a hash


CREATE TABLE `users` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `username`      VARCHAR(50)  NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$SSCjxKH8vEZI6MVgFmTfyOE6/.NcNe5VYheuFPmqy5xGeUvh6xMiS');

-- about page!!

DROP TABLE IF EXISTS about_member_facts;
DROP TABLE IF EXISTS about_members;
DROP TABLE IF EXISTS about_team; -- ensures no duplicates!!

CREATE TABLE IF NOT EXISTS about_member_facts (
    fact_id INT AUTO_INCREMENT PRIMARY KEY,
    member_name VARCHAR(100) NOT NULL,
    favorite_app VARCHAR(100) NOT NULL,
    favorite_book VARCHAR(100) NOT NULL,
    hobby VARCHAR(100) NOT NULL,
    dream_job VARCHAR(100) NOT NULL,
    team_comment TEXT NOT NULL
);

INSERT INTO about_member_facts
(member_name, favorite_app, favorite_book, hobby, dream_job, team_comment)
VALUES
('Aqsa Shiyana Baanu', 'Pinterest', 'Giovanni''s Room',
 'Drawing', 'Embedded Systems Engineer',
 'Everyone is very kind and respectful.'),
('Sukhmanpreet Kaur', 'YouTube', 'Can''t Hurt Me',
 'Singing', 'AI Engineer',
 'Everyone is very helpful and supportive.'),
('Noah Puglia', 'PlayStation 3', 'Clear and Present Danger',
 'Playing drums', 'Cybersecurity Architect',
 'We communicate well.'),
('Enricko Hartanto', 'TikTok', 'The Setting Sun',
 'Swimming', 'Data Analyst',
 'Everyone is very easy to talk to.');

-- About page: team details
CREATE TABLE IF NOT EXISTS about_team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(50) NOT NULL,
    intro TEXT NOT NULL,
    class_day VARCHAR(30) NOT NULL,
    class_time VARCHAR(30) NOT NULL,
    tutor VARCHAR(50) NOT NULL
);

INSERT INTO about_team
(team_name, intro, class_day, class_time, tutor)
VALUES
(
    'The Moneymasters',
    'Welcome to our team! We are a group of dedicated students passionate about what we do.',
    'Wednesday',
    '12:30 PM to 2:30 PM',
    'Feixue Yan'
);

-- About page: member contributions and quotes
CREATE TABLE IF NOT EXISTS about_members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    student_id VARCHAR(12) NOT NULL,
    part1_work TEXT NOT NULL,
    part2_work TEXT NOT NULL,
    quote VARCHAR(500) NOT NULL
);

INSERT INTO about_members
(name, student_id, part1_work, part2_work, quote)
VALUES
(
    'Aqsa Shiyana Baanu',
    '106512925',
    'Developed the Home page, logo, styles and navigation bar implemented across all pages.',
    'Worked on header.inc, footer.inc, logout.php, login.php, debugging, updating database and the presentation.',
    'என்ன செய்வது என்று தெரியாத பொழுது என்ன செய்கிறாயோ அதுதான் உன் வெற்றிக்கு வழிவகுக்கும்'
),
(
    'Sukhmanpreet Kaur',
    '106390891',
    'Developed the About page.',
    'Worked on eoi_options.php, proofreading and presentation slides.',
    'ਮਨੁ ਜੀਤੈ ਜਗੁ ਜੀਤੁ'
),
(
    'Noah Puglia',
    '105925250',
    'Developed the Jobs page and database setup.',
    'Worked on db.php, settings.php, functions.php, testing, bug fixes, updating database and the search function.',
    'Si votre nom ne figure pas dans le chapeau, comment pouvez-vous espérer être tiré au sort ? C''est impossible.'
),
(
    'Enricko Hartanto',
    '106705152',
    'Built the job application page and its form validation.',
    'Built process_eoi, manage.php and the EOI database functionality and helped integrate the database.',
    'Gantungkan cita-citamu setinggi langit! Bermimpilah setinggi langit. Jika engkau jatuh, engkau akan jatuh di antara bintang-bintang.'
);
