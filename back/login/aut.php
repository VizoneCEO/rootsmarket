<?php

// ---- AÑADE ESTAS LÍNEAS AL INICIO ----
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ------------------------------------

// Iniciar la sesión en la parte superior de tu script
session_start();

// El archivo db.php debe estar en el mismo directorio que aut.php para que funcione esta ruta
require_once(__DIR__ . '/../conection/db.php');
// Incluir la librería de cliente de Google para PHP.
require_once(__DIR__ . '/../../vendor/autoload.php');

// Configuración de Google (con tus credenciales)
$googleClient = new Google_Client();
$googleClient->setClientId('1034619608714-38jjhagukll7qv3us12demuf1qs0r5ma.apps.googleusercontent.com');
$googleClient->setClientSecret('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');
// La URL de redireccionamiento debe coincidir con la que configuraste en la consola de Google
$googleClient->setRedirectUri('http://localhost/rootsmarket/back/login/aut.php?action=google_callback');
$googleClient->addScope('email');
$googleClient->addScope('profile');


// --- NUEVA FUNCIÓN PARA REDIRIGIR BASADO EN EL ROL ---
function redirigirPorRol($rol) {
    switch ($rol) {
        case 'cliente':
            header('Location: ../../front/cliente/perfil.php');
            break;
        case 'administrador':
            header('Location: ../../admin/dashboard.php'); // Debes crear esta ruta
            break;
        case 'deliver':
            header('Location: ../../deliver/pedidos.php'); // Debes crear esta ruta
            break;
        case 'manager':
            header('Location: ../../manager/reportes.php'); // Debes crear esta ruta
            break;
        case 'vendedor':
            header('Location: ../../vendedor/ventas.php'); // Debes crear esta ruta
            break;
        default:
            // Si el rol es desconocido, redirige a la página principal por seguridad.
            header('Location: ../../index.php');
            break;
    }
    exit(); // Es importante terminar el script después de una redirección.
}


if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        // --- CASO 1: INICIO DE SESIÓN TRADICIONAL (MODIFICADO) ---
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (!$email || empty($password)) {
                    header('Location: ../../login.php?error=invalid_data');
                    exit();
                }

                // Query modificado para obtener el nombre del rol usando un JOIN
                $stmt = $pdo->prepare("
                    SELECT u.id, u.nombre, u.password, r.nombre_rol
                    FROM usuarios u
                    JOIN roles r ON u.rol_id = r.id
                    WHERE u.email = ?
                ");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_role'] = $user['nombre_rol']; // Guardamos el nombre del rol

                    // Usamos la nueva función para redirigir
                    redirigirPorRol($user['nombre_rol']);
                } else {
                    header('Location: ../../login.php?error=credentials');
                    exit();
                }
            }
            break;

        // --- CASO 2: INICIO DEL LOGIN CON GOOGLE (SIN CAMBIOS) ---
        case 'login_google':
            $authUrl = $googleClient->createAuthUrl();
            header('Location: ' . $authUrl);
            exit();
            break;

        // --- CASO 3: CALLBACK DE GOOGLE (MODIFICADO) ---
        case 'google_callback':
            if (isset($_GET['code'])) {
                $token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
                $googleClient->setAccessToken($token['access_token']);

                $google_oauth = new Google_Service_Oauth2($googleClient);
                $google_account_info = $google_oauth->userinfo->get();
                $email = $google_account_info->email;
                $name = $google_account_info->name;
                $google_id = $google_account_info->id;

                // Verificar si el usuario ya existe para obtener su rol
                $stmt = $pdo->prepare("
                    SELECT u.id, u.nombre, r.nombre_rol
                    FROM usuarios u
                    JOIN roles r ON u.rol_id = r.id
                    WHERE u.email = ?
                ");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // El usuario ya existe, iniciamos sesión y redirigimos según su rol
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_role'] = $user['nombre_rol'];
                    redirigirPorRol($user['nombre_rol']);
                } else {
                    // El usuario no existe, se registra como 'cliente' por defecto
                    $stmt_rol = $pdo->prepare("SELECT id FROM roles WHERE nombre_rol = 'cliente'");
                    $stmt_rol->execute();
                    $rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);

                    $stmt = $pdo->prepare("INSERT INTO usuarios (rol_id, nombre, email, google_id) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$rol['id'], $name, $email, $google_id])) {
                        $_SESSION['user_id'] = $pdo->lastInsertId();
                        $_SESSION['user_name'] = $name;
                        $_SESSION['user_role'] = 'cliente'; // Asignamos el rol por defecto

                        // Redirigimos al perfil de cliente
                        redirigirPorRol('cliente');
                    } else {
                        header('Location: ../../login.php?error=db_error');
                        exit();
                    }
                }
            } else {
                header('Location: ../../login.php?error=google_auth_failed');
                exit();
            }
            break;

        // --- CASO 4: REGISTRO DE NUEVO USUARIO (SIN CAMBIOS) ---
        case 'register':
            // Esta parte no necesita cambios, ya que los nuevos usuarios siempre serán 'cliente'
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (!$nombre || !$email || empty($password) || strlen($password) < 8) {
                    header('Location: ../../registro.php?error=invalid_data');
                    exit();
                }

                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    header('Location: ../../registro.php?error=email_exists');
                    exit();
                }

                $stmt_rol = $pdo->prepare("SELECT id FROM roles WHERE nombre_rol = 'cliente'");
                $stmt_rol->execute();
                $rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO usuarios (rol_id, nombre, email, password) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$rol['id'], $nombre, $email, $hashedPassword])) {
                    header('Location: ../../login.php?success=registered');
                    exit();
                } else {
                    header('Location: ../../registro.php?error=db_error');
                    exit();
                }
            }
            break;

        // --- CASO 5: LOGOUT (CERRAR SESIÓN) ---
        case 'logout':
            session_unset();
            session_destroy();
            header('Location: ../../index.php');
            exit();
            break;

        default:
            header('Location: ../../index.php');
            exit();
            break;
    }
} else {
    header('Location: ../../index.php');
    exit();
}