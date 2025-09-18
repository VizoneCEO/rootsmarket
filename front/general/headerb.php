<style>
    /* ----- Contenedor Principal del Header ----- */
    .header-main {
        background-color: #ffffff;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid #f0f0f0;
    }

    .navbar-brand img {
        height: 45px;
    }

    /* ----- Barra de Búsqueda Mejorada ----- */
    .search-bar-container {
        flex-grow: 1;
        margin: 0 1.5rem;
    }
    .search-bar {
        border-radius: 50px;
        border: 1px solid #e0e0e0;
        background-color: #f7f7f7;
        padding: 0.6rem 1.2rem;
        width: 100%;
        transition: all 0.3s ease;
    }
    .search-bar:focus {
        background-color: #fff;
        border-color: #4EAE3E;
        box-shadow: 0 0 0 2px rgba(78, 174, 62, 0.2);
    }

    /* ----- Enlaces de Navegación Principales ----- */
    .main-nav-links .nav-link {
        color: #2d4c48;
        font-weight: 600;
        font-size: 1rem;
        margin: 0 1rem;
        padding-bottom: 5px;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .main-nav-links .nav-link:hover,
    .main-nav-links .nav-link.active {
        color: #4EAE3E;
        border-bottom-color: #4EAE3E;
    }

    /* ----- Acciones del Header (Login e Iconos) ----- */
    .header-actions .btn-login {
        background-color: #4EAE3E;
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: bold;
        border: none;
        white-space: nowrap;
        transition: background-color 0.3s ease;
    }
    .header-actions .btn-login:hover {
        background-color: #3e8c32;
    }
    .header-actions .header-icon {
        font-size: 1.5rem;
        color: #2d4c48;
        margin-left: 1.2rem;
        transition: color 0.3s ease;
    }
    .header-actions .header-icon:hover {
        color: #4EAE3E;
    }

    /* ----- Corrección Ícono Hamburguesa ----- */
    .navbar-toggler {
        border: none;
    }
    .navbar-toggler:focus {
        box-shadow: none;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(45, 76, 72, 0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* ----- Ajustes para Móvil ----- */
    @media (max-width: 991.98px) {
        .main-nav-links {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }
        .search-bar-container {
            margin: 1rem 0;
        }
        .header-actions {
            justify-content: center;
            margin-top: 1rem;
        }
    }
</style>

<header>
    <nav class="navbar navbar-expand-lg header-main">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">
                <img src="../../front/multimedia/logo.svg" alt="Roots Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav main-nav-links mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="../../tienda.php">Tienda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../nosotros.php">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../recetas.php">Recetario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php#contacto">Contacto</a>
                    </li>
                </ul>

                <div class="search-bar-container">
                    <form class="d-flex">
                        <input class="form-control search-bar" type="search" placeholder="Buscar en Roots..." aria-label="Buscar">
                    </form>
                </div>

                <div class="header-actions d-flex align-items-center ms-lg-3">
                    <a href="../../login.php" class="text-decoration-none"><button class="btn btn-login">Log-in</button></a>
                    <a href="../../front/cliente/perfil.php"><i class="fas fa-user header-icon d-none d-lg-inline-flex"></i></a>
                    <a href="../../producto.php"><i class="fas fa-shopping-cart header-icon d-none d-lg-inline-flex"></i></a>
                </div>
            </div>
        </div>
    </nav>
</header>


<div style="position: fixed; bottom: 10px; left: 20px; z-index: 1000;">
    <button class="btn rounded-pill d-flex align-items-center px-3 py-2" style="background-color:#5a9a4d; color:white;" data-bs-toggle="modal" data-bs-target="#puntosModal">
        <i class="fas fa-gift me-2"></i> Tus puntos
    </button>
</div>
<div style="position: fixed; bottom: 10px; right: 20px; z-index: 1000;">
    <a href="https://wa.me/524422503383" target="_blank" class="btn rounded-circle d-flex justify-content-center align-items-center" style="background-color: #25d366; color: white; width: 60px; height: 60px;">
        <i class="fab fa-whatsapp fs-4"></i>
    </a>
</div>
<div class="modal fade" id="puntosModal" tabindex="-1" aria-labelledby="puntosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="margin-left: 10px; margin-right: auto;">
        <div class="modal-content">
            </div>
    </div>
</div>