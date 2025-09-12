
<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'roots_market';
$user = 'tu_usuario';
$pass = 'tu_contraseña';

// Cadena de conexión DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Opciones de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Crear una nueva instancia de PDO y conectar a la base de datos
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Si la conexión falla, se detiene la ejecución y se muestra un mensaje de error
    // En un entorno de producción, es mejor registrar el error en lugar de mostrarlo al usuario
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>