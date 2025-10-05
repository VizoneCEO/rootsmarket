<?php
$currentPage = $_GET['page'] ?? 'dashboard';
?>

<nav class="sidebar">
    <div class="sidebar-header">
        <a href="../../index.php" class="d-flex align-items-center text-white text-decoration-none">
            <img src="../front/multimedia/logo.svg" alt="Roots Logo" style="height: 40px; filter: brightness(0) invert(1);">
            <h4 class="ms-2 mb-0">Roots Admin</h4>
        </a>
    </div>

    <ul class="nav flex-column mt-3">
        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>" href="perfilAdmin.php?page=dashboard">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'usuarios') ? 'active' : ''; ?>" href="perfilAdmin.php?page=usuarios">
                <i class="fas fa-users me-2"></i> Control de Usuarios
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'stock') ? 'active' : ''; ?>" href="perfilAdmin.php?page=stock">
                <i class="fas fa-boxes-stacked me-2"></i> Control de Stock
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'ventas') ? 'active' : ''; ?>" href="perfilAdmin.php?page=ventas">
                <i class="fas fa-chart-line me-2"></i> Control de Ventas
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <ul class="nav flex-column">
             <li class="nav-item">
                <a class="nav-link <?php echo ($currentPage === 'configuracion') ? 'active' : ''; ?>" href="perfilAdmin.php?page=configuracion">
                    <i class="fas fa-cog me-2"></i> Configuración
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../back/login/aut.php?action=logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</nav>