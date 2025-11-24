<?php
// back/departamentosCall.php

// Incluir el archivo de conexión a la base de datos
require_once(__DIR__ . '/conection/db.php');

try {
    // Preparar la consulta para seleccionar todos los catálogos activos
    $stmt = $pdo->prepare("SELECT nombre, imagen_url FROM catalogos WHERE estatus = 'activo' ORDER BY nombre ASC");

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener todos los resultados como un array asociativo
    $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer la cabecera para indicar que la respuesta es de tipo JSON
    header('Content-Type: application/json');

    // Convertir el array de resultados a formato JSON y mostrarlo
    echo json_encode($departamentos);

} catch (PDOException $e) {
    // En caso de error en la base de datos, mostrar un mensaje de error en formato JSON
    // Se establece un código de respuesta HTTP 500 para indicar un error del servidor
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
?>