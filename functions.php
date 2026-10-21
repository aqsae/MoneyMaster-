<?php
// Shared helpers

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
