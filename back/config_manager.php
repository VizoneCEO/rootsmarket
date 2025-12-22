<?php
session_start();
require_once(__DIR__ . '/conection/db.php');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
    http_response_code(403);
    echo "Acceso denegado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Save Season Name
        if (isset($_POST['nombre_temporada'])) {
            $stmt = $pdo->prepare("INSERT INTO configuracion (clave, valor) VALUES ('nombre_temporada', ?) ON DUPLICATE KEY UPDATE valor = ?");
            $stmt->execute([$_POST['nombre_temporada'], $_POST['nombre_temporada']]);
        }

        // Save Season Image
        if (isset($_FILES['imagen_temporada']) && $_FILES['imagen_temporada']['error'] == 0) {
            $upload_dir = 'front/multimedia/'; // Relative path for DB
            // We need to go up from back/ to root, then to front/multimedia
            $target_directory = dirname(__DIR__) . '/' . $upload_dir;

            if (!file_exists($target_directory)) {
                mkdir($target_directory, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['imagen_temporada']['name'], PATHINFO_EXTENSION));
            $file_name = 'temporada_' . uniqid() . '.' . $ext;
            $target_file = $target_directory . $file_name;

            if (move_uploaded_file($_FILES['imagen_temporada']['tmp_name'], $target_file)) {
                $imagen_url = $upload_dir . $file_name;
                $stmt = $pdo->prepare("INSERT INTO configuracion (clave, valor) VALUES ('imagen_temporada', ?) ON DUPLICATE KEY UPDATE valor = ?");
                $stmt->execute([$imagen_url, $imagen_url]);
            }
        }

        $pdo->commit();
        $_SESSION['success_message'] = "Configuración actualizada correctamente.";

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error_message'] = "Error al guardar configuración: " . $e->getMessage();
        error_log("Upload Error: " . $e->getMessage());
    }

    // Check for upload errors if no exception was thrown but file was present
    if (isset($_FILES['imagen_temporada']) && $_FILES['imagen_temporada']['error'] != 0 && $_FILES['imagen_temporada']['error'] != UPLOAD_ERR_NO_FILE) {
        // Only overwrite success message if there was an error
        if (!isset($_SESSION['error_message'])) {
            $_SESSION['error_message'] = "File upload warning/error code: " . $_FILES['imagen_temporada']['error'];
        }
        error_log("File upload error code: " . $_FILES['imagen_temporada']['error']);
    }

    // Redirect back to the configuration tab
    header('Location: ../front/admin/perfilAdmin.php?page=configuracion&tab=temporada');
    exit();
}
?>