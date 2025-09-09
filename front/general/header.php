<style>
    /* Botón flotante */
    .btn-green {
        background-color: #2c7a2c;
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-green:hover {
        background-color: #226622;
        transform: scale(1.05);
    }

    .btn-whatsapp {
        background-color: #25d366;
        color: white;
        width: 60px;
        height: 60px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-whatsapp:hover {
        background-color: #20b857;
        transform: scale(1.1);
    }

    /* Modal personalizado */
    .modal-left {
        max-width: 350px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .modal-header.bg-green {
        background-color: #2c7a2c;
    }

    .text-green {
        color: #2c7a2c;
    }

    .list-group-item {
        font-size: 1rem;
        padding: 0.5rem 0;
    }

    /* Botón Utilizar esto */
    .btn-success {
        font-size: 1.25rem;
        font-weight: bold;
    }

</style>

<!-- Header Principal -->
<nav class="navbar navbar-expand-lg py-3">
    <div class="container">
        <!-- Barra de Búsqueda (versión móvil: ocupa toda la pantalla) -->
        <form class="d-flex w-100 mb-2 d-lg-none">
            <input class="form-control search-bar w-100" type="search" placeholder="Buscar Productos" aria-label="Buscar">
        </form>

        <!-- Logo (centrado en versión móvil) -->
        <a class="navbar-brand mx-auto d-lg-none" href="index.php">
            <img src="front/multimedia/logo.svg" alt="Roots Logo">
        </a>

        <!-- Sección Derecha: Iconos (móvil: alineados a la derecha) -->
        <div class="d-flex align-items-center nav-icons ms-auto d-lg-none">
            <a href="perfil.php"><i class="fas fa-user me-3"></i></a>
            <a href="producto.php"><i class="fas fa-shopping-cart"></i></a>
        </div>

        <!-- Barra de Búsqueda (versión de escritorio) -->
        <form class="d-flex me-3 d-none d-lg-block">
            <input class="form-control search-bar" type="search" placeholder="Buscar Productos" aria-label="Buscar">
        </form>

        <!-- Logo (versión de escritorio) -->
        <a class="navbar-brand mx-auto d-none d-lg-block" href="index.php">
            <img src="front/multimedia/logo.svg" alt="Roots Logo">
        </a>

        <!-- Sección Derecha: Botón de Login y Iconos (versión de escritorio) -->
        <div class="d-flex align-items-center nav-icons d-none d-lg-flex">
            <a href="perfil.php"><button class="btn btn-login me-2">LOG-IN</button></a>
            <a href="perfil.php"><i class="fas fa-user me-3"></i></a>
            <a href="producto.php"><i class="fas fa-shopping-cart"></i></a>
        </div>

        <!-- Botón de Hamburguesa para Móvil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Menú de Navegación -->
<div class="nav-links">
    <div class="container">
        <div class="collapse navbar-collapse show" id="navbarNav">
            <ul class="navbar-nav mx-auto d-flex flex-row justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="nosotros.php">NOSOTROS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tienda.php">TIENDA</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contacto">CONTACTO</a>
                </li>
            </ul>
        </div>
    </div>
</div>


<!-- Botón flotante de Puntos -->
<div style="position: fixed; bottom: 10px; left: 20px; z-index: 1000;">
    <button class="btn btn-green rounded-pill d-flex align-items-center px-3 py-2" data-bs-toggle="modal" data-bs-target="#puntosModal">
        <i class="fas fa-gift me-2"></i> Tus puntos
    </button>
    <!-- Botón flotante de WhatsApp -->
    <div style="position: fixed; bottom: 10px; right: 20px; z-index: 1000;">
        <a href="https://wa.me/524422503383" target="_blank" class="btn btn-whatsapp rounded-circle d-flex justify-content-center align-items-center">
            <i class="fab fa-whatsapp fs-4"></i>
        </a>
    </div>
</div>

<!-- Modal de Puntos -->
<div class="modal fade" id="puntosModal" tabindex="-1" aria-labelledby="puntosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="margin-left: 10px; margin-right: auto;">
        <div class="modal-content modal-left">
            <div class="modal-header bg-green text-white">
                <h5 class="modal-title" id="puntosModalLabel">Tus puntos Roots</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Puntos disponibles -->
                <div class="text-center mb-4">
                    <h2 class="text-green fw-bold mb-2">150 puntos</h2>
                    <button class="btn btn-success btn-lg">Utilizar esto</button>
                </div>

                <!-- Progreso del árbol -->
                <p class="fw-bold mb-2">Progreso para plantar un árbol</p>
                <div class="progress mb-4" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        25%
                    </div>
                </div>
                <p class="text-muted">¡Sigue comprando para llegar al 100% y plantar tu árbol!</p>

                <!-- Logros ambientales -->
                <hr>
                <h6 class="fw-bold mb-3">Tus logros ambientales</h6>
                <ul class="list-group mb-4">
                    <li class="list-group-item border-0">
                        <i class="fas fa-leaf text-success me-2"></i> Reduciste <strong>5 kg</strong> de emisiones de carbono.
                    </li>
                    <li class="list-group-item border-0">
                        <i class="fas fa-recycle text-success me-2"></i> Contribuiste al reciclaje de <strong>2 kg</strong> de residuos.
                    </li>
                </ul>

                <!-- Recetas -->
                <hr>
                <h6 class="fw-bold mb-3">Recetas recomendadas</h6>
                <ul class="list-group">
                    <li class="list-group-item border-0">
                        <i class="fas fa-utensils text-green me-2"></i>
                        <a href="#" class="text-green">Receta de Smoothie Verde</a>
                    </li>
                    <li class="list-group-item border-0">
                        <i class="fas fa-utensils text-green me-2"></i>
                        <a href="#" class="text-green">Receta de Ensalada Orgánica</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

