<?php
session_start();

// --- CONEXIÓN A LA BASE DE DATOS ---
require_once(__DIR__ . '/conection/db.php');

// --- VERIFICACIÓN DE SEGURIDAD ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
    $_SESSION['error_message'] = "Acceso denegado. No tienes permisos para esta acción.";
    header('Location: /front/login.php');
    exit();
}

// --- CONFIGURACIÓN PARA SUBIDA DE IMÁGENES ---
$upload_dir = '/front/multimedia/productos/';
$target_directory = $_SERVER['DOCUMENT_ROOT'] . $upload_dir;
if (!file_exists($target_directory)) {
    mkdir($target_directory, 0777, true);
}

// --- CONTROLADOR PRINCIPAL ---
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    try {
        switch ($action) {
            // --- ACCIÓN: CREAR UN NUEVO PRODUCTO ---
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // 1. Validación de datos obligatorios
                    if (empty(trim($_POST['nombre'])) || empty(trim($_POST['sku'])) || empty($_POST['precio_venta']) || empty($_POST['catalogo_id'])) {
                        $_SESSION['error_message'] = "Los campos Nombre, SKU, Precio de Venta y Catálogo son obligatorios.";
                        header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                        exit();
                    }

                    // 2. Iniciar transacción para asegurar consistencia
                    $pdo->beginTransaction();

                    // 3. Preparar datos para la tabla 'productos'
                    $sql_producto = "INSERT INTO productos (
                        catalogo_id, nombre, sku, descripcion_corta, descripcion_larga,
                        precio_compra, precio_venta, precio_oferta, origen,
                        es_organico, es_vegano, es_vegetariano, es_sin_gluten,
                        porcion_info, calorias, proteinas_g, carbohidratos_g, grasas_g, azucares_g, fibra_g, sodio_mg,
                        calificacion, estatus
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt_producto = $pdo->prepare($sql_producto);

                    // 4. Ejecutar la inserción en la tabla 'productos'
                    $stmt_producto->execute([
                        $_POST['catalogo_id'],
                        trim($_POST['nombre']),
                        trim($_POST['sku']),
                        empty(trim($_POST['descripcion_corta'])) ? null : trim($_POST['descripcion_corta']),
                        empty(trim($_POST['descripcion_larga'])) ? null : trim($_POST['descripcion_larga']),
                        empty($_POST['precio_compra']) ? null : $_POST['precio_compra'],
                        $_POST['precio_venta'],
                        empty($_POST['precio_oferta']) ? null : $_POST['precio_oferta'],
                        empty(trim($_POST['origen'])) ? null : trim($_POST['origen']),
                        isset($_POST['es_organico']) ? 1 : 0,
                        isset($_POST['es_vegano']) ? 1 : 0,
                        isset($_POST['es_vegetariano']) ? 1 : 0,
                        isset($_POST['es_sin_gluten']) ? 1 : 0,
                        empty(trim($_POST['porcion_info'])) ? null : trim($_POST['porcion_info']),
                        empty($_POST['calorias']) ? null : $_POST['calorias'],
                        empty($_POST['proteinas_g']) ? null : $_POST['proteinas_g'],
                        empty($_POST['carbohidratos_g']) ? null : $_POST['carbohidratos_g'],
                        empty($_POST['grasas_g']) ? null : $_POST['grasas_g'],
                        empty($_POST['azucares_g']) ? null : $_POST['azucares_g'],
                        empty($_POST['fibra_g']) ? null : $_POST['fibra_g'],
                        empty($_POST['sodio_mg']) ? null : $_POST['sodio_mg'],
                        empty($_POST['calificacion']) ? null : $_POST['calificacion'],
                        $_POST['estatus']
                    ]);

                    $producto_id = $pdo->lastInsertId(); // Obtener el ID del producto recién creado

                    // 5. Manejo de la subida de múltiples imágenes
                    if (isset($_FILES['imagenes']) && !empty(array_filter($_FILES['imagenes']['name']))) {
                        $sql_imagen = "INSERT INTO producto_imagenes (producto_id, imagen_url, alt_text, orden) VALUES (?, ?, ?, ?)";
                        $stmt_imagen = $pdo->prepare($sql_imagen);

                        foreach ($_FILES['imagenes']['name'] as $key => $name) {
                            if ($_FILES['imagenes']['error'][$key] == 0) {
                                $file_name = uniqid() . '-' . basename($name);
                                $target_file = $target_directory . $file_name;

                                if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $target_file)) {
                                    $imagen_url = $upload_dir . $file_name;
                                    $stmt_imagen->execute([$producto_id, $imagen_url, trim($_POST['nombre']), $key]);
                                }
                            }
                        }
                    }

                    // 6. Confirmar la transacción
                    $pdo->commit();
                    $_SESSION['success_message'] = "Producto '" . htmlspecialchars($_POST['nombre']) . "' creado exitosamente.";
                }
                break;

            // ... (Aquí irán los casos para 'update' y 'delete' en el futuro) ...

            default:
                $_SESSION['error_message'] = "Acción no reconocida.";
                break;
        }

    } catch (PDOException $e) {
        $pdo->rollBack(); // Revertir la transacción si algo falla
        if ($e->errorInfo[1] == 1062) {
            $_SESSION['error_message'] = "Error: Ya existe un producto con ese SKU.";
        } else {
            $_SESSION['error_message'] = "Error en la base de datos: " . $e->getMessage();
        }
    }
} else {
    $_SESSION['error_message'] = "No se especificó ninguna acción.";
}

// --- REDIRECCIÓN ---
header('Location: /front/admin/perfilAdmin.php?page=configuracion');
exit();
?>