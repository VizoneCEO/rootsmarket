<?php
// Iniciar la sesión en la parte superior de tu script
session_start();

// El archivo db.php debe estar en el mismo directorio que aut.php para que funcione esta ruta
require_once(__DIR__ . '/db.php');

// Incluir la librería de cliente de Google para PHP.
// Es un proceso más complejo que requiere el SDK de Google, instalado vía Composer.
require_once 'vendor/autoload.php';

// Configuración de Google
$googleClient = new Google_Client();
$googleClient->setClientId('TU_CLIENT_ID_DE_GOOGLE');
$googleClient->setClientSecret('TU_CLIENT_SECRET_DE_GOOGLE');
// La URL de redireccionamiento debe coincidir con la que configuraste en la consola de Google
$googleClient->setRedirectUri('http://localhost/roots/back/login/auth.php?action=google_callback');
$googleClient->addScope('email');
$googleClient->addScope('profile');


if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        // --- CASO 1: INICIO DE SESIÓN TRADICIONAL ---
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (!$email || empty($password)) {
                    header('Location: ../../login.php?error=invalid_data');
                    exit();
                }

                $stmt = $pdo->prepare("SELECT id, nombre, password, rol_id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_role'] = $user['rol_id'];

                    // Redirigir al perfil del usuario
                    header('Location: ../../perfil.php');
                    exit();
                } else {
                    header('Location: ../../login.php?error=credentials');
                    exit();
                }
            }
            break;

        // --- CASO 2: INICIO DEL LOGIN CON GOOGLE ---
        case 'login_google':
            $authUrl = $googleClient->createAuthUrl();
            header('Location: ' . $authUrl);
            exit();
            break;

        // --- CASO 3: CALLBACK DE GOOGLE ---
        case 'google_callback':
            if (isset($_GET['code'])) {
                $token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
                $googleClient->setAccessToken($token['access_token']);

                $google_oauth = new Google_Service_Oauth2($googleClient);
                $google_account_info = $google_oauth->userinfo->get();
                $email = $google_account_info->email;
                $name = $google_account_info->name;
                $google_id = $google_account_info->id;

                // Verificar si el usuario ya existe en la base de datos
                $stmt = $pdo->prepare("SELECT id, nombre FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // El usuario existe, iniciar sesión
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    header('Location: ../../perfil.php');
                    exit();
                } else {
                    // El usuario no existe, registrar uno nuevo
                    $stmt_rol = $pdo->prepare("SELECT id FROM roles WHERE nombre_rol = 'cliente'");
                    $stmt_rol->execute();
                    $rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);

                    $stmt = $pdo->prepare("INSERT INTO usuarios (rol_id, nombre, email, google_id) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$rol['id'], $name, $email, $google_id])) {
                        $_SESSION['user_id'] = $pdo->lastInsertId();
                        $_SESSION['user_name'] = $name;
                        header('Location: ../../perfil.php');
                        exit();
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

        // --- CASO 4: REGISTRO DE NUEVO USUARIO ---
        case 'register':
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