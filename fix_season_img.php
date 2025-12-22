<?php
require_once 'back/conection/db.php';

$new_val = 'front/multimedia/temporada_navidad.png';
$stmt = $pdo->prepare("UPDATE configuracion SET valor = ? WHERE clave = 'imagen_temporada'");
$stmt->execute([$new_val]);

echo "Updated imagen_temporada to: $new_val\n";
?>