<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario inició sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php?error=auth_required');
    exit();
}

// Roles permitidos por defecto: solo administrador
if (!isset($allowed_roles)) {
    $allowed_roles = ['administrador'];
}

// Verifica si el rol del usuario está en la lista de permitidos
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    // Si no tiene permiso, intenta redirigir a una página segura según su rol o al inicio
    header('Location: ../../index.php?error=access_denied');
    exit();
}
?>