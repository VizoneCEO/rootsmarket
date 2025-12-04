<style>
    /* ----- Estilos Generales Header ----- */
    .header-wrapper {
        position: sticky;
        top: 0;
        z-index: 1050;
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        width: 100%;
    }

    .header-main {
        background-color: #ffffff;
        padding: 1rem 2rem;
    }

    .navbar-brand img {
        height: 40px;
        /* Filtro verde Roots */
        filter: brightness(0) saturate(100%) invert(57%) sepia(78%) saturate(466%) hue-rotate(85deg) brightness(93%) contrast(95%);
    }

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

    .search-container {
        position: relative;
        width: 100%;
    }

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
        background-color: #00C853;
        border: none;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .header-icon {
        color: #00C853;
        font-size: 1.4rem;
        margin-left: 15px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .navbar-toggler {
        border: none;
        padding: 0;
        color: #333;
        font-size: 1.5rem;
        margin-left: 15px;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }

    .mobile-search-wrapper {
        background-color: #fff;
        padding: 0 1.5rem 1rem 1.5rem;
    }

    @keyframes bounceCart {
        0% {
            transform: scale(1);
        }

        30% {
            transform: scale(1.3);
            color: #2d4c48;
        }

        50% {
            transform: scale(0.9);
        }

        70% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
            color: #00C853;
        }
    }

    .cart-animating {
        animation: bounceCart 0.5s ease-in-out;
    }
</style>

<header class="header-wrapper">
    <nav class="navbar navbar-expand-lg header-main bg-white pb-2 pb-lg-3">
        <div class="container-fluid px-0">
            <a class="navbar-brand me-lg-5" href="index.php">
                <img src="front/multimedia/logo.svg" alt="Roots Logo">
            </a>

            <div class="d-flex align-items-center d-lg-none">
                <a href="front/cliente/perfil.php" class="text-decoration-none"><i
                        class="fas fa-user header-icon"></i></a>
                <a href="carrito.php" class="text-decoration-none position-relative" id="cart-icon-mobile-container">
                    <i class="fas fa-shopping-bag header-icon" id="cart-icon-mobile"></i>
                    <span
                        class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 0.6rem; display: none;">0</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"><i
                        class="fas fa-bars"></i></button>
            </div>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 main-nav-links">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="tienda.php">Categorías</a></li>
                    <li class="nav-item"><a class="nav-link" href="iniciativas.php">Iniciativas Roots</a></li>
                </ul>

                <div class="d-none d-lg-flex align-items-center w-100 justify-content-end">
                    <form class="search-container me-4" action="tienda.php" method="GET">
                        <input type="text" class="search-input" name="q"
                            placeholder="Busca productos sin químicos dañinos..."
                            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button type="submit" class="search-btn-circle"><i class="fas fa-search"></i></button>
                    </form>

                    <a href="front/cliente/perfil.php" class="text-decoration-none"><i
                            class="fas fa-user header-icon"></i></a>
                    <a href="carrito.php" class="text-decoration-none position-relative"
                        id="cart-icon-desktop-container">
                        <i class="fas fa-shopping-bag header-icon" id="cart-icon-desktop"></i>
                        <span
                            class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size: 0.6rem; display: none;">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="mobile-search-wrapper d-lg-none">
        <form class="search-container w-100" action="tienda.php" method="GET">
            <input type="text" class="search-input" name="q" placeholder="Busca productos..."
                value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit" class="search-btn-circle"><i class="fas fa-search"></i></button>
        </form>
    </div>
</header>

<div style="position: fixed; bottom: 10px; left: 20px; z-index: 1000;">
    <button class="btn rounded-pill d-flex align-items-center px-3 py-2" style="background-color:#5a9a4d; color:white;"
        data-bs-toggle="modal" data-bs-target="#puntosModal"><i class="fas fa-gift me-2"></i> Tus puntos</button>
</div>
<div style="position: fixed; bottom: 10px; right: 20px; z-index: 1000;">
    <a href="https://wa.me/524422503383" target="_blank"
        class="btn rounded-circle d-flex justify-content-center align-items-center"
        style="background-color: #25d366; color: white; width: 60px; height: 60px;"><i
            class="fab fa-whatsapp fs-4"></i></a>
</div>
<div class="modal fade" id="puntosModal" tabindex="-1" aria-labelledby="puntosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="margin-left: 10px; margin-right: auto;">
        <div class="modal-content"></div>
    </div>
</div>

<script src="front/general/cart.js"></script>