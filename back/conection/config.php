<?php
// Es crucial iniciar la sesión al principio de cualquier script que la vaya a utilizar.
session_start();

// Incluimos el archivo de configuración de la base de datos.
require_once(__DIR__ . '../conection/config.php');

// --- Controlador Principal de Autenticación ---

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {

        // --- CASO 1: INICIO DE SESIÓN CON CORREO Y CONTRASEÑA ---
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (!$email || empty($password)) {
                    header('Location: ../../login.php?error=invalid_data');
                    exit();
                }

                $stmt = $conn->prepare(
                    "SELECT u.id, u.nombre, u.password, u.estatus, r.nombre_rol 
                     FROM usuarios u
                     JOIN roles r ON u.rol_id = r.id
                     WHERE u.email = ?"
                );
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {

                    if ($user['estatus'] !== 'activo') {
                        header('Location: ../../login.php?error=account_inactive');
                        exit();
                    }

                    // Inicio de sesión exitoso: guardamos datos en la sesión.
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_role'] = $user['nombre_rol'];

                    // --- NUEVO: SWITCH PARA REDIRECCIÓN POR ROL ---
                    $role = $user['nombre_rol'];
                    $redirectPath = '';

                    switch ($role) {
                        case 'administrador':
                            // Asumimos que la ruta para el admin es /admin/dashboard.php
                            $redirectPath = '../../admin/dashboard.php';
                            break;
                        case 'manager':
                            // Asumimos ruta para manager
                            $redirectPath = '../../manager/dashboard.php';
                            break;
                        case 'vendedor':
                            // Asumimos ruta para vendedor
                            $redirectPath = '../../vendedor/dashboard.php';
                            break;
                        case 'deliver':
                            // Asumimos ruta para deliver
                            $redirectPath = '../../deliver/dashboard.php';
                            break;
                        case 'cliente':
                            // El cliente va a su perfil normal.
                            $redirectPath = '../../perfil.php';
                            break;
                        default:
                            // Si por alguna razón el rol no coincide, lo mandamos a la página principal.
                            $redirectPath = '../../index.php';
                            break;
                    }

                    header('Location: ' . $redirectPath);
                    exit();

                } else {
                    header('Location: ../../login.php?error=credentials');
                    exit();
                }
            }
            break;

        // --- CASO 2: REGISTRO DE NUEVO USUARIO ---
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (empty($nombre) || !$email || empty($password) || strlen($password) < 8) {
                    header('Location: ../../registro.php?error=invalid_data');
                    exit();
                }

                $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    header('Location: ../../registro.php?error=email_exists');
                    exit();
                }

                $stmt_rol = $conn->prepare("SELECT id FROM roles WHERE nombre_rol = 'cliente'");
                $stmt_rol->execute();
                $rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);

                if (!$rol) {
                    header('Location: ../../registro.php?error=default_role_not_found');
                    exit();
                }

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare("INSERT INTO usuarios (rol_id, nombre, email, password) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$rol['id'], $nombre, $email, $hashedPassword])) {
                    header('Location: ../../login.php?success=registered');
                    exit();
                } else {
                    header('Location: ../../registro.php?error=db_error');
                    exit();
                }
            }
            break;

        // --- CASO 3: CERRAR SESIÓN ---
        case 'logout':
            session_unset();
            session_destroy();
            header('Location: ../../index.php');
            exit();
            break;

        // --- CASO 4: INICIAR PROCESO DE LOGIN CON GOOGLE ---
        case 'login_google':
            die("Funcionalidad de login con Google en desarrollo.");
            break;

        default:
            header('Location: ../../index.php');
            exit();
    }
} else {
    header('Location: ../../index.php');
    exit();
}
?>