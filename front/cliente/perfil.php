<?php
// Iniciar la sesión para poder acceder a los datos del usuario
session_start();

// Si no hay una sesión de usuario iniciada, redirigir al login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

// --- LÓGICA DE BASE DE DATOS MOVIDA AQUÍ (LA PARTE QUE DABA ERROR) ---
require_once(__DIR__ . '/../../back/conection/db.php');

$userId = $_SESSION['user_id'];

// Consultar la base de datos para obtener la información completa del usuario
$stmt = $pdo->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si el usuario fue borrado de la BD pero su sesión sigue activa, lo sacamos.
if (!$user) {
    session_destroy();
    header('Location: ../../login.php?error=user_not_found');
    exit();
}
// --- FIN DE LA LÓGICA DE BASE DE DATOS ---
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { background-color: #f2ece9; }
        .text-green { color: #2d4c48; }
        .bg-light { background-color: #f8f9fa !important; }
        .shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        a.text-dark:hover { color: #2d4c48 !important; }
        .fw-bold { font-weight: bold !important; }
        .profile-menu i { font-size: 1.2rem; }
        hr { border-top: 1px solid #ccc; }
    </style>
    <title>Roots - Mi Perfil</title>
</head>
<body>

    <?php include '../general/headerb.php'; ?>

    <?php
        // Ahora incluimos el body. El body ya tendrá acceso a la variable $user que creamos arriba.
        include 'body.php';
    ?>

    <?php include '../general/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>