-- Template for adding a new job.
-- In phpMyAdmin: select the "moneymaster" database, open the SQL tab,
-- paste this in, change the text, and click Go.
--
-- Tips:
--   * The ref must be unique (no two jobs can share one).
--   * To use an apostrophe inside text, type it twice: 'MoneyMaster''s'
--   * Add or remove bullet lines as needed. Every line except the last
--     one ends with a comma; the last one ends with a semicolon.

USE moneymaster;

INSERT INTO jobs (ref, title, salary_min, salary_max, description) VALUES
('DAT5K9', 'Data Analyst', 85000, 105000,
 'In this role you will turn MoneyMaster''s data into clear insights that help teams make better decisions about our products and customers.');

-- Grab the id of the job we just added so the bullet points link to it
SET @j = LAST_INSERT_ID();

INSERT INTO job_items (job_id, type, content, sort_order) VALUES
(@j, 'responsibility', 'Build dashboards and reports for business stakeholders.', 1),
(@j, 'responsibility', 'Clean, validate, and analyse large datasets.', 2),
(@j, 'responsibility', 'Present findings and recommendations to non-technical teams.', 3),
(@j, 'essential', '2+ years of experience in a data or analytics role.', 1),
(@j, 'essential', 'Strong SQL skills.', 2),
(@j, 'essential', 'Experience with a visualisation tool such as Power BI or Tableau.', 3),
(@j, 'preferable', 'Experience with Python or R.', 1),
(@j, 'preferable', 'Background in financial services.', 2);
