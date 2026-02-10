<?php
require_once 'back/conection/db.php';

try {
    // 1. Create table codigos_descuento
    $sql1 = "CREATE TABLE IF NOT EXISTS codigos_descuento (
        id INT AUTO_INCREMENT PRIMARY KEY,
        codigo VARCHAR(50) NOT NULL UNIQUE,
        tipo ENUM('porcentaje', 'fijo') NOT NULL DEFAULT 'porcentaje',
        valor DECIMAL(10, 2) NOT NULL,
        fecha_expiracion DATE NULL,
        activo TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql1);
    echo "Table 'codigos_descuento' created/checked.<br>";

    // 2. Add columns to pedidos table if they don't exist
    // Check if column exists first to avoid error
    $stmt = $pdo->query("SHOW COLUMNS FROM pedidos LIKE 'descuento_codigo'");
    if ($stmt->rowCount() == 0) {
        $sql2 = "ALTER TABLE pedidos ADD COLUMN descuento_codigo VARCHAR(50) NULL AFTER total";
        $pdo->exec($sql2);
        echo "Column 'descuento_codigo' added to 'pedidos'.<br>";
    } else {
        echo "Column 'descuento_codigo' already exists.<br>";
    }

    $stmt = $pdo->query("SHOW COLUMNS FROM pedidos LIKE 'descuento_monto'");
    if ($stmt->rowCount() == 0) {
        $sql3 = "ALTER TABLE pedidos ADD COLUMN descuento_monto DECIMAL(10, 2) DEFAULT 0.00 AFTER descuento_codigo";
        $pdo->exec($sql3);
        echo "Column 'descuento_monto' added to 'pedidos'.<br>";
    } else {
        echo "Column 'descuento_monto' already exists.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>