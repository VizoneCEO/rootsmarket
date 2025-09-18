<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrador') {
    header('Location: ../login.php');
    exit();
}

// --- LÓGICA PARA CARGAR PÁGINAS DINÁMICAMENTE ---

// 1. Añadimos la nueva página a la lista de páginas permitidas
$allowedPages = [
    'dashboard' => 'body.php',
    'usuarios' => 'controlUsuarios.php',
    'stock' => 'controlStock.php',
    'ventas' => 'controlVentas.php',
    'configuracion' => 'controlConfiguracion.php' // <-- NUEVA LÍNEA
];

// 2. Obtenemos la página solicitada de la URL.
$requestedPage = $_GET['page'] ?? 'dashboard';

// 3. Verificamos si la página solicitada es válida.
if (array_key_exists($requestedPage, $allowedPages)) {
    $pageToInclude = $allowedPages[$requestedPage];
} else {
    $pageToInclude = $allowedPages['dashboard'];
}

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale-1">
    <title>Admin Dashboard - Roots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* (Tus estilos de siempre van aquí) */
        body { background-color: #f8f9fa; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 250px; padding-top: 1rem; background-color: #2d4c48; color: white; z-index: 100; }
        .main-content { margin-left: 250px; padding: 2rem; }
        .sidebar .nav-link { color: #e0e0e0; font-size: 1.1rem; padding: 0.8rem 1.5rem; transition: all 0.3s ease; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #4EAE3E; color: white; border-left: 5px solid #ffffff; padding-left: calc(1.5rem - 5px); }
        .sidebar .sidebar-header { padding: 0 1.5rem 1rem 1.5rem; border-bottom: 1px solid #4a6b67; }
        .sidebar .sidebar-footer { position: absolute; bottom: 0; width: 100%; padding-bottom: 1rem; }
        .card-icon { font-size: 3rem; opacity: 0.3; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <?php
            if (file_exists($pageToInclude)) {
                include $pageToInclude;
            } else {
                echo "<h1>Error: El archivo de contenido no se encontró.</h1>";
            }
        ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>