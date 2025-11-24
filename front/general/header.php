<style>
    /* ----- Estilos Generales Header ----- */
    .header-main {
        background-color: #ffffff;
        padding: 1rem 2rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .navbar-brand img {
        height: 40px;
    }

    /* Enlaces de Navegación */
    .main-nav-links .nav-link {
        color: #000;
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0 1rem;
        transition: color 0.3s ease;
    }

    .main-nav-links .nav-link:hover,
    .main-nav-links .nav-link.active {
        color: #4EAE3E;
    }

    /* Estilos de la Barra de Búsqueda */
    .search-container {
        position: relative;
        width: 100%;
    }

    /* Ancho específico para escritorio */
    @media (min-width: 992px) {
        .search-container {
            max-width: 400px;
        }
    }

    .search-input {
        background-color: #EFEFEF;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        padding-right: 50px;
        width: 100%;
        outline: none;
        color: #666;
    }

    .search-btn-circle {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 35px;
        height: 35px;
        background-color: #00C853; /* Verde brillante */
        border: none;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* Iconos de usuario y carrito */
    .header-icon {
        color: #00C853;
        font-size: 1.4rem; /* Un poco más grandes para móvil */
        margin-left: 15px;
        cursor: pointer;
    }

    /* Ajustes para el botón hamburguesa */
    .navbar-toggler {
        border: none;
        padding: 0;
        color: #333; /* Color de las líneas */
        font-size: 1.5rem;
        margin-left: 15px;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }

    /* Contenedor de búsqueda móvil (Debajo del header) */
    .mobile-search-wrapper {
        background-color: #fff;
        padding: 0 1.5rem 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
</style>

<header>
    <nav class="navbar navbar-expand-lg header-main bg-white pb-2 pb-lg-3">
        <div class="container-fluid px-0">
            <a class="navbar-brand me-lg-5" href="index.php">
                <img src="front/multimedia/logo.svg" alt="Roots Logo">
            </a>

            <div class="d-flex align-items-center d-lg-none">
                <a href="front/cliente/perfil.php" class="text-decoration-none">
                    <i class="fas fa-user header-icon"></i>
                </a>
                <a href="carrito.php" class="text-decoration-none position-relative">
                    <i class="fas fa-shopping-bag header-icon"></i>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 main-nav-links">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="tienda.php">Categorías</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#iniciativas">Iniciativas Roots</a></li>
                    <li class="nav-item"><a class="nav-link" href="nosotros.php">Nosotros</a></li>
                </ul>

                <div class="d-none d-lg-flex align-items-center w-100 justify-content-end">
                    <form class="search-container me-4">
                        <input type="text" class="search-input" placeholder="Busca productos sin químicos dañinos...">
                        <button type="submit" class="search-btn-circle">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <a href="front/cliente/perfil.php" class="text-decoration-none">
                        <i class="fas fa-user header-icon"></i>
                    </a>
                    <a href="carrito.php" class="text-decoration-none position-relative">
                        <i class="fas fa-shopping-bag header-icon"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="mobile-search-wrapper d-lg-none">
        <form class="search-container w-100">
            <input type="text" class="search-input" placeholder="Busca productos...">
            <button type="submit" class="search-btn-circle">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
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
        <div class="modal-content"></div>
    </div>
</div>