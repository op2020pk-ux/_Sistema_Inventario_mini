<?php
$type = $_GET['type'] ?? 'css';

if ($type === 'js') {
    header('Content-Type: application/javascript; charset=utf-8');
    echo "document.addEventListener('DOMContentLoaded', () => {\n";
    echo "  document.querySelectorAll('.alert').forEach(el => {\n";
    echo "    setTimeout(() => { el.style.transition = 'opacity 0.4s'; el.style.opacity = '0'; }, 4000);\n";
    echo "  });\n";
    echo "});\n";
    exit;
}

header('Content-Type: text/css; charset=utf-8');
echo ".table th { white-space: nowrap; }\n";
echo ".card { border-radius: 0.6rem; }\n";