<?php
echo "Current directory: " . __DIR__ . "<br>";
echo "Trying to find config/db.php...<br>";

if (file_exists('config/db.php')) {
    echo "✅ Found config/db.php using relative path<br>";
} else {
    echo "❌ Cannot find config/db.php using relative path<br>";
}

if (file_exists(__DIR__ . '/config/db.php')) {
    echo "✅ Found config/db.php using absolute path<br>";
} else {
    echo "❌ Cannot find config/db.php using absolute path<br>";
}

echo "<br>Files in current directory:<br>";
$files = scandir(__DIR__);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "📄 " . $file . "<br>";
    }
}
?>
