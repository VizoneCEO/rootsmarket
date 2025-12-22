<?php
// --- 1. CONEXIÓN Y LÓGICA ---
require_once(__DIR__ . '/../../back/conection/db.php');

// Obtener ID del producto desde la URL
$id_producto = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$producto = null;
$imagenes = [];
$similares = [];

if ($id_producto > 0) {
    try {
        // A) OBTENER DETALLES DEL PRODUCTO
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? AND estatus = 'activo'");
        $stmt->execute([$id_producto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            // B) OBTENER IMÁGENES
            $stmt_img = $pdo->prepare("SELECT imagen_url FROM producto_imagenes WHERE producto_id = ? ORDER BY orden ASC");
            $stmt_img->execute([$id_producto]);
            $imagenes = $stmt_img->fetchAll(PDO::FETCH_COLUMN);

            if (empty($imagenes)) {
                $imagenes[] = 'front/multimedia/productos/default.png';
            }

            // C) OBTENER PRODUCTOS SIMILARES
            $stmt_sim = $pdo->prepare("
                SELECT p.*, 
                       (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal
                FROM productos p
                WHERE p.catalogo_id = ? AND p.id != ? AND p.estatus = 'activo'
                ORDER BY RAND() LIMIT 4
            ");
            $stmt_sim->execute([$producto['catalogo_id'], $id_producto]);
            $similares = $stmt_sim->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        // Manejo de errores silencioso
    }
}

if (!$producto) {
    echo "<div class='container my-5 text-center'><h3>Producto no encontrado</h3><a href='tienda.php' class='btn btn-success'>Volver a la tienda</a></div>";
    return;
}
?>

<style>
    /* --- ESTILOS COMPARTIDOS / DESKTOP PRESERVADOS --- */
    body {
        background-color: #ffffff;
    }

    /* Desktop Styles Wrapper */
    .desktop-view .gallery-wrapper {
        display: flex;
        gap: 15px;
        height: 500px;
    }

    .desktop-view .thumbnails-col {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 80px;
        overflow-y: auto;
    }

    .desktop-view .thumb-img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: contain;
        border-radius: 10px;
        background-color: #fff;
        border: 1px solid #eee;
        cursor: pointer;
        transition: all 0.2s;
        padding: 5px;
    }

    .desktop-view .thumb-img:hover,
    .desktop-view .thumb-img.active {
        border-color: #E67E22;
    }

    .desktop-view .main-image-col {
        flex-grow: 1;
        background-color: #F9F9F9;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .desktop-view .main-img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .desktop-view .product-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }

    .desktop-view .product-price {
        font-size: 1.8rem;
        font-weight: 400;
        color: #333;
        margin-bottom: 1rem;
    }

    .desktop-view .rating-stars {
        color: #E67E22;
        font-size: 0.9rem;
    }

    .desktop-view .review-count {
        color: #888;
        font-size: 0.9rem;
        margin-left: 5px;
    }

    .desktop-view .product-description {
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
        margin: 1.5rem 0;
    }

    .desktop-view .action-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 2rem;
    }

    .desktop-view .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 50px;
        padding: 5px 15px;
        height: 50px;
    }

    .desktop-view .qty-btn {
        border: none;
        background: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #333;
    }

    .desktop-view .qty-input {
        border: none;
        width: 40px;
        text-align: center;
        font-weight: 600;
        outline: none;
    }

    .desktop-view .btn-add-cart {
        background-color: #E67E22;
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 12px 40px;
        font-weight: 600;
        height: 50px;
        flex-grow: 1;
        transition: background 0.3s;
    }

    .desktop-view .btn-add-cart:hover {
        background-color: #D35400;
    }

    .desktop-view .btn-buy-now {
        background-color: #fff;
        color: #333;
        border: 1px solid #333;
        border-radius: 50px;
        padding: 12px 40px;
        font-weight: 600;
        height: 50px;
        width: 100%;
        margin-top: 10px;
        transition: all 0.3s;
    }

    .desktop-view .btn-buy-now:hover {
        background-color: #f5f5f5;
    }

    .desktop-view .shipping-info {
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 0.9rem;
        color: #555;
    }

    .desktop-view .shipping-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .desktop-view .shipping-item i {
        color: #4EAE3E;
        font-size: 1.1rem;
    }

    .desktop-view .nutrition-box {
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 15px;
        padding: 20px;
        margin-top: 3rem;
    }

    .desktop-view .nutrition-item {
        text-align: center;
        border-right: 1px solid #eee;
    }

    .desktop-view .nutrition-item:last-child {
        border-right: none;
    }

    .desktop-view .nutri-val {
        font-weight: 700;
        font-size: 1.1rem;
        display: block;
        color: #333;
    }

    .desktop-view .nutri-label {
        font-size: 0.8rem;
        color: #888;
        text-transform: uppercase;
    }

    .desktop-view .similar-card {
        border: none;
        background: transparent;
        transition: transform 0.3s;
    }

    .desktop-view .similar-card:hover {
        transform: translateY(-5px);
    }

    .desktop-view .similar-img-box {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 15px;
        aspect-ratio: 1/1.1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        position: relative;
    }

    .desktop-view .similar-img-box img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }

    /* --- MOBILE STYLES (New Design) --- */
    .mobile-product-view {
        background-color: #fff;
        /* Removed bottom padding as footer is now static */
        padding-bottom: 20px;
    }

    /* Transparent Header */
    .mobile-prod-header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 20px;
        z-index: 20;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-btn-circle {
        width: 40px;
        height: 40px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: #333;
        text-decoration: none;
        cursor: pointer;
    }

    /* Hero Image */
    .mobile-hero-bg {
        width: 100%;
        height: 45vh;
        /* Large hero as requested */
        background-color: #f7f7f7;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        /* Background texture or image if needed */
        background-image: url('front/multimedia/fondo_natural.png');
        /* If exists, else color */
        background-size: cover;
    }

    .mobile-hero-img {
        max-width: 80%;
        max-height: 85%;
        object-fit: contain;
        z-index: 10;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.15));
    }

    /* Body Content */
    .mobile-body-content {
        padding: 20px;
        background: white;
        position: relative;
        border-radius: 25px 25px 0 0;
        margin-top: -25px;
        /* Overlap hero */
        z-index: 15;
    }

    .m-prod-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #111;
        line-height: 1.2;
        margin-bottom: 5px;
    }

    .m-prod-qty {
        font-size: 0.9rem;
        color: #888;
        margin-bottom: 15px;
    }

    /* Price Row */
    .m-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .m-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: #4EAE3E;
        /* Green Price */
    }

    .m-qty-selector {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .m-qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background-color: #f0f0f0;
        /* Light gray */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        cursor: pointer;
        border: none;
    }

    .m-qty-btn.green {
        background-color: #dcfce7;
        /* Light green */
        color: #4EAE3E;
    }

    .m-qty-val {
        font-weight: 700;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    /* Expandable Description */
    .m-desc-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .m-desc-text {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.5;
    }

    .m-show-more {
        color: #4EAE3E;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        margin-top: 5px;
    }

    /* Similar Slider */
    .m-similar-scroll {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding: 10px 5px 20px 5px;
        scroll-snap-type: x mandatory;
    }

    .m-similar-card {
        flex: 0 0 140px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 10px;
        scroll-snap-align: start;
        text-align: center;
    }

    .m-sim-img {
        width: 100%;
        height: 100px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    .m-sim-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 4px;
    }

    .m-sim-price {
        font-size: 0.9rem;
        color: #333;
        font-weight: 600;
    }

    .m-sim-add {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #dcfce7;
        color: #4EAE3E;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 5px auto 0 auto;
    }

    /* Fixed/Static Footer Action */
    .m-footer-action {
        background: white;
        padding: 20px 0;
        margin-top: 20px;
        width: 100%;
    }

    .m-btn-add {
        background-color: #4EAE3E;
        color: white;
        border: none;
        width: 100%;
        padding: 15px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .m-btn-add:active {
        background-color: #3d8b31;
    }

    /* Specifications Grid Styles */
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 25px;
    }

    .spec-card-square {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 10px 5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        min-height: 70px;
        word-break: break-word;
    }

    .spec-card-square .label {
        font-size: 0.65rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .spec-card-square .value {
        font-size: 0.9rem;
        font-weight: 700;
        color: #333;
        line-height: 1.1;
    }
</style>

<!-- ================= MOBILE VIEW ================= -->
<div class="d-lg-none mobile-product-view">
    <!-- Transparent Overlay Header -->
    <div class="mobile-prod-header">
        <a href="javascript:history.back()" class="header-btn-circle">
            <i class="fas fa-arrow-left"></i>
        </a>
        <a href="carrito.php" class="header-btn-circle">
            <i class="fas fa-shopping-basket"></i>
        </a>
    </div>

    <!-- Hero Section -->
    <div class="mobile-hero-bg">
        <!-- Main Image -->
        <img src="<?php echo htmlspecialchars(ltrim($imagenes[0], '/')); ?>" class="mobile-hero-img" alt="Product">
    </div>

    <!-- Body Content -->
    <div class="mobile-body-content">
        <h1 class="m-prod-title"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
        <div class="m-prod-qty"> <!-- Placeholder for net content title if desired --> </div>

        <div class="m-price-row">
            <div class="m-price">
                <?php if (($producto['es_promocion'] ?? 0) == 1 && $producto['precio_oferta'] > 0): ?>
                    $<?php echo number_format($producto['precio_oferta'], 2); ?>
                    <span class="text-muted text-decoration-line-through ms-2"
                        style="font-size: 1rem; color: #999 !important;">$<?php echo number_format($producto['precio_venta'], 2); ?></span>
                <?php else: ?>
                    $<?php echo number_format($producto['precio_venta'], 2); ?>
                <?php endif; ?>
            </div>

            <div class="m-qty-selector">
                <button class="m-qty-btn" onclick="updateQtyMobile(-1)"><i class="fas fa-minus"></i></button>
                <span class="m-qty-val" id="qtyMobileVal">1</span>
                <button class="m-qty-btn green" onclick="updateQtyMobile(1)"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        <!-- Specifications & Nutrition Grid -->
        <div class="specs-grid">
            <!-- Marca / Origen -->
            <div class="spec-card-square">
                <span class="label">Marca</span>
                <span
                    class="value"><?php echo htmlspecialchars($producto['origen'] ?? $producto['marca'] ?? $producto['nombre']); ?></span>
            </div>

            <!-- Contenido -->
            <div class="spec-card-square">
                <span class="label">Contenido</span>
                <span class="value"><?php echo $producto['contenido_neto'] ?? '1 Pza'; ?></span>
            </div>

            <!-- Calorías -->
            <?php if (!empty($producto['calorias'])): ?>
                <div class="spec-card-square">
                    <span class="label">Calorías</span>
                    <span class="value"><?php echo $producto['calorias']; ?></span>
                </div>
            <?php endif; ?>

            <!-- Proteínas -->
            <?php if (!empty($producto['proteinas_g'])): ?>
                <div class="spec-card-square">
                    <span class="label">Proteínas</span>
                    <span class="value"><?php echo $producto['proteinas_g']; ?>g</span>
                </div>
            <?php endif; ?>

            <!-- Carbohidratos -->
            <?php if (!empty($producto['carbohidratos_g'])): ?>
                <div class="spec-card-square">
                    <span class="label">Carbos</span>
                    <span class="value"><?php echo $producto['carbohidratos_g']; ?>g</span>
                </div>
            <?php endif; ?>

            <!-- Azúcares -->
            <?php if (!empty($producto['azucares_g'])): ?>
                <div class="spec-card-square">
                    <span class="label">Azúcares</span>
                    <span class="value"><?php echo $producto['azucares_g']; ?>g</span>
                </div>
            <?php endif; ?>

            <!-- Fibra -->
            <?php if (!empty($producto['fibra_g'])): ?>
                <div class="spec-card-square">
                    <span class="label">Fibra</span>
                    <span class="value"><?php echo $producto['fibra_g']; ?>g</span>
                </div>
            <?php endif; ?>

            <!-- Sodio -->
            <?php if (!empty($producto['sodio_mg'])): ?>
                <div class="spec-card-square">
                    <span class="label">Sodio</span>
                    <span class="value"><?php echo $producto['sodio_mg']; ?>mg</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <h3 class="m-desc-title">Descripción</h3>
            <div class="m-desc-text">
                <?php echo htmlspecialchars($producto['descripcion_corta']); ?>
            </div>
        </div>

        <!-- Action Button (Static Flow) - Moved here -->
        <div class="m-footer-action" style="padding-top: 0; margin-top: 5px; margin-bottom: 30px;">
            <button class="m-btn-add" onclick="addToCartMobile()">
                <i class="fas fa-shopping-basket"></i> Agregar al carrito
            </button>
        </div>

        <!-- Similar Products -->
        <?php if (!empty($similares)): ?>
            <div class="mb-5">
                <h3 class="m-desc-title">Productos Similares</h3>
                <div class="m-similar-scroll">
                    <?php foreach ($similares as $sim): ?>
                        <a href="producto.php?id=<?php echo $sim['id']; ?>" class="m-similar-card text-decoration-none">
                            <img src="<?php echo htmlspecialchars(ltrim($sim['imagen_principal'] ?? $sim['imagen_url'], '/')); ?>"
                                class="m-sim-img">
                            <div class="m-sim-title"><?php echo htmlspecialchars($sim['nombre']); ?></div>
                            <div class="m-sim-price">$<?php echo number_format($sim['precio_venta'], 2); ?></div>
                            <div class="m-sim-add"
                                onclick="event.preventDefault(); addToCart(<?php echo $sim['id']; ?>, '<?php echo htmlspecialchars($sim['nombre']); ?>', <?php echo $sim['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($sim['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>')">
                                <i class="fas fa-plus"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>


<!-- ================= DESKTOP VIEW (Original) ================= -->
<div class="container my-5 d-none d-lg-block desktop-view">
    <div class="row gx-5">

        <div class="col-lg-7 mb-5 mb-lg-0">
            <div class="gallery-wrapper">
                <div class="thumbnails-col d-none d-md-flex">
                    <?php foreach ($imagenes as $index => $img): ?>
                        <img src="<?php echo htmlspecialchars(ltrim($img, '/')); ?>"
                            class="thumb-img <?php echo $index === 0 ? 'active' : ''; ?>"
                            onclick="changeImage(this.src, this)" alt="Thumbnail">
                    <?php endforeach; ?>
                </div>

                <div class="main-image-col">
                    <img id="mainImage" src="<?php echo htmlspecialchars(ltrim($imagenes[0], '/')); ?>" class="main-img"
                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <h1 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h1>

            <div class="d-flex align-items-center mb-3">
                <div class="rating-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                        class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <span class="review-count">(32 reviews)</span>
                <i class="far fa-heart ms-auto fs-5 text-muted" style="cursor: pointer;"></i>
            </div>

            <div class="product-price">
                <?php if (($producto['es_promocion'] ?? 0) == 1 && $producto['precio_oferta'] > 0): ?>
                    <span
                        class="text-decoration-line-through text-muted fs-5 me-2">$<?php echo number_format($producto['precio_venta'], 2); ?></span>
                    $<?php echo number_format($producto['precio_oferta'], 2); ?>
                <?php else: ?>
                    $<?php echo number_format($producto['precio_venta'], 2); ?>
                <?php endif; ?>
            </div>

            <div class="product-description">
                <p class="mb-2"><?php echo htmlspecialchars($producto['descripcion_corta']); ?></p>
                <ul class="list-unstyled text-muted small mt-3">
                    <li>• Origen: <?php echo htmlspecialchars($producto['origen'] ?? 'Nacional'); ?></li>
                    <?php if ($producto['es_organico']): ?>
                        <li>• Producto Orgánico</li> <?php endif; ?>
                    <?php if ($producto['es_vegano']): ?>
                        <li>• Apto para veganos</li> <?php endif; ?>
                </ul>
            </div>

            <div class="action-row">
                <div class="quantity-selector">
                    <button class="qty-btn" onclick="updateQty(-1)">-</button>
                    <input type="text" class="qty-input" id="qtyInput" value="1" readonly>
                    <button class="qty-btn" onclick="updateQty(1)">+</button>
                </div>
                <button class="btn-add-cart" onclick="addToCart(
                            <?php echo $producto['id']; ?>,
                            '<?php echo htmlspecialchars($producto['nombre']); ?>',
                            <?php echo $producto['precio_oferta'] ?: $producto['precio_venta']; ?>,
                            '<?php echo htmlspecialchars(ltrim($imagenes[0], '/')); ?>',
                            document.getElementById('qtyInput').value
                        )">
                    Añadir al carrito
                </button>
            </div>
            <button class="btn-buy-now">Comprar ahora</button>

            <div class="shipping-info">
                <div class="shipping-item">
                    <i class="fas fa-truck"></i> <span>Envío gratis en compras mayores a $1,000 MXN</span>
                </div>
                <div class="shipping-item">
                    <i class="fas fa-clock"></i> <span>Entrega en menos de 2 horas | Envíos rápidos y seguros</span>
                </div>
            </div>
        </div>
    </div>

    <?php if ($producto['calorias'] || $producto['proteinas_g']): ?>
        <div class="nutrition-box">
            <h5 class="fw-bold mb-4 text-center text-uppercase" style="font-size: 1rem; letter-spacing: 1px;">Información
                Nutricional</h5>
            <div class="row justify-content-center">
                <div class="col-3 col-md-2 nutrition-item">
                    <span class="nutri-val"><?php echo $producto['calorias'] ?? '-'; ?></span>
                    <span class="nutri-label">Calorías</span>
                </div>
                <div class="col-3 col-md-2 nutrition-item">
                    <span class="nutri-val"><?php echo $producto['proteinas_g'] ?? '-'; ?>g</span>
                    <span class="nutri-label">Proteínas</span>
                </div>
                <div class="col-3 col-md-2 nutrition-item">
                    <span class="nutri-val"><?php echo $producto['grasas_g'] ?? '-'; ?>g</span>
                    <span class="nutri-label">Grasas</span>
                </div>
                <div class="col-3 col-md-2 nutrition-item">
                    <span class="nutri-val"><?php echo $producto['carbohidratos_g'] ?? '-'; ?>g</span>
                    <span class="nutri-label">Carbos</span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-5 pt-5">
        <h3 class="fw-bold mb-4 text-uppercase" style="font-size: 1.5rem;">PRODUCTOS SIMILARES</h3>
        <div class="row g-4">
            <?php if (!empty($similares)): ?>
                <?php foreach ($similares as $sim): ?>
                    <div class="col-6 col-md-3">
                        <div class="similar-card">
                            <div class="similar-img-box">
                                <a href="producto.php?id=<?php echo $sim['id']; ?>">
                                    <img src="<?php echo htmlspecialchars(ltrim($sim['imagen_principal'] ?? $sim['imagen_url'], '/')); ?>"
                                        alt="<?php echo htmlspecialchars($sim['nombre']); ?>">
                                </a>
                            </div>
                            <h6 class="fw-bold text-truncate mt-2">
                                <a href="producto.php?id=<?php echo $sim['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($sim['nombre']); ?>
                                </a>
                            </h6>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="fw-bold text-dark">$<?php echo number_format($sim['precio_venta'], 2); ?></span>
                                <i class="fas fa-plus-circle fs-4 text-dark" style="cursor: pointer;"
                                    onclick="addToCart(<?php echo $sim['id']; ?>, '<?php echo htmlspecialchars($sim['nombre']); ?>', <?php echo $sim['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($sim['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>')"></i>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No hay productos similares disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Desktop JS
    function changeImage(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumb-img').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    function updateQty(change) {
        const input = document.getElementById('qtyInput');
        let val = parseInt(input.value);
        val += change;
        if (val < 1) val = 1;
        input.value = val;
    }

    // Mobile JS
    function updateQtyMobile(change) {
        const valSpan = document.getElementById('qtyMobileVal');
        let val = parseInt(valSpan.innerText);
        val += change;
        if (val < 1) val = 1;
        valSpan.innerText = val;
    }

    function addToCartMobile() {
        let qty = document.getElementById('qtyMobileVal').innerText;
        addToCart(
            <?php echo $producto['id']; ?>,
            '<?php echo htmlspecialchars($producto['nombre']); ?>',
            <?php echo $producto['precio_oferta'] ?: $producto['precio_venta']; ?>,
            '<?php echo htmlspecialchars(ltrim($imagenes[0], '/')); ?>',
            qty
        );
    }
</script>