<?php
// 1. Conexión a la base de datos
require_once(__DIR__ . '/../../back/conection/db.php');

// 2. Consultas para obtener datos reales

// A) Obtener Categorías (Limitamos a 4 para el diseño de la home)
try {
    $stmt_cat = $pdo->prepare("SELECT * FROM catalogos WHERE estatus = 'activo' ORDER BY id ASC");
    $stmt_cat->execute();
    $categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categorias = []; // Fallback vacío si hay error
}

// Obtener Configuración (Temporada)
try {
    $stmt_config = $pdo->query("SELECT clave, valor FROM configuracion");
    $configuracion = $stmt_config->fetchAll(PDO::FETCH_KEY_PAIR);
    $nombre_temporada = $configuracion['nombre_temporada'] ?? 'Temporada';
} catch (PDOException $e) {
    $nombre_temporada = 'Temporada';
    $configuracion = [];
}

// B) Obtener Productos "Top" (Lo Mejor de Roots) - Ahora filtrado por flag "es_mejor"
// Criterio: Productos activos que tienen es_mejor = 1
try {
    $stmt_prod = $pdo->prepare("
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo' AND p.es_mejor = 1
        ORDER BY p.id DESC 
        LIMIT 8
    ");
    $stmt_prod->execute();
    $productos_top = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productos_top = [];
}

// C) Obtener "Novedades" (Productos más recientes)
try {
    $stmt_nov = $pdo->prepare("
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo' AND p.es_novedad = 1
        ORDER BY p.id DESC 
        LIMIT 8
    ");
    $stmt_nov->execute();
    $productos_novedades = $stmt_nov->fetchAll(PDO::FETCH_ASSOC);

    // Fallback: Si no hay marcados como novedad, traer los últimos creados
    if (empty($productos_novedades)) {
        $stmt_nov_fallback = $pdo->prepare("
            SELECT p.*, 
                   (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
            FROM productos p 
            WHERE p.estatus = 'activo' 
            ORDER BY p.id DESC 
            LIMIT 8
        ");
        $stmt_nov_fallback->execute();
        $productos_novedades = $stmt_nov_fallback->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $productos_novedades = [];
}

// D) Obtener Productos de Temporada
try {
    $stmt_temp = $pdo->prepare("
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo' AND p.es_temporada = 1
        ORDER BY p.id DESC 
        LIMIT 8
    ");
    $stmt_temp->execute();
    $productos_temporada = $stmt_temp->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productos_temporada = [];
}
?>
<?php
// E) Obtener Productos en Promoción
try {
    $stmt_promo = $pdo->prepare("
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo' AND p.es_promocion = 1
        ORDER BY p.id DESC 
        LIMIT 8
    ");
    $stmt_promo->execute();
    $productos_promocion = $stmt_promo->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productos_promocion = [];
}
?>


<style>
    /* --- HERO SECTION --- */
    /* Desktop Hero */
    .hero-section-desktop {
        background-color: #666666;
        height: 600px;
        /* Increased height for better impact */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        /* Aligned left */
        text-align: left;
        color: white;
        padding: 0;
        background-image: url('front/multimedia/fondoP2.jpg');
        background-size: cover;
        background-position: center;
    }

    /* Mobile Hero */
    .hero-section-mobile {
        position: relative;
        height: 450px;
        background-image: url('front/multimedia/fondoP2M.jpg');
        /* background-image: url('https://placehold.co/800x450/666666/FFFFFF?text=Mascota+Temporal');  Placeholder Mascota option */
        background-size: cover;
        background-position: center bottom;
        /* Adjusted to likely show mascot if it is at the bottom */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Overlay for text readability if needed */
    .hero-mobile-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.2);
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    .hero-title-mobile {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        text-align: center;
        line-height: 1.1;
        text-transform: uppercase;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        max-width: 80%;
        margin: 0 auto;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        max-width: 600px;
        color: #E0E0E0;
    }

    /* Mobile Search Float */
    .hero-search-float {
        position: absolute;
        bottom: -25px;
        /* Half overlapping */
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        z-index: 10;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 50px;
        background: white;
    }

    .hero-search-float .search-input {
        background: white !important;
        /* Override header gray */
        height: 50px;
    }

    .hero-search-float .search-btn-circle {
        background-color: transparent;
        color: #999;
    }

    /* --- SECCIONES GENERALES --- */
    .section-padding {
        padding: 4rem 0;
    }

    /* Mobile text adjustments */
    @media (max-width: 991px) {
        .section-padding {
            padding: 2.5rem 1.5rem;
        }

        .section-title {
            font-size: 1.3rem !important;
            text-align: center;
        }

        .section-desc {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
    }

    .section-title {
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .section-desc {
        color: #666;
        margin-bottom: 2rem;
    }

    /* --- BOTONES --- */
    .btn-dark-pill {
        background-color: #333;
        color: white;
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-dark-pill:hover {
        background-color: #000;
        color: white;
    }

    /* --- CARDS GRISES (BENTO GRID - DESKTOP) --- */
    .gray-card {
        background-color: #A0A0A0;
        border-radius: 20px;
        width: 100%;
        height: 100%;
        min-height: 250px;
        display: flex;
        align-items: flex-end;
        padding: 20px;
        color: white;
        font-weight: 500;
        font-size: 1.1rem;
        transition: transform 0.3s;
        background-size: cover;
        background-position: center;
        text-decoration: none;
    }

    .gray-card:hover {
        transform: translateY(-5px);
        color: white;
    }

    .card-tall {
        min-height: 520px;
    }

    .card-medium {
        min-height: 250px;
    }

    /* --- MOBILE HORIZONTAL SLIDERS --- */
    .horizontal-snap-slider {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding-bottom: 15px;
        scroll-snap-type: x mandatory;
        padding-left: 5px;
        /* Slight padding */
    }

    .horizontal-snap-slider::-webkit-scrollbar {
        display: none;
    }

    .snap-item {
        scroll-snap-align: start;
        flex: 0 0 85%;
        /* SHow part of next card */
    }


    /* --- ARROWS FOR CAROUSEL --- */
    .carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: white;
        border: 1px solid #ddd;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        color: #333;
    }

    .carousel-arrow:hover {
        background-color: #f0f0f0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .prev-arrow {
        left: -20px;
    }

    .next-arrow {
        right: -20px;
    }

    /* --- CATEGORIAS --- */
    /* Mobile Categories Horizontal - UPDATED */
    .mobile-cat-scroll {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding: 10px 5px;
        scroll-snap-type: x mandatory;
    }

    .mobile-cat-scroll::-webkit-scrollbar {
        display: none;
    }

    .mobile-cat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 0 0 31%;
        /* Show ~3 items */
        text-decoration: none;
        color: #333;
        scroll-snap-align: start;
        white-space: normal;
        vertical-align: top;
    }

    .mobile-cat-icon-circle {
        width: 65px;
        height: 65px;
        background-color: #F5F5F5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .mobile-cat-icon-circle img {
        width: 35px;
        height: 35px;
        object-fit: contain;
    }

    .mobile-cat-name {
        font-size: 0.75rem;
        font-weight: 500;
        text-align: center;
        line-height: 1.2;
        width: 100%;
        overflow-wrap: break-word;
    }


    /* Desktop Categories (Existing) */
    .cat-card {
        background-color: #f0f0f0;
        border-radius: 28px;
        height: 420px;
        position: relative;
        margin-bottom: 1.2rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        display: block;
    }

    .cat-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .cat-card:hover img {
        transform: scale(1.03);
    }

    .cat-label {
        text-align: center;
        color: #333;
        font-weight: 500;
        font-size: 1.2rem;
        margin-top: 5px;
    }

    .categories-carousel {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 20px;
        padding-bottom: 20px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .categories-carousel::-webkit-scrollbar {
        display: none;
    }

    .category-item {
        flex: 0 0 auto;
        width: 280px;
        scroll-snap-align: start;
    }

    @media (min-width: 992px) {
        .category-item {
            width: calc((100% - 40px) / 3);
        }
    }

    /* --- PRODUCT CARDS (LO MEJOR DE ROOTS) --- */
    .product-card-minimal {
        border: none;
        background: transparent;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .product-placeholder {
        background-color: #F9F9F9;
        border-radius: 20px;
        height: 300px;
        /* Desktop default */
        position: relative;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* Mobile product card height adjust */
    @media (max-width: 991px) {
        .product-placeholder {
            height: 180px;
            margin-bottom: 10px;
            border-radius: 15px;
        }

        .product-card-minimal h5 {
            font-size: 0.95rem;
        }
    }

    .product-placeholder img {
        max-height: 80%;
        max-width: 80%;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .discount-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #D32F2F;
        /* Red for discount as requested */
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        z-index: 10;
        font-weight: 700;
    }

    .add-btn-circle {
        border: none;
        background: #4EAE3E;
        /* Green button */
        width: 32px;
        height: 32px;
        border-radius: 8px;
        /* Squared with radius */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: white;
    }

    .add-btn-circle:hover {
        background-color: #3d8b31;
    }

    /* --- CARD MOBILE NOVEDADES (Updated Design) --- */
    .mobile-promo-card {
        background: white;
        padding: 10px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        /* Soft shadow */
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .mobile-promo-img-container {
        height: 180px;
        /* Reduced height for balance */
        background-color: transparent;
        border-radius: 12px;
        /* Slightly less rounded inner */
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        position: relative;
    }

    .mobile-promo-img-container img {
        max-width: 90%;
        max-height: 90%;
        mix-blend-mode: multiply;
        object-fit: contain;
    }

    /* Red Badge similar to Figma */
    .discount-badge-mobile {
        position: absolute;
        top: 10px;
        right: 10px;
        /* Right aligned in Figma mockup usually, or left */
        background-color: #ff4d4d;
        /* Bright red */
        color: white;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 5;
    }

    /* Green Add Button - Bottom Right of Info Area */
    .add-btn-mobile {
        background: #D9F2D5;
        /* Light green bg */
        color: #4EAE3E;
        /* Dark green icon */
        width: 38px;
        height: 38px;
        border-radius: 12px;
        /* Rounded square */
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s;
        font-size: 1.1rem;
    }

    .add-btn-mobile:active {
        transform: scale(0.95);
        background: #c3eec0;
    }

    .promo-title-mobile {
        font-weight: 800;
        font-size: 1rem;
        color: #333;
        margin-bottom: 4px;
        line-height: 1.2;
        /* Truncate to 2 lines */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .promo-unit-mobile {
        color: #888;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    .price-container-mobile {
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .current-price-mobile {
        font-weight: 800;
        font-size: 1.1rem;
        color: #333;
    }

    .old-price-mobile {
        text-decoration: line-through;
        color: #ff4d4d;
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.8;
    }
</style>

<!-- ================= HERO SECTION ================= -->

<!-- Desktop Hero -->
<div class="hero-section-desktop d-none d-lg-flex position-relative">
    <div
        style="z-index: 2; position: relative; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: flex-start; text-align: left; padding-left: 10%;">
        <div class="d-flex align-items-center mb-2">
            <!-- Badges if needed, or rely on background image containing them if they are illustrative. 
                 The user said "trabaje de fondo con la imagen la1.png", if the characters and badges are in the image, 
                 I just need to position the text. 
                 If they are separate elements, I would need them. 
                 Assuming the text COMPRA CON PROPÓSITO needs to be bold and specific. -->
        </div>

        <h1 class="hero-title text-white text-uppercase"
            style="font-size: 4rem; line-height: 1; text-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            TU SÚPER QUE SE PREOCUPA POR TI
        </h1>
        <!-- <h3 class="hero-subtitle-mobile text-white mb-3">POR TU SÚPER QUE SE PREOCUPA POR TI</h3> -->


        <p class="hero-subtitle text-white mt-3"
            style="font-size: 1.2rem; max-width: 500px; text-align: left; font-weight: 500;">
            Todo lo que necesitas para tu día a día, libre de químicos dañinos. Saludable, confiable y al alcance de un
            clic.
        </p>

        <a href="tienda.php" class="btn btn-primary rounded-pill px-5 py-3 mt-4 fw-bold" style="font-size: 1.1rem;">
            Empieza tu súper <i class="fas fa-chevron-right ms-2"></i>
        </a>
    </div>

    <!-- Badges - Positioned absolutely to match "Image 2" roughly if they are text overlaps, 
         but likely they are part of the illustration or need distinct HTML. 
         Adding placeholders for them just in case they are needed as text elements. -->

    <!-- "SIN NITRATOS" Badge (Green) -->
    <!-- <div style="position: absolute; top: 15%; right: 15%; background: #4EAE3E; color: white; padding: 5px 15px; transform: rotate(-5deg); font-weight: bold; border-radius: 10px; font-size: 1.2rem; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">SIN NITRATOS</div> -->

    <!-- "SIN NITRITOS" Badge (Orange) -->
    <!-- <div style="position: absolute; top: 35%; right: 35%; background: #F39C12; color: white; padding: 5px 15px; transform: rotate(5deg); font-weight: bold; border-radius: 10px; font-size: 1.2rem; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">SIN NITRITOS</div> -->

    <!-- Overlay -->
    <!-- <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.1); z-index: 1; pointer-events: none;"></div> -->
</div>

<!-- Mobile Hero -->
<div class="hero-section-mobile d-flex d-lg-none flex-column justify-content-center text-center px-4">
    <div class="hero-mobile-overlay"></div>
    <div style="z-index: 2;">
        <h1 class="hero-title-mobile text-white mb-3">TU SÚPER QUE SE PREOCUPA POR TI</h1>
        <!-- <h3 class="hero-subtitle-mobile text-white mb-3">POR TU SÚPER QUE SE PREOCUPA POR TI</h3> -->
        <p class="text-white mb-4" style="font-size: 1rem; opacity: 0.9;">
            Todo lo que necesitas para tu día a día, libre de químicos dañinos. Saludable, confiable y al alcance de un
            clic.
        </p>
        <a href="tienda.php" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">Empieza tu súper</a>
    </div>

    <form class="hero-search-float d-flex align-items-center px-2" action="tienda.php" method="GET">
        <button type="submit" class="search-btn-circle" style="position:static; transform:none; color: #4EAE3E;"><i
                class="fas fa-search"></i></button>
        <input type="text" class="search-input ps-2" name="q" placeholder="Buscar en Roots..."
            style="height: 100%; border-radius: 0; background: transparent !important;">
    </form>
</div>


<!-- ================= NOVEDADES & PROMOS (BENTO GRID) ================= -->
<style>
    .bento-card {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        color: white;
        text-decoration: none;
        display: block;
        height: 100%;
        background-size: cover;
        background-position: center;
        transition: transform 0.3s;
    }

    .bento-card:hover {
        transform: translateY(-5px);
        color: white;
    }

    .bento-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.2);
        /* Adjust darken as needed */
        z-index: 1;
    }

    .bento-content {
        position: relative;
        z-index: 2;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    /* Specific Colors/Backgrounds based on image */
    .card-season {
        background-color: #4EAE3E;
        /* Green */
        /* background-image: url('path/to/season-bg.jpg'); */
    }

    .card-new {
        background-color: #F5CBA7;
        color: #333;
    }

    .card-campaigns {
        background-color: #A04000;
        /* background-image: url('path/to/campaign-bg.jpg'); */
    }

    .card-promos {
        background-color: #D35400;
        /* Orange/Brown */
        /* background-image: url('path/to/promo-bg.jpg'); */
    }

    .bento-badge {
        background-color: #F39C12;
        /* Orange Badge */
        color: white;
        padding: 5px 15px;
        border-radius: 5px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.9rem;
        display: inline-block;
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .bento-title-lg {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        opacity: 0.2;
        position: absolute;
        top: 20%;
        left: 10px;
        right: 10px;
        text-align: center;
        text-transform: uppercase;
    }

    .bento-text-main {
        font-size: 1.2rem;
        font-weight: 600;
    }
</style>

<div class="container section-padding pt-5 d-none d-lg-block">
    <div class="row mb-5 d-none d-lg-flex">
        <div class="col-12 text-center">
            <h2 class="section-title text-uppercase" style="font-size: 2rem;">Novedades y Promos de la Semana</h2>
            <p class="section-desc">
                Encuentra descuentos, nuevos productos y ediciones limitadas,<br>
                todos con la garantía de estar libres de químicos dañinos.
            </p>
        </div>
    </div>

    <?php
    $img_temporada = !empty($configuracion['imagen_temporada']) ? $configuracion['imagen_temporada'] : 'front/multimedia/d2.png';
    ?>

    <!-- Desktop Bento Grid -->
    <div class="row g-4 d-none d-lg-flex" style="height: 500px;">

        <!-- COL 1: TEMPORADA (Dynamic Image) -->
        <div class="col-lg-4">
            <a href="tienda.php?filter=temporada" class="bento-card card-season"
                style="background-image: url('<?php echo htmlspecialchars($img_temporada); ?>');">
            </a>
        </div>

        <!-- COL 2: STACKED (New & Campaign) -->
        <div class="col-lg-4 d-flex flex-column gap-4">
            <!-- Top: Lo Nuevo (d3) -->
            <a href="tienda.php?filter=mejores" class="bento-card card-new flex-grow-1"
                style="background-image: url('front/multimedia/d2.png');">
            </a>
            <!-- Bottom: Campañas (d4) -->
            <a href="iniciativas.php" class="bento-card card-campaigns flex-grow-1"
                style="background-image: url('front/multimedia/d3.png');">
            </a>
        </div>

        <!-- COL 3: PROMOS (d1 used as d5 fallback) -->
        <div class="col-lg-4">
            <a href="tienda.php?filter=promocion" class="bento-card card-promos"
                style="background-image: url('front/multimedia/d4.png');">
            </a>
        </div>
    </div>

    <!-- Mobile Carousel (Simplified) -->

</div>


<!-- ================= CATEGORIES SECTION (Moved Down) ================= -->
<div class="container section-padding pb-4 pt-1 pt-lg-5">
    <!-- DESKTOP CATEGORIES HEADER -->
    <div class="row align-items-end mb-4 d-none d-lg-flex">
        <div class="col-md-6">
            <h2 class="section-title mb-0" style="font-size: 2rem; letter-spacing: 0.5px;">COMPRA POR CATEGORÍA</h2>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <p class="text-muted m-0" style="font-size: 1rem; line-height: 1.5;">
                Hicimos la selección por ti:<br>
                Alimentos, bebidas, cuidado personal y más.
            </p>
        </div>
    </div>

    <!-- MOBILE CATEGORIES (Redesigned Round 3) -->
    <style>
        .mobile-cat-scroll-new {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 15px;
            padding-bottom: 10px;
            /* Hide scrollbar */
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .mobile-cat-scroll-new::-webkit-scrollbar {
            display: none;
        }

        .mobile-cat-item-new {
            flex: 0 0 auto;
            /* Changed from percentage to auto so we accept the width of children but we will control icon width */
            width: 85px;
            /* Fixed width for the item to ensure uniformity approx 4.5 items */
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #333;
        }

        .mobile-cat-icon-box {
            width: 70px;
            /* Fixed width for ICON */
            height: 70px;
            /* Fixed height for ICON */
            background-color: #EEF8ED;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            overflow: hidden;
            flex-shrink: 0;
            /* Prevent shrinking */
        }

        .mobile-cat-icon-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 15px;
        }

        .mobile-cat-label-new {
            font-size: 0.75rem;
            font-weight: 400;
            text-align: center;
            line-height: 1.2;
            color: #1a1a1a;
            width: 100%;
            /* Ensure text wraps within the item width */
            word-wrap: break-word;
        }
    </style>
    <div class="position-relative d-lg-none mt-5 pt-3"> <!-- Added mt-5 and pt-3 to push down below search bar -->
        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
            <h5 class="fw-bold m-0" style="color:#102e18;">Categorías</h5>
            <a href="tienda.php" class="text-decoration-none fw-bold" style="color: #4EAE3E; font-size: 0.9rem;">Ver
                todo <i class="fas fa-chevron-right small"></i></a>
        </div>
        <div class="mobile-cat-scroll-new">
            <?php foreach ($categorias as $cat): ?>
                <a href="tienda.php?categoria=<?php echo $cat['id']; ?>" class="mobile-cat-item-new">
                    <div class="mobile-cat-icon-box">
                        <?php if (!empty($cat['icono_url'])): ?>
                            <img src="<?php echo htmlspecialchars(ltrim($cat['icono_url'], '/')); ?>" alt="Icon">
                        <?php else: ?>
                            <i class="fas fa-leaf text-success" style="font-size: 1.5rem;"></i>
                        <?php endif; ?>
                    </div>
                    <span class="mobile-cat-label-new"><?php echo htmlspecialchars($cat['nombre']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- DESKTOP CATEGORIES GRID (4 COLUMNS - UPDATED) -->
    <style>
        @media (min-width: 992px) {
            .category-item {
                /* Changed from /3 to /4 for 4 elements */
                width: calc((100% - 60px) / 4) !important;
            }
        }
    </style>
    <div class="position-relative d-none d-lg-block">
        <button class="carousel-arrow prev-arrow" onclick="scrollCategories(-1)" type="button">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div class="categories-carousel" id="categoriesCarousel">
            <?php foreach ($categorias as $cat): ?>
                <div class="category-item">
                    <a href="tienda.php?categoria=<?php echo $cat['id']; ?>" class="cat-card" style="height: 350px;">
                        <?php if (!empty($cat['imagen_url'])): ?>
                            <img src="<?php echo htmlspecialchars(ltrim($cat['imagen_url'], '/')); ?>"
                                alt="<?php echo htmlspecialchars($cat['nombre']); ?>">
                        <?php endif; ?>
                    </a>
                    <div class="cat-label mt-2 text-center  text-dark" style="font-size: 1.1rem;">
                        <?php echo htmlspecialchars($cat['nombre']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-arrow next-arrow" onclick="scrollCategories(1)" type="button">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>
<!-- ================= MOBILE NOVEDADES CAROUSEL ================= -->
<div class="d-lg-none py-4 mb-4" style="background-color: #D1E7D2;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold m-0 text-dark" style="font-size: 1.2rem; line-height: 1.2;">¡Novedades y promos<br>de la
                semana!</h2>
            <a href="tienda.php" class="text-success fw-bold text-decoration-none"
                style="font-size: 0.9rem; color: #4EAE3E !important;">Ver todo ></a>
        </div>

        <div class="d-flex overflow-auto pb-3"
            style="gap: 15px; scroll-snap-type: x mandatory; -ms-overflow-style: none; scrollbar-width: none;">
            <?php foreach ($productos_promocion as $prod): ?>
                <div class="bg-white rounded-4 p-3 shadow-sm position-relative d-flex flex-column justify-content-between flex-shrink-0"
                    style="width: 160px; scroll-snap-align: start;">

                    <!-- Discount -->
                    <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                        <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1" style="font-size: 0.75rem;">
                            <?php echo $descuento; ?>%
                        </span>
                    <?php endif; ?>

                    <!-- Image -->
                    <a href="producto.php?id=<?php echo $prod['id']; ?>" class="d-block text-center mb-3 mt-2">
                        <?php if (!empty($prod['imagen_principal'])): ?>
                            <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>" class="img-fluid"
                                style="height: 100px; object-fit: contain;"
                                alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                        <?php else: ?>
                            <img src="front/multimedia/productos/default.png" class="img-fluid"
                                style="height: 100px; object-fit: contain;">
                        <?php endif; ?>
                    </a>

                    <!-- Title -->
                    <h6 class="fw-bold text-dark mb-1"
                        style="font-size: 0.95rem; line-height: 1.2; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        <?php echo htmlspecialchars($prod['nombre']); ?>
                    </h6>

                    <!-- Unit Placeholder -->
                    <p class="text-muted mb-2" style="font-size: 0.75rem;">1 pza</p>

                    <!-- Price -->
                    <div class="d-flex flex-wrap align-items-baseline gap-2 mb-2">
                        <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                $<?php echo number_format($prod['precio_oferta'], 2); ?></div>
                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Add Button -->
                    <div class="text-end mt-auto">
                        <button class="btn rounded-3 p-0 d-inline-flex align-items-center justify-content-center"
                            style="width: 35px; height: 35px; background-color: #C6EBC5; border: none; color: #1b5e20;"
                            onclick="addToCart(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars($prod['nombre']); ?>', <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ================= MOBILE LO MEJOR CAROUSEL ================= -->
<div class="d-lg-none py-4 mb-4" style="background-color: #F8F8F8;"> <!-- Light gray bg -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold m-0 text-dark" style="font-size: 1.2rem; line-height: 1.2;">Lo mejor de Roots</h2>
            <a href="tienda.php?filter=mejores" class="text-success fw-bold text-decoration-none"
                style="font-size: 0.9rem; color: #4EAE3E !important;">Ver todo ></a>
        </div>

        <div class="d-flex overflow-auto pb-3"
            style="gap: 15px; scroll-snap-type: x mandatory; -ms-overflow-style: none; scrollbar-width: none;">
            <?php foreach ($productos_top as $prod): ?>
                <!-- Card Style: White with border/shadow -->
                <div class="bg-white rounded-4 p-3 shadow-sm position-relative d-flex flex-column justify-content-between flex-shrink-0"
                    style="width: 160px; scroll-snap-align: start; border: 1px solid #f0f0f0;">

                    <!-- Discount -->
                    <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                        <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1" style="font-size: 0.75rem;">
                            <?php echo $descuento; ?>%
                        </span>
                    <?php endif; ?>

                    <!-- Image -->
                    <a href="producto.php?id=<?php echo $prod['id']; ?>" class="d-block text-center mb-3 mt-2">
                        <?php if (!empty($prod['imagen_principal'])): ?>
                            <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>" class="img-fluid"
                                style="height: 100px; object-fit: contain;"
                                alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                        <?php else: ?>
                            <img src="front/multimedia/productos/default.png" class="img-fluid"
                                style="height: 100px; object-fit: contain;">
                        <?php endif; ?>
                    </a>

                    <!-- Title -->
                    <h6 class="fw-bold text-dark mb-1"
                        style="font-size: 0.95rem; line-height: 1.2; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        <?php echo htmlspecialchars($prod['nombre']); ?>
                    </h6>

                    <!-- Unit Placeholder -->
                    <p class="text-muted mb-2" style="font-size: 0.75rem;">1 pza</p>

                    <!-- Price -->
                    <div class="d-flex flex-wrap align-items-baseline gap-2 mb-2">
                        <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                $<?php echo number_format($prod['precio_oferta'], 2); ?></div>
                            <div class="text-danger text-decoration-line-through" style="font-size: 0.75rem;">
                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                        <?php else: ?>
                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Add Button -->
                    <div class="text-end mt-auto">
                        <button class="btn rounded-3 p-0 d-inline-flex align-items-center justify-content-center"
                            style="width: 35px; height: 35px; background-color: #C6EBC5; border: none; color: #1b5e20;"
                            onclick="addToCart(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars($prod['nombre']); ?>', <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ================= MOBILE HAZ QUE TU COMPRA CUENTE ================= -->
<div class="d-lg-none py-2 mb-5">
    <div class="container">
        <h2 class="fw-bold m-0 mb-3 text-dark" style="font-size: 1.2rem; line-height: 1.2;">Haz que tu compra cuente
        </h2>

        <div class="row g-2">
            <!-- Card 1: Raíces Verdes -->
            <div class="col-4">
                <a href="iniciativas.php" class="d-block position-relative rounded-3 overflow-hidden shadow-sm"
                    style="height: 110px;">
                    <img src="front/multimedia/r1.png" class="w-100 h-100" style="object-fit: cover;"
                        alt="Raíces Verdes">
                    <div class="position-absolute top-50 start-0 translate-middle-y text-white p-1 ps-2 pe-2 rounded-end d-none d-md-block"
                        style="background-color: #388E3C; font-size: 0.55rem; line-height: 1.1; font-weight: 800; letter-spacing: 0.5px;">
                        RAÍCES<br>VERDES
                    </div>
                </a>
            </div>
            <!-- Card 2: Cero Basura -->
            <div class="col-4">
                <a href="iniciativas.php" class="d-block position-relative rounded-3 overflow-hidden shadow-sm"
                    style="height: 110px;">
                    <img src="front/multimedia/r2.png" class="w-100 h-100" style="object-fit: cover;" alt="Cero Basura">
                    <div class="position-absolute top-50 start-0 translate-middle-y text-white p-1 ps-2 pe-2 rounded-end d-none d-md-block"
                        style="background-color: #388E3C; font-size: 0.55rem; line-height: 1.1; font-weight: 800; letter-spacing: 0.5px;">
                        CERO<br>BASURA
                    </div>
                </a>
            </div>
            <!-- Card 3: Impulso Local -->
            <div class="col-4">
                <a href="impulso_local.php" class="d-block position-relative rounded-3 overflow-hidden shadow-sm"
                    style="height: 110px;">
                    <img src="front/multimedia/r3.png" class="w-100 h-100" style="object-fit: cover;"
                        alt="Impulso Local">
                    <div class="position-absolute top-50 start-0 translate-middle-y text-white p-1 ps-2 pe-2 rounded-end d-none d-md-block"
                        style="background-color: #388E3C; font-size: 0.55rem; line-height: 1.1; font-weight: 800; letter-spacing: 0.5px;">
                        IMPULSO<br>LOCAL
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<!-- ================= MOBILE TABBED PRODUCTS SECTION ================= -->
<div class="d-lg-none py-4 mb-0" style="background-color: #fff;">
    <div class="container">
        <!-- Tabs Nav -->
        <ul class="nav nav-tabs border-0 justify-content-between mb-4" id="mobileProductTabs" role="tablist">
            <li class="nav-item" role="presentation" style="flex: 1; text-align: center;">
                <button class="nav-link active w-100 p-0 pb-2 fw-bold" id="tab-temporada" data-bs-toggle="tab"
                    data-bs-target="#content-temporada" type="button" role="tab" aria-selected="true"
                    style="color: #4EAE3E; border: none; border-bottom: 3px solid #4EAE3E; background: transparent; font-size: 0.95rem;">
                    <?php echo htmlspecialchars($nombre_temporada); ?>
                </button>
            </li>
            <li class="nav-item" role="presentation" style="flex: 1; text-align: center;">
                <button class="nav-link w-100 p-0 pb-2 fw-bold text-muted" id="tab-novedades" data-bs-toggle="tab"
                    data-bs-target="#content-novedades" type="button" role="tab" aria-selected="false"
                    style="border: none; background: transparent; font-size: 0.95rem;">
                    ¡Lo nuevo!
                </button>
            </li>
            <li class="nav-item" role="presentation" style="flex: 1; text-align: center;">
                <button class="nav-link w-100 p-0 pb-2 fw-bold text-muted" id="tab-promos" data-bs-toggle="tab"
                    data-bs-target="#content-promos" type="button" role="tab" aria-selected="false"
                    style="border: none; background: transparent; font-size: 0.95rem;">
                    ¡Promos!
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="mobileProductTabsContent">

            <!-- 1. Temporada Tab -->
            <div class="tab-pane fade show active" id="content-temporada" role="tabpanel"
                aria-labelledby="tab-temporada">
                <div class="row g-3">
                    <?php if (!empty($productos_temporada)): ?>
                        <?php foreach ($productos_temporada as $prod): ?>
                            <div class="col-6">
                                <div class="bg-white rounded-4 p-3 shadow-sm position-relative d-flex flex-column justify-content-between h-100"
                                    style="border: 1px solid #f0f0f0;">
                                    <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                        <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1"
                                            style="font-size: 0.75rem;"><?php echo $descuento; ?>%</span>
                                    <?php endif; ?>

                                    <a href="producto.php?id=<?php echo $prod['id']; ?>" class="d-block text-center mb-3 mt-2">
                                        <?php if (!empty($prod['imagen_principal'])): ?>
                                            <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>"
                                                class="img-fluid" style="height: 100px; object-fit: contain;"
                                                alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                                        <?php else: ?>
                                            <img src="front/multimedia/productos/default.png" class="img-fluid"
                                                style="height: 100px; object-fit: contain;">
                                        <?php endif; ?>
                                    </a>

                                    <h6 class="fw-bold text-dark mb-1"
                                        style="font-size: 0.95rem; line-height: 1.2; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        <?php echo htmlspecialchars($prod['nombre']); ?>
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.75rem;">1 pza</p>

                                    <div class="d-flex flex-wrap align-items-baseline gap-2 mb-2">
                                        <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                $<?php echo number_format($prod['precio_oferta'], 2); ?></div>
                                            <div class="text-danger text-decoration-line-through" style="font-size: 0.75rem;">
                                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                                        <?php else: ?>
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="text-end mt-auto">
                                        <button
                                            class="btn rounded-3 p-0 d-inline-flex align-items-center justify-content-center"
                                            style="width: 35px; height: 35px; background-color: #C6EBC5; border: none; color: #1b5e20;"
                                            onclick="addToCart(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars($prod['nombre']); ?>', <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-muted py-4">No hay productos de temporada.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. Novedades Tab -->
            <div class="tab-pane fade" id="content-novedades" role="tabpanel" aria-labelledby="tab-novedades">
                <div class="row g-3">
                    <?php if (!empty($productos_novedades)): ?>
                        <?php foreach ($productos_novedades as $prod): ?>
                            <div class="col-6">
                                <div class="bg-white rounded-4 p-3 shadow-sm position-relative d-flex flex-column justify-content-between h-100"
                                    style="border: 1px solid #f0f0f0;">
                                    <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                        <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1"
                                            style="font-size: 0.75rem;"><?php echo $descuento; ?>%</span>
                                    <?php endif; ?>

                                    <a href="producto.php?id=<?php echo $prod['id']; ?>" class="d-block text-center mb-3 mt-2">
                                        <?php if (!empty($prod['imagen_principal'])): ?>
                                            <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>"
                                                class="img-fluid" style="height: 100px; object-fit: contain;"
                                                alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                                        <?php else: ?>
                                            <img src="front/multimedia/productos/default.png" class="img-fluid"
                                                style="height: 100px; object-fit: contain;">
                                        <?php endif; ?>
                                    </a>

                                    <h6 class="fw-bold text-dark mb-1"
                                        style="font-size: 0.95rem; line-height: 1.2; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        <?php echo htmlspecialchars($prod['nombre']); ?>
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.75rem;">1 pza</p>

                                    <div class="d-flex flex-wrap align-items-baseline gap-2 mb-2">
                                        <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                $<?php echo number_format($prod['precio_oferta'], 2); ?></div>
                                            <div class="text-danger text-decoration-line-through" style="font-size: 0.75rem;">
                                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                                        <?php else: ?>
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="text-end mt-auto">
                                        <button
                                            class="btn rounded-3 p-0 d-inline-flex align-items-center justify-content-center"
                                            style="width: 35px; height: 35px; background-color: #C6EBC5; border: none; color: #1b5e20;"
                                            onclick="addToCart(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars($prod['nombre']); ?>', <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-muted py-4">No hay productos nuevos.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 3. Promos Tab -->
            <div class="tab-pane fade" id="content-promos" role="tabpanel" aria-labelledby="tab-promos">
                <div class="row g-3">
                    <?php if (!empty($productos_promocion)): ?>
                        <?php foreach ($productos_promocion as $prod): ?>
                            <div class="col-6">
                                <div class="bg-white rounded-4 p-3 shadow-sm position-relative d-flex flex-column justify-content-between h-100"
                                    style="border: 1px solid #f0f0f0;">
                                    <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                        <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1"
                                            style="font-size: 0.75rem;"><?php echo $descuento; ?>%</span>
                                    <?php endif; ?>

                                    <a href="producto.php?id=<?php echo $prod['id']; ?>" class="d-block text-center mb-3 mt-2">
                                        <?php if (!empty($prod['imagen_principal'])): ?>
                                            <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>"
                                                class="img-fluid" style="height: 100px; object-fit: contain;"
                                                alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                                        <?php else: ?>
                                            <img src="front/multimedia/productos/default.png" class="img-fluid"
                                                style="height: 100px; object-fit: contain;">
                                        <?php endif; ?>
                                    </a>

                                    <h6 class="fw-bold text-dark mb-1"
                                        style="font-size: 0.95rem; line-height: 1.2; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        <?php echo htmlspecialchars($prod['nombre']); ?>
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.75rem;">1 pza</p>

                                    <div class="d-flex flex-wrap align-items-baseline gap-2 mb-2">
                                        <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                $<?php echo number_format($prod['precio_oferta'], 2); ?></div>
                                            <div class="text-danger text-decoration-line-through" style="font-size: 0.75rem;">
                                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                                        <?php else: ?>
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="text-end mt-auto">
                                        <button
                                            class="btn rounded-3 p-0 d-inline-flex align-items-center justify-content-center"
                                            style="width: 35px; height: 35px; background-color: #C6EBC5; border: none; color: #1b5e20;"
                                            onclick="addToCart(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars($prod['nombre']); ?>', <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-muted py-4">No hay promociones activas.</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- JS for Tabs Styling -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('#mobileProductTabs .nav-link');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (e) {
                    // Update styles
                    tabs.forEach(t => {
                        t.style.color = '#6c757d';
                        t.style.borderBottom = 'none';
                    });
                    e.target.style.color = '#4EAE3E';
                    e.target.style.borderBottom = '3px solid #4EAE3E';
                });
            });
        });
    </script>
</div>

<!-- ================= LO MEJOR DE ROOTS (Desktop Only) ================= -->
<div class="container section-padding d-none d-lg-block">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="section-title" style="font-size: 2rem;">LO MEJOR DE ROOTS</h2>
            <p class="section-desc">
                Favoritos de la comunidad.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($productos_top as $prod): ?>
            <div class="col-6 col-md-3">
                <div class="position-relative h-100 d-flex flex-column">
                    <!-- Discount Badge -->
                    <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                        <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                        <span class="discount-badge">-<?php echo $descuento; ?>%</span>
                    <?php endif; ?>

                    <a href="producto.php?id=<?php echo $prod['id']; ?>"
                        class="product-placeholder mb-3 d-flex align-items-center justify-content-center bg-light-roots"
                        style="height: 280px; border-radius: 20px;">
                        <?php if (!empty($prod['imagen_principal'])): ?>
                            <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>"
                                alt="<?php echo htmlspecialchars($prod['nombre']); ?>"
                                style="max-height:80%; max-width:80%; object-fit:contain; mix-blend-mode:multiply;">
                        <?php else: ?>
                            <img src="front/multimedia/productos/default.png" alt="Producto"
                                style="max-height:80%; max-width:80%;">
                        <?php endif; ?>
                    </a>

                    <div class="d-flex justify-content-between align-items-start flex-grow-1">
                        <div>
                            <h5 class="fw-bold mb-1" style="font-size: 1rem; color: #333;">
                                <a href="producto.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($prod['nombre']); ?>
                                </a>
                            </h5>
                            <!-- Price -->
                            <div class="d-flex align-items-center gap-2">
                                <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0): ?>
                                    <span class="fw-bold"
                                        style="font-size: 0.95rem;">$<?php echo number_format($prod['precio_oferta'], 2); ?></span>
                                    <span class="text-muted text-decoration-line-through"
                                        style="font-size: 0.8rem;">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                <?php else: ?>
                                    <span class="fw-bold"
                                        style="font-size: 0.95rem;">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Add Button -->
                        <button class="btn p-0 rounded-circle d-flex align-items-center justify-content-center" onclick="addToCart(
                                    <?php echo $prod['id']; ?>,
                                    '<?php echo htmlspecialchars($prod['nombre']); ?>',
                                    <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>,
                                    '<?php echo htmlspecialchars(ltrim($prod['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>'
                                )"
                            style="width: 32px; height: 32px; border: 1px solid #4EAE3E; color: #4EAE3E; background: transparent;">
                            <i class="fas fa-plus" style="font-size: 0.8rem;"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ================= LO QUE NOS HACE DIFERENTE ================= -->
<!-- ================= LO QUE NOS HACE DIFERENTE ================= -->
<style>
    .bg-different-new {
        background-image: url('front/multimedia/fondo3.png');
        background-size: cover;
        background-position: center;
        position: relative;
        color: white;
        /* Ensure text is white as per design */
    }

    .bg-different-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        /* Darken slightly for readability */
        z-index: 1;
    }

    .diff-content {
        position: relative;
        z-index: 2;
    }
</style>

<div class="bg-different-new w-100 my-5 text-white d-none d-lg-block">
    <div class="bg-different-overlay"></div>
    <div class="container diff-content py-5">
        <div class="row align-items-center" style="min-height: 400px;">
            <!-- Left Column: Title & Button -->
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h2 class="display-4 fw-bold mb-4 text-uppercase text-white" style="line-height: 1.1;">
                    Lo que nos hace<br>DIFERENTE
                </h2>
                <a href="iniciativas.php" class="btn rounded-pill px-4 py-2 fw-bold text-white"
                    style="background-color: #F39C12; border: none;">
                    Únete a la comunidad Roots <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>

            <!-- Right Column: Text & Stats -->
            <div class="col-lg-7 ps-lg-5">
                <p class="mb-5 text-white" style="font-size: 1.1rem; line-height: 1.6; opacity: 0.9;">
                    En Roots Market combinamos la practicidad de un súper tradicional con la tranquilidad de saber que
                    todos nuestros productos están libres de químicos dañinos.<br><br>
                    Aquí encontrarás productos curados con cuidado, precios justos y la comodidad de hacer tu súper
                    completo desde casa, mientras cuidas tu salud y la del planeta.
                </p>

                <div class="row">
                    <div class="col-6">
                        <h2 class="display-4 fw-bold mb-0 text-white">100%</h2>
                        <p class="fs-6 text-white opacity-75">libre de químicos dañinos</p>
                    </div>
                    <div class="col-6">
                        <h2 class="display-4 fw-bold mb-0 text-white">+500</h2>
                        <p class="fs-6 text-white opacity-75">Productos curados y verificados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- ================= HAZ QUE TU COMPRA CUENTE & IMPULSO LOCAL ================= -->
<div class="container section-padding d-none d-lg-block">
    <!-- Title & Description Row -->
    <div class="row mb-5 align-items-end">
        <div class="col-lg-8">
            <h5 class="text-uppercase text-muted mb-2 fw-bold" style="font-size: 0.9rem;">HAZ QUE TU COMPRA CUENTE</h5>
            <h2 class="section-title mb-3" style="font-size: 2.5rem; line-height: 1.2;">En Roots, cada compra
                tiene<br>un propósito.</h2>
            <p class="section-desc mb-0" style="max-width: 600px;">
                Con nuestros programas, transformar tu súper en acciones que cuidan el planeta y apoyan a la comunidad
                es más fácil de lo que imaginas.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
            <a href="iniciativas.php" class="btn rounded-pill px-4 py-2 text-white fw-bold"
                style="background-color: #E67E22; border: none; padding-left: 2rem; padding-right: 2rem;">
                Conoce más <i class="fas fa-chevron-right ms-2"></i>
            </a>
        </div>
    </div>

    <!-- Cards Layout -->
    <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-md-4">
            <a href="iniciativas.php" class="text-decoration-none text-dark d-block">
                <div class="card-feature rounded-4 overflow-hidden position-relative mb-3"
                    style="background-image: url('front/multimedia/r1.png'); height: 250px; background-size: cover; background-position: center;">
                </div>
            </a>
        </div>
        <!-- Card 2 -->
        <div class="col-md-4">
            <a href="iniciativas.php" class="text-decoration-none text-dark d-block">
                <div class="card-feature rounded-4 overflow-hidden position-relative mb-3"
                    style="background-image: url('front/multimedia/r2.png'); height: 250px; background-size: cover; background-position: center;">
                </div>
            </a>
        </div>
        <!-- Card 3 -->
        <div class="col-md-4">
            <a href="impulso_local.php" class="text-decoration-none text-dark d-block">
                <div class="card-feature rounded-4 overflow-hidden position-relative mb-3"
                    style="background-image: url('front/multimedia/r3.png'); height: 250px; background-size: cover; background-position: center;">
                </div>
            </a>
        </div>
    </div>

    <!-- IMPULSO LOCAL SECTION -->
    <div class="row mt-5 pt-5" id="impulso-local-section">
        <!-- Left Column: Title, Text, Button -->
        <div class="col-lg-5 mb-4 mb-lg-0">
            <h3 class="fw-bold mb-3 text-uppercase" style="color: #333;">IMPULSO LOCAL</h3>
            <p class="text-muted fw-bold mb-3" style="font-size: 0.95rem;">
                En Roots creemos en el talento y la calidad mexicana.
            </p>
            <p class="text-muted mb-4" style="line-height: 1.6;">
                Con Impulso Local, cada compra ayuda a pequeñas y medianas marcas del país a crecer y ofrecer productos
                honestos y de confianza para tu día a día.
            </p>
            <a href="impulso_local.php" class="btn rounded-pill px-4 py-2 text-white fw-bold shadow-sm"
                style="background-color: #F39C12; border: none;">
                Compra productos mexicanos <i class="fas fa-chevron-right ms-2"></i>
            </a>
        </div>

        <!-- Right Column: Accordion -->
        <div class="col-lg-7 ps-lg-5">
            <div class="accordion" id="accordionImpulsoLocal">
                <!-- Item 1 -->
                <div class="mb-3 rounded-2 overflow-hidden border border-success border-opacity-25">
                    <div class="p-3 bg-white cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">¿Qué es Impulso Local?</h6>
                            <i class="fas fa-chevron-up text-muted"></i>
                        </div>
                    </div>
                    <div id="collapseOne" class="collapse show" data-bs-parent="#accordionImpulsoLocal">
                        <div class="px-3 pb-3 bg-white text-muted small">
                            Es nuestro programa que apoya marcas mexicanas, para que cada compra impulse la economía
                            local y productos de calidad.
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="mb-3 rounded-2 overflow-hidden border border-success border-opacity-25">
                    <div class="p-3 bg-white cursor-pointer collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-muted">¿Cómo sé que un producto es local?</h6>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="px-3 pb-3 bg-white text-muted small">
                            Busca el distintivo "Impulso Local" en la descripción del producto o usa los filtros en
                            nuestra tienda.
                        </div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="mb-3 rounded-2 overflow-hidden border border-success border-opacity-25">
                    <div class="p-3 bg-white cursor-pointer collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-muted">¿Puedo comprar solo productos locales?</h6>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                    </div>
                    <div id="collapseThree" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="px-3 pb-3 bg-white text-muted small">
                            ¡Sí! Contamos con una sección exclusiva para facilitarte el apoyo al talento nacional.
                        </div>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="mb-3 rounded-2 overflow-hidden border border-success border-opacity-25">
                    <div class="p-3 bg-white cursor-pointer collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-muted">¿Hay beneficios adicionales por comprar local?</h6>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                    </div>
                    <div id="collapseFour" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="px-3 pb-3 bg-white text-muted small">
                            Además de apoyar la economía, reduces la huella de carbono asociada al transporte de larga
                            distancia.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    function scrollCategories(direction) {
        const container = document.getElementById('categoriesCarousel');
        if (container) {
            const scrollAmount = 300; // Adjust scroll distance as needed
            container.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }
    }
</script>