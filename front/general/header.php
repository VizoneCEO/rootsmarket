<?php
if (!isset($base_url)) {
    $base_url = './';
}
?>
<style>
    /* ----- Estilos Generales Header ----- */
    .header-wrapper {
        position: sticky;
        top: 0;
        z-index: 1050;
        background-color: var(--color-bg-white);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        width: 100%;
    }

    .header-main {
        background-color: var(--color-bg-white);
        padding: 1rem 2rem;
    }

    .navbar-brand img {
        height: 40px;
        /* Filtro verde Roots - Matches brand primary */
        filter: brightness(0) saturate(100%) invert(57%) sepia(78%) saturate(466%) hue-rotate(85deg) brightness(93%) contrast(95%);
    }

    .main-nav-links .nav-link {
        color: var(--color-text-main);
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0 1rem;
        transition: color 0.3s ease;
    }

    .main-nav-links .nav-link:hover,
    .main-nav-links .nav-link.active {
        color: var(--color-primary);
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
        background-color: var(--color-primary);
        border: none;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .header-icon {
        color: var(--color-primary);
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
            color: var(--color-secondary);
        }

        50% {
            transform: scale(0.9);
        }

        70% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
            color: var(--color-primary);
        }
    }

    .cart-animating {
        animation: bounceCart 0.5s ease-in-out;
    }

    /* --- MOBILE SPECIFIC STYLES --- */
    .mobile-top-bar {
        position: relative;
        top: 0;
        left: 0;
        width: 100%;
        padding: 40px 20px 20px 20px;
        display: flex;
        flex-direction: column;
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
        flex-grow: 1;
        margin-right: 15px;
        min-width: 0;
        position: relative;
        z-index: 20;
    }

    .location-pill i.fa-map-marker-alt {
        color: var(--color-primary);
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
    }

    .notification-circle i {
        font-size: 1.2rem;
        color: var(--color-primary);
    }

    .notification-dot {
        position: absolute;
        top: 0px;
        right: 0px;
        width: 10px;
        height: 10px;
        background-color: var(--color-accent);
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
        color: var(--color-primary);
    }

    .cart-float-btn {
        background-color: var(--color-primary);
        color: white;
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: -30px;
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
        background-color: var(--color-accent);
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
<?php if (isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'checkout.php'): ?>
    <?php
    $addresses = [];
    $defaultAddress = 'Seleccionar Dirección';

    try {
        // Ensure DB connection is available
        if (!isset($pdo)) {
            $dbPath = __DIR__ . '/../../back/conection/db.php';
            require_once $dbPath;
        }

        if (isset($pdo) && isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT * FROM direcciones_envio WHERE user_id = ? ORDER BY es_principal DESC");
            $stmt->execute([$_SESSION['user_id']]);
            $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (isset($_SESSION['selected_address_label'])) {
                $defaultAddress = $_SESSION['selected_address_label'];
            } elseif (!empty($addresses)) {
                foreach ($addresses as $addr) {
                    if ($addr['es_principal']) {
                        $defaultAddress = $addr['alias'] ? $addr['alias'] : $addr['calle_numero'];
                        break;
                    }
                }
                if ($defaultAddress == 'Seleccionar Dirección' && count($addresses) > 0) {
                    $defaultAddress = $addresses[0]['alias'] ? $addresses[0]['alias'] : $addresses[0]['calle_numero'];
                }
            }
        }
    } catch (Throwable $e) {
        $defaultAddress = 'Dirección';
    }
    ?>
    <div class="mobile-top-bar d-block d-lg-none">
        <div class="entrega-label">Entrega</div>

        <div class="header-row-bottom">
            <div class="dropdown location-pill" style="padding: 0;">
                <button
                    class="btn w-100 d-flex align-items-center justify-content-between border-0 bg-transparent shadow-none"
                    type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                    style="padding: 8px 15px;">
                    <div class="d-flex align-items-center overflow-hidden">
                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                        <span class="location-text-content text-start text-dark fw-bold text-truncate"
                            style="max-width: 180px;">
                            <?php echo htmlspecialchars($defaultAddress); ?>
                        </span>
                    </div>
                    <i class="fas fa-chevron-down text-dark small ms-2"></i>
                </button>
                <ul class="dropdown-menu w-100 shadow-sm border-0 rounded-3 mt-1" aria-labelledby="dropdownMenuButton1">
                    <?php if (count($addresses) > 0): ?>
                        <li>
                            <h6 class="dropdown-header small text-muted text-uppercase">Mis Direcciones</h6>
                        </li>
                        <?php foreach ($addresses as $addr): ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center justify-content-between py-2" href="#"
                                    onclick="selectHeaderAddress(<?php echo $addr['id']; ?>); return false;">
                                    <div>
                                        <i class="fas fa-map-marker-alt text-muted me-2 small"></i>
                                        <span class="fw-bold text-dark small">
                                            <?php echo htmlspecialchars($addr['alias'] ? $addr['alias'] : substr($addr['calle_numero'], 0, 15) . '...'); ?>
                                        </span>
                                    </div>
                                    <?php if ($addr['es_principal']): ?>
                                        <span class="badge bg-light text-secondary rounded-pill"
                                            style="font-size: 0.6rem;">Principal</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                    <?php else: ?>
                        <li><a class="dropdown-item text-muted small" href="#">Sin direcciones guardadas</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    <?php endif; ?>
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2 text-primary fw-bold small"
                            href="front/cliente/perfil.php?section=direcciones">
                            <i class="fas fa-plus-circle me-2"></i> Nueva Dirección
                        </a>
                    </li>
                </ul>
            </div>

            <div class="notification-circle" onclick="location.href='front/cliente/perfil.php?section=pedidos'">
                <i class="fas fa-bell"></i>
                <!-- <div class="notification-dot"></div> -->
            </div>
            <!-- Search Icon (Mobile) -->
            <a href="tienda.php" class="text-dark">
                <i class="fas fa-search" style="font-size: 1.2rem;"></i>
            </a>
        </div>
    </div>

    <script>
        function selectHeaderAddress(addressId) {
            const formData = new FormData();
            formData.append('action', 'set_session_address');
            formData.append('address_id', addressId);

            fetch('back/client_manager.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        console.error('Error selecting address:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
<?php endif; ?>

<!-- EXISTING HEADER (Desktop Only: d-none d-lg-block) -->
<header class="header-wrapper d-none d-lg-block">
    <nav class="navbar navbar-expand-lg header-main bg-white pb-2 pb-lg-3">
        <div class="container-fluid px-0">
            <a class="navbar-brand me-lg-5" href="<?php echo $base_url; ?>index.php">
                <img src="<?php echo $base_url; ?>front/multimedia/logo.svg" alt="Roots Logo">
            </a>

            <div class="d-flex align-items-center d-lg-none">
                <!-- Hidden on mobile -->
            </div>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 main-nav-links">
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>tienda.php">Categorías</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>iniciativas.php">Nosotros</a>
                    </li>
                </ul>

                <div class="d-none d-lg-flex align-items-center w-100 justify-content-end">
                    <form class="search-container me-4" action="<?php echo $base_url; ?>tienda.php" method="GET">
                        <input type="text" class="search-input" name="q"
                            placeholder="Busca productos sin químicos dañinos..."
                            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button type="submit" class="search-btn-circle"><i class="fas fa-search"></i></button>
                    </form>

                    <a href="<?php echo $base_url; ?>front/cliente/perfil.php" class="text-decoration-none"><i
                            class="fas fa-user header-icon"></i></a>
                    <a href="<?php echo $base_url; ?>carrito.php" class="text-decoration-none position-relative"
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
    <a href="<?php echo $base_url; ?>index.php"
        class="nav-item-mobile <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="<?php echo $base_url; ?>tienda.php"
        class="nav-item-mobile <?php echo ($current_page == 'tienda.php') ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i>
        <span>Categorías</span>
    </a>
    <div class="nav-item-mobile">
        <a href="<?php echo $base_url; ?>carrito.php" class="cart-float-btn">
            <i class="fas fa-shopping-bag" id="cart-icon-mobile"></i>
            <span class="cart-badge cart-count-float" style="display: none;">0</span>
        </a>
    </div>
    <!-- Pedidos Item (Conditional) -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo $base_url; ?>front/cliente/perfil.php?section=pedidos"
            class="nav-item-mobile <?php echo ($current_page == 'perfil.php' && isset($_GET['section']) && $_GET['section'] == 'pedidos') ? 'active' : ''; ?>">
            <i class="fas fa-file-alt"></i>
            <span>Pedidos</span>
        </a>
    <?php else: ?>
        <div class="nav-item-mobile" style="opacity: 0.4; cursor: default;">
            <i class="fas fa-file-alt"></i>
            <span>Pedidos</span>
        </div>
    <?php endif; ?>

    <!-- Profile Item -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo $base_url; ?>front/cliente/perfil.php"
            class="nav-item-mobile <?php echo ($current_page == 'perfil.php' && (!isset($_GET['section']) || $_GET['section'] != 'pedidos')) ? 'active' : ''; ?>">
            <i class="far fa-user"></i>
            <span>Perfil</span>
        </a>
    <?php else: ?>
        <a href="<?php echo $base_url; ?>login.php" class="nav-item-mobile">
            <i class="fas fa-user"></i>
            <span>Perfil</span>
        </a>
    <?php endif; ?>
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