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

// 1. Fetch jobs, filtered by the search term if one was given
$search = trim($_GET['q'] ?? '');

if ($search === '') {
    $jobs = $pdo->query('SELECT id, ref, title, salary_min, salary_max, description
                         FROM jobs ORDER BY created_at DESC, id DESC')->fetchAll();
} else {
    // Symbols such as % and _ are searched as texts
    $like = '%' . addcslashes($search, '%_\\') . '%';
    $stmt = $pdo->prepare('SELECT id, ref, title, salary_min, salary_max, description
                           FROM jobs
                           WHERE title LIKE ? OR ref LIKE ? OR description LIKE ?
                              OR id IN (SELECT job_id FROM job_items WHERE content LIKE ?)
                           ORDER BY created_at DESC, id DESC');
    $stmt->execute([$like, $like, $like, $like]);
    $jobs = $stmt->fetchAll();
}


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

      <form class="job-search" action="jobs.php" method="get" role="search">
        <label for="q">Search jobs</label>
        <input type="search" id="q" name="q" value="<?= e($search) ?>" placeholder="e.g. engineer, AML, FCA8B1">
        <button type="submit" class="btn">Search</button>
        <?php if ($search !== ''): ?>
        <a href="jobs.php">Clear</a>
        <?php endif; ?>
      </form>

      <?php if ($search !== ''): ?>
      <p class="search-summary"><?= count($jobs) ?> result<?= count($jobs) === 1 ? '' : 's' ?> for &ldquo;<?= e($search) ?>&rdquo;</p>
      <?php endif; ?>

      <?php if (!$jobs && $search !== ''): ?>
      <p>No jobs match your search. Try a different keyword.</p>
      <?php elseif (!$jobs): ?>
      <p>There are no open positions right now. Please check back soon.</p>
      <?php endif; ?>

      <?php foreach ($jobs as $job): ?>
      <?php $list = $items[$job['id']] ?? []; ?>
      <section>
        <details<?= $search !== '' ? ' open' : '' ?>>
          <summary>
            <h2><?= e($job['title']) ?></h2>
            <p class="job-meta">Ref: <?= e($job['ref']) ?> | Salary: $<?= number_format((int)$job['salary_min']) ?>-$<?= number_format((int)$job['salary_max']) ?></p>
            <p class="job-brief"><?= e(excerpt($job['description'])) ?></p>
            <span class="job-toggle" aria-hidden="true"></span>
          </summary>

          <p class="job-desc"><?= e($job['description']) ?></p>

          <h3>Key Responsibilities</h3>
<?php renderList('ol', $list['responsibility'] ?? []); ?>

          <h3>Essential Requirements</h3>
<?php renderList('ul', $list['essential'] ?? []); ?>

          <h3>Preferable Requirements</h3>
<?php renderList('ul', $list['preferable'] ?? []); ?>

          <a class="btn apply-btn" href="apply.php?ref=<?= urlencode($job['ref']) ?>" aria-label="Apply for <?= e($job['title']) ?>">Apply now</a>
        </details>
      </section>
      <?php endforeach; ?>
    </main>
<?php include __DIR__ . 'footer.inc'; ?>
