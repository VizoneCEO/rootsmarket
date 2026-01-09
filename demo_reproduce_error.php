el<?php
require_once 'back/conection/db.php';

echo "<h1>Demo: Reproducción del Error 1136</h1>";
echo "<p>Este script simula código malformado para demostrar exactamente qué causa el error que ves en Preproducción.</p>";

// CASO 1: Menos valores que columnas
echo "<h3>Caso 1: Faltan valores en VALUES</h3>";
echo "<code>INSERT INTO catalogos (nombre, descripcion, estatus, imagen_url, icono_url) VALUES (?, ?, ?, ?)</code><br>";
try {
    $stmt = $pdo->prepare("INSERT INTO catalogos (nombre, descripcion, estatus, imagen_url, icono_url) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Test', 'Desc', 'activo', 'img.jpg']);
} catch (PDOException $e) {
    echo "<div style='background-color:#ffecec; border:1px solid red; padding:10px; color:red'>";
    echo "<strong>Error Capturado:</strong> " . $e->getMessage();
    echo "</div>";
}

// CASO 2: Más valores que columnas
echo "<h3>Caso 2: Sobran valores en VALUES (o falta una columna en la lista)</h3>";
echo "<code>INSERT INTO catalogos (nombre, descripcion, estatus, imagen_url) VALUES (?, ?, ?, ?, ?)</code><br>";
try {
    $stmt = $pdo->prepare("INSERT INTO catalogos (nombre, descripcion, estatus, imagen_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['Test', 'Desc', 'activo', 'img.jpg', 'icon.jpg']);
} catch (PDOException $e) {
    echo "<div style='background-color:#ffecec; border:1px solid red; padding:10px; color:red'>";
    echo "<strong>Error Capturado:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<h3>Conclusión</h3>";
echo "<p>El error <code>SQLSTATE[21S01]: ... 1136 Column count doesn't match value count</code> confirma que el archivo <code>back/catalog_manager.php</code> en <strong>Preproducción</strong> tiene una discrepancia entre la lista de columnas y los signos de interrogación <code>?</code>.</p>";
echo "<p>En tu entorno Local, el código es correcto (5 columnas, 5 valores), por lo que funciona bien.</p>";
?>