<?php
require_once __DIR__ . 'db.php';
require_once __DIR__ . 'functions.php';

$pageTitle   = 'Work at MoneyMaster';
$currentPage = 'jobs.php';

$hiringProcess = [
    'Initial application',
    'Phone interview',
    'Technical assessment',
    'On-site interview',
    'Position Offers',
];

// 1. Fetch all jobs
$jobs = $pdo->query('SELECT id, ref, title, salary_min, salary_max, description
                     FROM jobs ORDER BY created_at DESC, id DESC')->fetchAll();

// 2. Fetch all bullet items in one query, then group them by job
$items = [];
$stmt = $pdo->query('SELECT job_id, type, content FROM job_items ORDER BY job_id, type, sort_order');
foreach ($stmt as $row) {
    $items[$row['job_id']][$row['type']][] = $row['content'];
}

include __DIR__ . 'header.inc';
?>
    <main>
      <h1><?= e($pageTitle) ?></h1>

      <aside>Our hiring Process
        <ol>
          <?php foreach ($hiringProcess as $step): ?>
          <li><?= e($step) ?></li>
          <?php endforeach; ?>
        </ol>
      </aside>

      <?php if (!$jobs): ?>
      <p>There are no open positions right now. Please check back soon.</p>
      <?php endif; ?>

      <?php foreach ($jobs as $job): ?>
      <?php $list = $items[$job['id']] ?? []; ?>
      <section>
        <h2><?= e($job['title']) ?></h2>
        <p>Ref: <?= e($job['ref']) ?> | Salary: $<?= number_format((int)$job['salary_min']) ?>-$<?= number_format((int)$job['salary_max']) ?></p>
        <p><?= e($job['description']) ?></p>

        <h3>Key Responsibilities</h3>
<?php renderList('ol', $list['responsibility'] ?? []); ?>

        <h3>Essential Requirements</h3>
<?php renderList('ul', $list['essential'] ?? []); ?>

        <h3>Preferable Requirements</h3>
<?php renderList('ul', $list['preferable'] ?? []); ?>

        <a class="btn apply-btn" href="apply.php?ref=<?= urlencode($job['ref']) ?>" aria-label="Apply for <?= e($job['title']) ?>">Apply now</a>
      </section>
      <?php endforeach; ?>
    </main>
<?php include __DIR__ . 'footer.inc'; ?>
