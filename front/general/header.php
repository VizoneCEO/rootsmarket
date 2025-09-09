<style>
    /* Barra Superior */
    .top-bar {
        background-color: #f2ece9;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .navbar-brand img {
        height: 45px;
    }

    /* Barra de Búsqueda */
    .search-bar-container {
        flex-grow: 1;
        margin: 0 2rem;
    }
    .search-bar {
        border-radius: 25px;
        border: 1px solid #e0e0e0;
        background-color: #f5f5f5;
        padding: 0.5rem 1rem;
        width: 100%;
    }

    /* Botón Log-in y Iconos */
    .header-actions .btn-login {
        background-color: #4EAE3E;
        color: white;
        border-radius: 20px;
        padding: 8px 25px;
        font-weight: bold;
        border: none;
        white-space: nowrap; /* Evita que el texto se rompa */
    }
    .header-actions .btn-login:hover {
        background-color: #4EAE3E;
    }
    .header-actions i {
        font-size: 1.5rem;
        color: #2d4c48;
        margin-left: 15px;
    }

    /* Barra de Navegación Verde */
    .green-nav {
        background-color: #4EAE3E;
        padding: 10px 0;
    }
    .green-nav .nav-link {
        color: white;
        font-weight: bold;
        margin: 0 20px;
        font-size: 1.1rem;
    }

    /* Ajustes para el Menú Móvil */
    @media (max-width: 991.98px) {
        .search-bar-container {
            margin: 1rem 0; /* Espacio para la barra de búsqueda en móvil */
        }
        .header-actions {
            justify-content: center; /* Centrar botón de login en móvil */
        }
        .navbar-collapse {
            padding-top: 1rem; /* Espacio superior en el menú desplegado */
        }
    }
</style>

<header>
    <nav class="navbar navbar-expand-lg top-bar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="front/multimedia/logo.svg" alt="Roots Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="search-bar-container">
                    <form class="d-flex">
                        <input class="form-control search-bar" type="search" placeholder="Buscar" aria-label="Buscar">
                    </form>
                </div>

                <div class="header-actions d-flex align-items-center">
                    <a href="login.php"><button class="btn btn-login">Log-in</button></a>
                    <a href="perfil.php"><i class="fas fa-user d-none d-lg-inline-flex"></i></a>
                    <a href="producto.php"><i class="fas fa-shopping-cart d-none d-lg-inline-flex"></i></a>
                </div>

                <ul class="navbar-nav d-lg-none text-center mt-3 border-top pt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="tienda.php">Tienda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nosotros.php">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recetas.php">Recetario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <nav class="green-nav d-none d-lg-block">
        <div class="container">
            <ul class="navbar-nav d-flex flex-row justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="tienda.php">Tienda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="nosotros.php">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="recetas.php">Recetario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contacto">Contacto</a>
                </li>
            </ul>
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
