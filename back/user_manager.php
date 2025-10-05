<?php
session_start();

// --- CONEXIÓN A LA BASE DE DATOS ---
require_once(__DIR__ . '/conection/db.php');

// --- VERIFICACIÓN DE SEGURIDAD ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
    $_SESSION['error_message'] = "Acceso denegado. No tienes permisos para esta acción.";
    header('Location: /front/login.php'); // O a una página de error 403
    exit();
}

// --- CONTROLADOR PRINCIPAL ---
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    try {
        switch ($action) {

            // --- ACCIÓN: CREAR UN NUEVO USUARIO ---
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['rol_id'])) {
                        $_SESSION['error_message'] = "Todos los campos son obligatorios para crear un usuario.";
                    } else {
                        // Verificar si el email ya existe
                        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                        $stmt_check->execute([$_POST['email']]);
                        if ($stmt_check->fetch()) {
                            $_SESSION['error_message'] = "El correo electrónico ya está registrado.";
                        } else {
                            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$_POST['nombre'], $_POST['email'], $hashedPassword, $_POST['rol_id']]);
                            $_SESSION['success_message'] = "Usuario creado exitosamente.";
                        }
                    }
                }
                break;

            // --- ACCIÓN: ACTUALIZAR UN USUARIO ---
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['user_id']) || empty($_POST['nombre']) || empty($_POST['rol_id']) || empty($_POST['estatus'])) {
                        $_SESSION['error_message'] = "Faltan datos para actualizar el usuario.";
                    } else {
                        // Si se proporcionó una nueva contraseña, la actualizamos.
                        if (!empty($_POST['password'])) {
                            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, rol_id = ?, estatus = ?, password = ? WHERE id = ?");
                            $stmt->execute([$_POST['nombre'], $_POST['rol_id'], $_POST['estatus'], $hashedPassword, $_POST['user_id']]);
                        } else {
                            // Si no, actualizamos todo excepto la contraseña.
                            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, rol_id = ?, estatus = ? WHERE id = ?");
                            $stmt->execute([$_POST['nombre'], $_POST['rol_id'], $_POST['estatus'], $_POST['user_id']]);
                        }
                        $_SESSION['success_message'] = "Usuario actualizado exitosamente.";
                    }
                }
                break;

            // --- ACCIÓN: ELIMINAR UN USUARIO ---
            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['user_id'])) {
                        $_SESSION['error_message'] = "No se especificó el ID del usuario a eliminar.";
                    } else {
                        // Evitar que un admin se borre a sí mismo
                        if ($_POST['user_id'] == $_SESSION['user_id']) {
                            $_SESSION['error_message'] = "No puedes eliminar tu propia cuenta de administrador.";
                        } else {
                            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                            $stmt->execute([$_POST['user_id']]);
                            $_SESSION['success_message'] = "Usuario eliminado exitosamente.";
                        }
                    }
                }
                break;

            default:
                $_SESSION['error_message'] = "Acción no reconocida.";
                break;
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error en la base de datos: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "No se especificó ninguna acción.";
}

// Redirige siempre de vuelta a la página de control de usuarios.
header('Location: /front/admin/perfilAdmin.php?page=controlUsuarios');
exit();
?>