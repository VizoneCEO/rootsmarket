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

    /* --- MOBILE SPECIFIC STYLES --- */
    .mobile-top-bar {
        position: absolute;
        /* Overlay hero */
        top: 0;
        left: 0;
        width: 100%;
        padding: 40px 20px 20px 20px;
        /* Safe area top padding */
        display: flex;
        flex-direction: column;
        /* Stack Label and Row */
        align-items: flex-start;
        gap: 5px;
        background: transparent;
        z-index: 1060;
    }

    .entrega-label {
        color: white;
        font-size: 0.9rem;
        font-weight: 600;
        margin-left: 5px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    /* New Row container for Pill and Bell */
    .header-row-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        flex-wrap: nowrap;
    }

    .location-pill {
        background-color: white;
        padding: 8px 15px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        font-weight: 700;
        color: #333;
        font-size: 1rem;
        /* Ensure it takes available space but leaves room for bell */
        flex-grow: 1;
        margin-right: 15px;
        /* Separation requested */
        /* max-width: calc(100% - 60px); remove fixed max width to allow flex to handle */
        min-width: 0;
        /* Important for flex child truncation */
    }

    .location-pill i.fa-map-marker-alt {
        color: #4EAE3E;
        margin-right: 8px;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .location-pill i.fa-chevron-down {
        color: #333;
        margin-left: 8px;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* Text truncation for long addresses */
    .location-text-content {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-grow: 1;
    }

    .notification-circle {
        background-color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
        flex-shrink: 0;
        /* Never shrink */
    }

    .notification-circle i {
        font-size: 1.2rem;
        color: #4EAE3E;
    }

    .notification-dot {
        position: absolute;
        top: 0px;
        right: 0px;
        width: 10px;
        height: 10px;
        background-color: #FF5722;
        border-radius: 50%;
        border: 2px solid white;
    }

    /* Bottom Navigation */
    .bottom-nav {
        background-color: #fff;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        height: 70px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 0 10px;
        z-index: 1060;
    }

    .nav-item-mobile {
        text-decoration: none;
        color: #999;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.7rem;
        flex: 1;
    }

    .nav-item-mobile i {
        font-size: 1.3rem;
        margin-bottom: 4px;
    }

    .nav-item-mobile.active {
        color: #4EAE3E;
    }

    .cart-float-btn {
        background-color: #4EAE3E;
        color: white;
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: -30px;
        /* Make it float */
        box-shadow: 0 4px 10px rgba(78, 174, 62, 0.4);
        position: relative;
    }

    .cart-float-btn i {
        font-size: 1.4rem;
        color: white;
    }

    .cart-count-float {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #FF5722;
        color: white;
        font-size: 0.7rem;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- MOBILE TOP BAR (d-block d-lg-none) -->
<?php if (basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
    <div class="mobile-top-bar d-block d-lg-none">
        <div class="entrega-label">Entrega</div>

        <div class="header-row-bottom">
            <div class="location-pill">
                <i class="fas fa-map-marker-alt"></i>
                <span class="location-text-content">Casa de Luis</span>
                <i class="fas fa-chevron-down"></i>
            </div>

            <div class="notification-circle">
                <i class="fas fa-bell"></i>
                <!-- <div class="notification-dot"></div> -->
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- EXISTING HEADER (Desktop Only: d-none d-lg-block) -->
<header class="header-wrapper d-none d-lg-block">
    <nav class="navbar navbar-expand-lg header-main bg-white pb-2 pb-lg-3">
        <div class="container-fluid px-0">
            <a class="navbar-brand me-lg-5" href="index.php">
                <img src="front/multimedia/logo.svg" alt="Roots Logo">
            </a>

            <div class="d-flex align-items-center d-lg-none">
                <!-- Hidden on mobile -->
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
</header>

<!-- MOBILE BOTTOM NAV (Fixed Bottom) -->
<div class="bottom-nav fixed-bottom d-flex d-lg-none">
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
    <a href="index.php" class="nav-item-mobile <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="tienda.php" class="nav-item-mobile <?php echo ($current_page == 'tienda.php') ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i>
        <span>Categorías</span>
    </a>
    <div class="nav-item-mobile">
        <a href="carrito.php" class="cart-float-btn">
            <i class="fas fa-shopping-bag" id="cart-icon-mobile"></i>
            <span class="cart-badge cart-count-float" style="display: none;">0</span>
        </a>
    </div>
    <a href="pedidos.php" class="nav-item-mobile <?php echo ($current_page == 'pedidos.php') ? 'active' : ''; ?>">
        <i class="fas fa-file-alt"></i>
        <span>Pedidos</span>
    </a>
    <a href="front/cliente/perfil.php"
        class="nav-item-mobile <?php echo ($current_page == 'perfil.php') ? 'active' : ''; ?>">
        <i class="far fa-user"></i>
        <span>Perfil</span>
    </a>
</div>


<div class="floating-btns-mobile-adjust" style="position: fixed; bottom: 10px; left: 20px; z-index: 1000;">
    <button class="btn rounded-pill d-flex align-items-center px-3 py-2" style="background-color:#5a9a4d; color:white;"
        data-bs-toggle="modal" data-bs-target="#puntosModal"><i class="fas fa-gift me-2"></i> Tus puntos</button>
</div>
<div class="floating-btns-mobile-adjust" style="position: fixed; bottom: 10px; right: 20px; z-index: 1000;">
    <a href="https://wa.me/524422503383" target="_blank"
        class="btn rounded-circle d-flex justify-content-center align-items-center"
        style="background-color: #25d366; color: white; width: 60px; height: 60px;"><i
            class="fab fa-whatsapp fs-4"></i></a>
</div>

<style>
    @media (max-width: 991px) {
        .floating-btns-mobile-adjust {
            bottom: 80px !important;
        }
    }
</style>

<div class="modal fade" id="puntosModal" tabindex="-1" aria-labelledby="puntosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="margin-left: 10px; margin-right: auto;">
        <div class="modal-content"></div>
    </div>
</div>

<script src="front/general/cart.js"></script>