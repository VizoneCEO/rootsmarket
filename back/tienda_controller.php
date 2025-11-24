<?php
// back/tienda_controller.php

// Incluir el archivo de conexión a la base de datos
require_once(__DIR__ . '/conection/db.php');

// --- LÓGICA PARA OBTENER LOS DATOS ---

// Obtener los parámetros de filtro de la URL (si existen)
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// 1. OBTENER TODAS LAS CATEGORÍAS ACTIVAS (para el dropdown de filtros)
$categorias = [];
try {
    $stmt_cat = $pdo->prepare("SELECT id, nombre FROM catalogos WHERE estatus = 'activo' ORDER BY nombre ASC");
    $stmt_cat->execute();
    $categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejar error si la consulta de categorías falla
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener las categorías.']);
    exit();
}

// 2. OBTENER LOS PRODUCTOS FILTRADOS
$productos = [];
try {
    // Consulta base
    $sql = "SELECT 
                p.id, p.nombre, p.precio_venta, p.calificacion,
                (SELECT imagen_url FROM producto_imagenes WHERE producto_id = p.id ORDER BY orden ASC LIMIT 1) as imagen_principal
            FROM productos p
            WHERE p.estatus = 'activo'";

    $params = [];

    // Añadir filtro por categoría si se seleccionó una
    if ($categoria_id > 0) {
        $sql .= " AND p.catalogo_id = ?";
        $params[] = $categoria_id;
    }

    // Añadir filtro por término de búsqueda si se escribió algo
    if (!empty($busqueda)) {
        $sql .= " AND p.nombre LIKE ?";
        $params[] = '%' . $busqueda . '%';
    }

    $sql .= " ORDER BY p.nombre ASC";

    $stmt_prod = $pdo->prepare($sql);
    $stmt_prod->execute($params);
    $productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Manejar error si la consulta de productos falla
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los productos: ' . $e->getMessage()]);
    exit();
}


// --- RESPUESTA EN FORMATO JSON ---

// Combinar ambos resultados en un solo array
$respuesta = [
    'categorias' => $categorias,
    'productos' => $productos
];

// Establecer la cabecera para indicar que la respuesta es JSON
header('Content-Type: application/json');

// Imprimir la respuesta en formato JSON
echo json_encode($respuesta);
?>