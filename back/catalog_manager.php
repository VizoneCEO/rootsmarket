<?php
session_start();

// --- CONEXIÓN A LA BASE DE DATOS ---
require_once(__DIR__ . '/conection/db.php');

// --- VERIFICACIÓN DE SEGURIDAD ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
    $_SESSION['error_message'] = "Acceso denegado. No tienes permisos para esta acción.";
    header('Location: ../../login.php'); // Redirigue al login si no es admin
    exit();
}

// --- CONFIGURACIÓN PARA SUBIDA DE IMÁGENES ---
// Las imágenes se guardarán en la carpeta /front/multimedia/catalogos/
$upload_dir = 'front/multimedia/catalogos/'; // Ruta relativa para BD (sin / inicial)
$target_directory = dirname(__DIR__) . '/' . $upload_dir; // Ruta absoluta del sistema de archivos

// Asegurarse de que el directorio de subida exista, si no, lo crea.
if (!file_exists($target_directory)) {
    if (!mkdir($target_directory, 0777, true)) {
        error_log("No se pudo crear el directorio: " . $target_directory);
    }
}

// --- CONTROLADOR PRINCIPAL DE ACCIONES ---
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $active_tab = $_POST['active_tab'] ?? 'catalogs'; // Capture the tab or default to 'catalogs'

    try {
        switch ($action) {

            // --- ACCIÓN: CREAR UN NUEVO CATÁLOGO ---
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty(trim($_POST['nombre']))) {
                        $_SESSION['error_message'] = "El nombre del catálogo no puede estar vacío.";
                        $_SESSION['error_message'] = "El nombre del catálogo no puede estar vacío.";
                        header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=' . $active_tab);
                        exit();
                    }

                    $nombre = trim($_POST['nombre']);
                    $descripcion = trim($_POST['descripcion']);
                    $estatus = $_POST['estatus'];
                    $imagen_url = null;
                    $icono_url = null;

                    // Procesar Imagen Principal
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        $file_name = uniqid() . '-' . basename($_FILES["imagen"]["name"]);
                        $target_file = $target_directory . $file_name;

                        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                            $imagen_url = $upload_dir . $file_name;
                        } else {
                            $_SESSION['error_message'] = "Hubo un error al subir la imagen principal.";
                            header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=' . $active_tab);
                            exit();
                        }
                    }

                    // Procesar Icono (Móvil)
                    if (isset($_FILES['icono']) && $_FILES['icono']['error'] == 0) {
                        $file_name_icon = uniqid() . '-icon-' . basename($_FILES["icono"]["name"]);
                        $target_file_icon = $target_directory . $file_name_icon;

                        if (move_uploaded_file($_FILES["icono"]["tmp_name"], $target_file_icon)) {
                            $icono_url = $upload_dir . $file_name_icon;
                        } else {
                            $_SESSION['error_message'] = "Hubo un error al subir el icono.";
                            header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=' . $active_tab);
                            exit();
                        }
                    }

                    $stmt = $pdo->prepare("INSERT INTO catalogos (nombre, descripcion, estatus, imagen_url, icono_url) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $descripcion, $estatus, $imagen_url, $icono_url]);

                    $_SESSION['success_message'] = "Catálogo '" . htmlspecialchars($nombre) . "' creado exitosamente.";
                }
                break;

            // --- ACCIÓN: ACTUALIZAR UN CATÁLOGO EXISTENTE ---
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty(trim($_POST['nombre'])) || empty($_POST['catalog_id'])) {
                        $_SESSION['error_message'] = "Faltan datos para actualizar el catálogo.";
                        $_SESSION['error_message'] = "Faltan datos para actualizar el catálogo.";
                        header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=' . $active_tab);
                        exit();
                    }

                    $id = $_POST['catalog_id'];
                    $nombre = trim($_POST['nombre']);
                    $descripcion = trim($_POST['descripcion']);
                    $estatus = $_POST['estatus'];

                    // Obtener URLs actuales
                    $stmt_current = $pdo->prepare("SELECT imagen_url, icono_url FROM catalogos WHERE id = ?");
                    $stmt_current->execute([$id]);
                    $current_data = $stmt_current->fetch(PDO::FETCH_ASSOC);

                    $current_image_url = $current_data['imagen_url'];
                    $current_icono_url = $current_data['icono_url'];

                    $imagen_url_a_actualizar = $current_image_url;
                    $icono_url_a_actualizar = $current_icono_url;

                    // Procesar Nueva Imagen Principal
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        if ($current_image_url && file_exists(dirname(__DIR__) . '/' . $current_image_url)) {
                            unlink(dirname(__DIR__) . '/' . $current_image_url);
                        }
                        $file_name = uniqid() . '-' . basename($_FILES["imagen"]["name"]);
                        $target_file = $target_directory . $file_name;
                        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                            $imagen_url_a_actualizar = $upload_dir . $file_name;
                        } else {
                            $_SESSION['error_message'] = "Hubo un error al subir la nueva imagen.";
                            header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=' . $active_tab);
                            exit();
                        }
                    }

                    // Procesar Nuevo Icono
                    if (isset($_FILES['icono']) && $_FILES['icono']['error'] == 0) {
                        if ($current_icono_url && file_exists(dirname(__DIR__) . '/' . $current_icono_url)) {
                            unlink(dirname(__DIR__) . '/' . $current_icono_url);
                        }
                        $file_name_icon = uniqid() . '-icon-' . basename($_FILES["icono"]["name"]);
                        $target_file_icon = $target_directory . $file_name_icon;
                        if (move_uploaded_file($_FILES["icono"]["tmp_name"], $target_file_icon)) {
                            $icono_url_a_actualizar = $upload_dir . $file_name_icon;
                        } else {
                            $_SESSION['error_message'] = "Hubo un error al subir el nuevo icono.";
                            header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=' . $active_tab);
                            exit();
                        }
                    }

                    // Actualizar la base de datos.
                    $stmt = $pdo->prepare("UPDATE catalogos SET nombre = ?, descripcion = ?, estatus = ?, imagen_url = ?, icono_url = ? WHERE id = ?");
                    $stmt->execute([$nombre, $descripcion, $estatus, $imagen_url_a_actualizar, $icono_url_a_actualizar, $id]);

                    $_SESSION['success_message'] = "Catálogo '" . htmlspecialchars($nombre) . "' actualizado exitosamente.";
                }
                break;

            // --- ACCIÓN: ELIMINAR UN CATÁLOGO ---
            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['catalog_id'];

                    // Antes de borrar de la BD, obtener URLs para borrar archivos.
                    $stmt_select = $pdo->prepare("SELECT imagen_url, icono_url FROM catalogos WHERE id = ?");
                    $stmt_select->execute([$id]);
                    $data_to_delete = $stmt_select->fetch(PDO::FETCH_ASSOC);

                    if ($data_to_delete) {
                        if ($data_to_delete['imagen_url'] && file_exists(dirname(__DIR__) . '/' . $data_to_delete['imagen_url'])) {
                            unlink(dirname(__DIR__) . '/' . $data_to_delete['imagen_url']);
                        }
                        if ($data_to_delete['icono_url'] && file_exists(dirname(__DIR__) . '/' . $data_to_delete['icono_url'])) {
                            unlink(dirname(__DIR__) . '/' . $data_to_delete['icono_url']);
                        }
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
// --- REDIRECCIÓN ---
header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=' . (isset($active_tab) ? $active_tab : 'catalogs'));
exit();
?>