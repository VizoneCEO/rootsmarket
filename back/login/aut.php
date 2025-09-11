<?php
// Iniciar la sesión en la parte superior de tu script
session_start();

// Suponiendo que tienes un archivo de configuración para la conexión a la base de datos
// y que este crea un objeto PDO llamado $pdo.
// La ruta es relativa desde 'back/login/' hasta la raíz y luego a 'config/'.
require_once(__DIR__ . '../config/db.php');

// --- Lógica para el Login con Google ---
// Esto requiere la librería de cliente de Google para PHP.
// Ejemplo: require_once 'vendor/autoload.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        // --- CASO 1: INICIO DE SESIÓN TRADICIONAL ---
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (!$email || empty($password)) {
                    // Redirigir con error si los datos son inválidos o están vacíos
                    header('Location: ../../login.php?error=invalid_data');
                    exit();
                }

                // Buscar usuario por correo electrónico
                $stmt = $pdo->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar si el usuario existe y si la contraseña es correcta
                if ($user && password_verify($password, $user['password'])) {
                    // Inicio de sesión exitoso: guardar datos en la sesión
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];

                    // Redirigir al perfil del usuario
                    header('Location: ../../perfil.php');
                    exit();
                } else {
                    // Credenciales incorrectas
                    header('Location: ../../login.php?error=credentials');
                    exit();
                }
            }
            break;

        // --- CASO 2: INICIO DEL LOGIN CON GOOGLE ---
        case 'login_google':
            // Aquí iría la lógica para redirigir a la pantalla de autenticación de Google.
            // Es un proceso más complejo que requiere el SDK de Google.

            /*
            $googleClient = new Google_Client();
            $googleClient->setClientId('TU_CLIENT_ID_DE_GOOGLE');
            $googleClient->setClientSecret('TU_CLIENT_SECRET_DE_GOOGLE');
            $googleClient->setRedirectUri('URL_A_TU_SCRIPT_DE_CALLBACK'); // ej: https://tusitio.com/back/login/auth.php?action=google_callback
            $googleClient->addScope('email');
            $googleClient->addScope('profile');

            $authUrl = $googleClient->createAuthUrl();
            header('Location: ' . $authUrl);
            exit();
            */

            // Por ahora, solo es un marcador de posición
            echo "Redireccionando a Google... (Lógica de back-end pendiente)";
            // En la implementación real, la línea de arriba se borraría y se usaría el bloque comentado.
            break;

        // --- CASO 3: REGISTRO DE NUEVO USUARIO ---
        // (Este sería llamado desde un formulario en registro.php)
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';

                // Validación simple de datos
                if (!$nombre || !$email || empty($password)) {
                    header('Location: ../../registro.php?error=missing_data');
                    exit();
                }

                // Verificar si el correo ya existe
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    header('Location: ../../registro.php?error=email_exists');
                    exit();
                }

                // Hashear la contraseña por seguridad
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insertar el nuevo usuario en la base de datos
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$nombre, $email, $hashedPassword])) {
                    // Registro exitoso, redirigir al login para que inicie sesión
                    header('Location: ../../login.php?success=registered');
                    exit();
                } else {
                    // Error al crear el usuario
                    header('Location: ../../registro.php?error=db_error');
                    exit();
                }
            }
            break;

        // --- CASO 4: LOGOUT (CERRAR SESIÓN) ---
        case 'logout':
            session_unset();    // Libera todas las variables de sesión
            session_destroy();  // Destruye la sesión
            header('Location: ../../index.php'); // Redirige a la página principal
            exit();
            break;

        default:
            // Si la acción no es reconocida, redirigir a la página principal
            header('Location: ../../index.php');
            exit();
            break;
    }
} else {
    // Si no se especifica ninguna acción, redirigir a la página principal
    header('Location: ../../index.php');
    exit();
}
?>