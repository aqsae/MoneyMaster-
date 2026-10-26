<?php
// shared helpers (functions that can be usedacross different php files

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function renderList(string $tag, array $items): void {
    echo "<$tag>\n";
    foreach ($items as $item) {
        echo '  <li>' . e($item) . "</li>\n";
    }
    echo "</$tag>\n";
}

// counts bytes and not unicode characters so non-ASCII text in our website will be included and the jobs.php can call it
function excerpt(string $text, int $limit = 180): string
{
    $text = trim($text);

    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit)) . '...';
}
