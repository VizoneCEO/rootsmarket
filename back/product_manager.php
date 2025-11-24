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
                    if (empty(trim($_POST['nombre'])) || empty(trim($_POST['sku'])) || empty($_POST['precio_venta']) || empty($_POST['catalogo_id'])) {
                        $_SESSION['error_message'] = "Los campos Nombre, SKU, Precio de Venta y Catálogo son obligatorios.";
                        header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                        exit();
                    }
                    $pdo->beginTransaction();
                    $sql_producto = "INSERT INTO productos (
                        catalogo_id, nombre, sku, descripcion_corta, descripcion_larga, precio_compra, precio_venta, precio_oferta, origen,
                        es_organico, es_vegano, es_vegetariano, es_sin_gluten, porcion_info, calorias, proteinas_g, carbohidratos_g, grasas_g, azucares_g, fibra_g, sodio_mg,
                        calificacion, estatus
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_producto = $pdo->prepare($sql_producto);
                    $stmt_producto->execute([
                        $_POST['catalogo_id'], trim($_POST['nombre']), trim($_POST['sku']),
                        empty(trim($_POST['descripcion_corta'])) ? null : trim($_POST['descripcion_corta']),
                        empty(trim($_POST['descripcion_larga'])) ? null : trim($_POST['descripcion_larga']),
                        empty($_POST['precio_compra']) ? null : $_POST['precio_compra'],
                        $_POST['precio_venta'],
                        empty($_POST['precio_oferta']) ? null : $_POST['precio_oferta'],
                        empty(trim($_POST['origen'])) ? null : trim($_POST['origen']),
                        isset($_POST['es_organico']) ? 1 : 0, isset($_POST['es_vegano']) ? 1 : 0,
                        isset($_POST['es_vegetariano']) ? 1 : 0, isset($_POST['es_sin_gluten']) ? 1 : 0,
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
                    $producto_id = $pdo->lastInsertId();
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
                    $pdo->commit();
                    $_SESSION['success_message'] = "Producto '" . htmlspecialchars($_POST['nombre']) . "' creado exitosamente.";
                }
                break;

            // --- ACCIÓN: ACTUALIZAR UN PRODUCTO EXISTENTE ---
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['product_id']) || empty(trim($_POST['nombre']))) {
                        $_SESSION['error_message'] = "Faltan datos obligatorios para actualizar el producto.";
                        header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                        exit();
                    }

                    $product_id = $_POST['product_id'];
                    $pdo->beginTransaction();

                    // 1. Actualizar los datos de texto del producto
                    $sql_update = "UPDATE productos SET
                        catalogo_id = ?, nombre = ?, sku = ?, descripcion_corta = ?, descripcion_larga = ?,
                        precio_compra = ?, precio_venta = ?, precio_oferta = ?, origen = ?,
                        es_organico = ?, es_vegano = ?, es_vegetariano = ?, es_sin_gluten = ?,
                        porcion_info = ?, calorias = ?, proteinas_g = ?, carbohidratos_g = ?, grasas_g = ?, azucares_g = ?, fibra_g = ?, sodio_mg = ?,
                        calificacion = ?, estatus = ?
                    WHERE id = ?";

                    $stmt_update = $pdo->prepare($sql_update);
                    $stmt_update->execute([
                        $_POST['catalogo_id'], trim($_POST['nombre']), trim($_POST['sku']),
                        $_POST['descripcion_corta'], $_POST['descripcion_larga'], $_POST['precio_compra'],
                        $_POST['precio_venta'], $_POST['precio_oferta'], $_POST['origen'],
                        isset($_POST['es_organico']) ? 1 : 0, isset($_POST['es_vegano']) ? 1 : 0,
                        isset($_POST['es_vegetariano']) ? 1 : 0, isset($_POST['es_sin_gluten']) ? 1 : 0,
                        $_POST['porcion_info'], $_POST['calorias'], $_POST['proteinas_g'],
                        $_POST['carbohidratos_g'], $_POST['grasas_g'], $_POST['azucares_g'],
                        $_POST['fibra_g'], $_POST['sodio_mg'], $_POST['calificacion'], $_POST['estatus'],
                        $product_id
                    ]);

                    // 2. Lógica para manejar la actualización de la imagen
                    if (isset($_FILES['imagenes']) && $_FILES['imagenes']['error'][0] == 0) {

                        // a. Buscar la imagen principal actual para borrarla
                        $stmt_find_img = $pdo->prepare("SELECT id, imagen_url FROM producto_imagenes WHERE producto_id = ? ORDER BY orden ASC LIMIT 1");
                        $stmt_find_img->execute([$product_id]);
                        $imagen_actual = $stmt_find_img->fetch();

                        if ($imagen_actual) {
                            // Borrar el archivo físico del servidor
                            $ruta_completa_img_actual = $_SERVER['DOCUMENT_ROOT'] . $imagen_actual['imagen_url'];
                            if (file_exists($ruta_completa_img_actual)) {
                                unlink($ruta_completa_img_actual);
                            }
                        }

                        // b. Subir la nueva imagen
                        $new_image_name = uniqid() . '-' . basename($_FILES['imagenes']['name'][0]);
                        $target_file = $target_directory . $new_image_name;

                        if (move_uploaded_file($_FILES['imagenes']['tmp_name'][0], $target_file)) {
                            $new_image_url = $upload_dir . $new_image_name;

                            // c. Actualizar la base de datos
                            if ($imagen_actual) {
                                // Si ya existía una imagen, actualizamos el registro
                                $stmt_update_img = $pdo->prepare("UPDATE producto_imagenes SET imagen_url = ? WHERE id = ?");
                                $stmt_update_img->execute([$new_image_url, $imagen_actual['id']]);
                            } else {
                                // Si no existía, insertamos un nuevo registro
                                $stmt_insert_img = $pdo->prepare("INSERT INTO producto_imagenes (producto_id, imagen_url, orden) VALUES (?, ?, 0)");
                                $stmt_insert_img->execute([$product_id, $new_image_url]);
                            }
                        }
                    }

                    $pdo->commit();
                    $_SESSION['success_message'] = "Producto '" . htmlspecialchars($_POST['nombre']) . "' actualizado exitosamente.";
                }
                break;

            // --- ACCIÓN: ELIMINAR UN PRODUCTO ---
            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['product_id'])) {
                        $_SESSION['error_message'] = "No se especificó el ID del producto a eliminar.";
                    } else {
                        $product_id = $_POST['product_id'];
                        $pdo->beginTransaction();

                        // 1. Borrar las imágenes asociadas del servidor y de la tabla 'producto_imagenes'
                        $stmt_img = $pdo->prepare("SELECT imagen_url FROM producto_imagenes WHERE producto_id = ?");
                        $stmt_img->execute([$product_id]);
                        $imagenes = $stmt_img->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($imagenes as $imagen_url) {
                            $file_path = $_SERVER['DOCUMENT_ROOT'] . $imagen_url;
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                        }

                        $stmt_delete_img = $pdo->prepare("DELETE FROM producto_imagenes WHERE producto_id = ?");
                        $stmt_delete_img->execute([$product_id]);

                        // 2. Borrar el producto de la tabla 'productos'
                        $stmt_delete_prod = $pdo->prepare("DELETE FROM productos WHERE id = ?");
                        $stmt_delete_prod->execute([$product_id]);

                        $pdo->commit();
                        $_SESSION['success_message'] = "Producto eliminado exitosamente.";
                    }
                }
                break;

            default:
                $_SESSION['error_message'] = "Acción no reconocida.";
                break;
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
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