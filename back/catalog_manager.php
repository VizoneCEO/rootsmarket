<?php
session_start();

// --- CONEXIÓN A LA BASE DE DATOS ---
require_once(__DIR__ . '/conection/db.php');

// --- VERIFICACIÓN DE SEGURIDAD ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
    $_SESSION['error_message'] = "Acceso denegado. No tienes permisos para esta acción.";
    header('Location: /front/login.php'); // Redirige al login si no es admin
    exit();
}

// --- CONFIGURACIÓN PARA SUBIDA DE IMÁGENES ---
// Las imágenes se guardarán en la carpeta /front/multimedia/catalogos/
$upload_dir = '/front/multimedia/catalogos/';
$target_directory = $_SERVER['DOCUMENT_ROOT'] . $upload_dir;

// Asegurarse de que el directorio de subida exista, si no, lo crea.
if (!file_exists($target_directory)) {
    mkdir($target_directory, 0777, true);
}

// --- CONTROLADOR PRINCIPAL DE ACCIONES ---
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    try {
        switch ($action) {

            // --- ACCIÓN: CREAR UN NUEVO CATÁLOGO ---
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty(trim($_POST['nombre']))) {
                        $_SESSION['error_message'] = "El nombre del catálogo no puede estar vacío.";
                        header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                        exit();
                    }

                    $nombre = trim($_POST['nombre']);
                    $descripcion = trim($_POST['descripcion']);
                    $estatus = $_POST['estatus'];
                    $imagen_url = null;

                    // Si se subió una imagen, procesarla.
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        $file_name = uniqid() . '-' . basename($_FILES["imagen"]["name"]);
                        $target_file = $target_directory . $file_name;

                        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                            // Guardar la ruta relativa en la base de datos.
                            $imagen_url = $upload_dir . $file_name;
                        } else {
                           $_SESSION['error_message'] = "Hubo un error al subir la imagen.";
                           header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                           exit();
                        }
                    }

                    $stmt = $pdo->prepare("INSERT INTO catalogos (nombre, descripcion, estatus, imagen_url) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$nombre, $descripcion, $estatus, $imagen_url]);

                    $_SESSION['success_message'] = "Catálogo '" . htmlspecialchars($nombre) . "' creado exitosamente.";
                }
                break;

            // --- ACCIÓN: ACTUALIZAR UN CATÁLOGO EXISTENTE ---
            case 'update':
                 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty(trim($_POST['nombre'])) || empty($_POST['catalog_id'])) {
                        $_SESSION['error_message'] = "Faltan datos para actualizar el catálogo.";
                        header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                        exit();
                    }

                    $id = $_POST['catalog_id'];
                    $nombre = trim($_POST['nombre']);
                    $descripcion = trim($_POST['descripcion']);
                    $estatus = $_POST['estatus'];

                    // Obtener la URL de la imagen actual para poder borrarla si se sube una nueva.
                    $stmt_current_img = $pdo->prepare("SELECT imagen_url FROM catalogos WHERE id = ?");
                    $stmt_current_img->execute([$id]);
                    $current_image_url = $stmt_current_img->fetchColumn();

                    $imagen_url_a_actualizar = $current_image_url;

                    // Si se sube una nueva imagen.
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        // Borrar la imagen anterior del servidor, si existe.
                        if ($current_image_url && file_exists($_SERVER['DOCUMENT_ROOT'] . $current_image_url)) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . $current_image_url);
                        }

                        $file_name = uniqid() . '-' . basename($_FILES["imagen"]["name"]);
                        $target_file = $target_directory . $file_name;

                        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                            $imagen_url_a_actualizar = $upload_dir . $file_name; // Nueva URL
                        } else {
                            $_SESSION['error_message'] = "Hubo un error al subir la nueva imagen.";
                            header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                            exit();
                        }
                    }

                    // Actualizar la base de datos.
                    $stmt = $pdo->prepare("UPDATE catalogos SET nombre = ?, descripcion = ?, estatus = ?, imagen_url = ? WHERE id = ?");
                    $stmt->execute([$nombre, $descripcion, $estatus, $imagen_url_a_actualizar, $id]);

                    $_SESSION['success_message'] = "Catálogo '" . htmlspecialchars($nombre) . "' actualizado exitosamente.";
                }
                break;

            // --- ACCIÓN: ELIMINAR UN CATÁLOGO ---
            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['catalog_id'];

                    // Antes de borrar de la BD, obtener la URL de la imagen para borrarla del servidor.
                    $stmt_select = $pdo->prepare("SELECT imagen_url FROM catalogos WHERE id = ?");
                    $stmt_select->execute([$id]);
                    $image_to_delete = $stmt_select->fetchColumn();

                    if ($image_to_delete && file_exists($_SERVER['DOCUMENT_ROOT'] . $image_to_delete)) {
                        unlink($_SERVER['DOCUMENT_ROOT'] . $image_to_delete);
                    }

                    // Ahora sí, borrar el registro de la base de datos.
                    $stmt = $pdo->prepare("DELETE FROM catalogos WHERE id = ?");
                    $stmt->execute([$id]);

                    $_SESSION['success_message'] = "Catálogo eliminado exitosamente.";
                }
                break;

            default:
                $_SESSION['error_message'] = "Acción no reconocida.";
                break;
        }

    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $_SESSION['error_message'] = "Error: Ya existe un catálogo con ese nombre.";
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