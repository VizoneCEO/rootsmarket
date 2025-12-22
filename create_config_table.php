<?php
require_once 'back/conection/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS configuracion (
        id INT AUTO_INCREMENT PRIMARY KEY,
        clave VARCHAR(100) NOT NULL UNIQUE,
        valor TEXT
    )";
    $pdo->exec($sql);
    echo "Table 'configuracion' created successfully.\n";

    // Insert default values if not exist
    $defaults = [
        'nombre_temporada' => 'Temporada',
        'imagen_temporada' => 'front/multimedia/temporada_default.png'
    ];

    foreach ($defaults as $key => $val) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO configuracion (clave, valor) VALUES (?, ?)");
        $stmt->execute([$key, $val]);
    }
    echo "Default configuration values inserted.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>