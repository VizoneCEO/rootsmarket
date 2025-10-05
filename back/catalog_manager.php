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
$upload_dir = '/front/multimedia/catalogos/';
$target_directory = $_SERVER['DOCUMENT_ROOT'] . $upload_dir;

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
                    // --- !! VALIDACIÓN IMPORTANTE !! ---
                    // Verificamos que el nombre no esté vacío antes de hacer nada.
                    if (empty(trim($_POST['nombre']))) {
                        $_SESSION['error_message'] = "El nombre del catálogo no puede estar vacío.";
                        header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                        exit(); // Detenemos el script aquí para no continuar.
                    }

                    $nombre = trim($_POST['nombre']);
                    $descripcion = trim($_POST['descripcion']);
                    $estatus = $_POST['estatus'];
                    $imagen_url = null;

                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        $file_name = uniqid() . '-' . basename($_FILES["imagen"]["name"]);
                        $target_file = $target_directory . $file_name;

                        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
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
                    // --- !! VALIDACIÓN IMPORTANTE !! ---
                    if (empty(trim($_POST['nombre']))) {
                        $_SESSION['error_message'] = "El nombre del catálogo no puede estar vacío.";
                        header('Location: /front/admin/perfilAdmin.php?page=configuracion');
                        exit();
                    }

                    $id = $_POST['catalog_id'];
                    $nombre = trim($_POST['nombre']);
                    $descripcion = trim($_POST['descripcion']);
                    $estatus = $_POST['estatus'];

                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        // ... (lógica para subir y actualizar imagen)
                        $stmt = $pdo->prepare("UPDATE catalogos SET nombre = ?, descripcion = ?, estatus = ?, imagen_url = ? WHERE id = ?");
                        $stmt->execute([$nombre, $descripcion, $estatus, $new_imagen_url, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE catalogos SET nombre = ?, descripcion = ?, estatus = ? WHERE id = ?");
                        $stmt->execute([$nombre, $descripcion, $estatus, $id]);
                    }

                    $_SESSION['success_message'] = "Catálogo '" . htmlspecialchars($nombre) . "' actualizado exitosamente.";
                }
                break;

            // --- ACCIÓN: ELIMINAR UN CATÁLOGO ---
            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['catalog_id'];

                    // (Opcional) Borrar imagen...
                    $stmt_select = $pdo->prepare("SELECT imagen_url FROM catalogos WHERE id = ?");
                    // ...

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
        // Manejo de errores de duplicados (UNIQUE) u otros errores de BD.
        if ($e->errorInfo[1] == 1062) { // Código de error para entrada duplicada
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