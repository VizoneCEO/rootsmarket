<?php
require_once(__DIR__ . '/back/conection/db.php');

$queries = [
    // Prompt 2: Mobile Categories Icons
    "ALTER TABLE catalogos ADD COLUMN IF NOT EXISTS icono_url VARCHAR(255) DEFAULT NULL",

    // Prompt 3: Dynamic Promotions & News
    "ALTER TABLE productos ADD COLUMN IF NOT EXISTS es_novedad TINYINT(1) DEFAULT 0",
    "ALTER TABLE productos ADD COLUMN IF NOT EXISTS es_promocion TINYINT(1) DEFAULT 0",

    // Prompt 4: Customizable Background for Product Detail
    "ALTER TABLE productos ADD COLUMN IF NOT EXISTS fondo_detalle VARCHAR(255) DEFAULT NULL"
];

echo "<h2>Starting Database Updates...</h2>";

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
        echo "<p style='color:green'>SUCCESS: $sql</p>";
    } catch (PDOException $e) {
        echo "<p style='color:orange'>WARNING: " . $e->getMessage() . " (Query: $sql)</p>";
    }
}

echo "<h3>Done.</h3>";
?>