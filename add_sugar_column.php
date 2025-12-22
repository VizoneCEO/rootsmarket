<?php
require_once 'back/conection/db.php';

try {
    $sql = "ALTER TABLE productos ADD COLUMN tiene_azucar TINYINT(1) DEFAULT 0";
    $pdo->exec($sql);
    echo "Column 'tiene_azucar' added successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>