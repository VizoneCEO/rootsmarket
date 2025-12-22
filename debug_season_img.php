<?php
require_once 'back/conection/db.php';

$stmt = $pdo->query("SELECT valor FROM configuracion WHERE clave = 'imagen_temporada'");
$val = $stmt->fetchColumn();

echo "DB Value: " . ($val ? $val : "NULL") . "\n";
if ($val) {
    if (file_exists($val)) {
        echo "File exists at relative path from root: YES\n";
    } else {
        echo "File exists at relative path from root: NO\n";
    }

    // Check absolute path
    $abs = __DIR__ . '/' . $val;
    echo "Absolute path: " . $abs . "\n";
    if (file_exists($abs)) {
        echo "File exists at absolute path: YES\n";
    } else {
        echo "File exists at absolute path: NO\n";
    }
}
?>