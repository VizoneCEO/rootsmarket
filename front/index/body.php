<?php
// 1. Conexión a la base de datos
require_once(__DIR__ . '/../../back/conection/db.php');

// 2. Consultas para obtener datos reales

// A) Obtener Categorías (Limitamos a 4 para el diseño de la home)
try {
    $stmt_cat = $pdo->prepare("SELECT * FROM catalogos WHERE estatus = 'activo' ORDER BY id ASC LIMIT 4");
    $stmt_cat->execute();
    $categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categorias = []; // Fallback vacío si hay error
}

// B) Obtener Productos "Top" (Lo Mejor de Roots)
// Criterio: Productos activos, ordenados por calificación o ID, limitados a 4
try {
    // Nota: Ajustamos la consulta para obtener la imagen principal de la tabla relacionada
    $stmt_prod = $pdo->prepare("
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo' 
        ORDER BY p.calificacion DESC, p.id DESC 
        LIMIT 4
    ");
    $stmt_prod->execute();
    $productos_top = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productos_top = [];
}
?>

<style>
    /* --- HERO SECTION --- */
    .hero-section {
        background-color: #666666;
        height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        padding: 20px;
        /* Ajuste de ruta para la imagen de fondo (sin barra inicial) */
        background-image: url('front/multimedia/fondo1.png');
        background-size: cover;
        background-position: center;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        max-width: 600px;
        color: #E0E0E0;
    }

    /* --- SECCIONES GENERALES --- */
    .section-padding { padding: 4rem 0; }

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

    /* --- CARDS GRISES (BENTO GRID) --- */
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
    .gray-card:hover { transform: translateY(-5px); color: white; }
    .card-tall { min-height: 520px; }
    .card-medium { min-height: 250px; }

    /* --- CATEGORIAS --- */
    /* --- ACTUALIZACIÓN CATEGORÍAS (ESTILO FIGMA) --- */
    .cat-card {
        background-color: #f0f0f0; /* Color de fondo por si la imagen tarda en cargar */
        border-radius: 28px; /* Bordes muy redondeados como en el diseño */
        height: 420px; /* Altura fija para que se vean altas y elegantes */
        position: relative;
        margin-bottom: 1.2rem;
        overflow: hidden; /* Para que la imagen respete el borde redondeado */
        box-shadow: 0 4px 15px rgba(0,0,0,0.03); /* Sombra muy sutil */
        display: block;
    }

    .cat-card img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* La imagen llena todo el cuadro sin deformarse */
        transition: transform 0.5s ease;
    }

    .cat-card:hover img {
        transform: scale(1.03); /* Zoom suave al pasar el mouse */
    }

    .cat-label {
        text-align: center;
        color: #333;
        font-weight: 500;
        font-size: 1.2rem; /* Texto un poco más grande */
        margin-top: 5px;
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
        background-color: #F9F9F9; /* Fondo gris muy claro para producto */
        border-radius: 20px;
        height: 300px;
        position: relative;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .product-placeholder img {
        max-height: 80%;
        max-width: 80%;
        object-fit: contain;
        mix-blend-mode: multiply; /* Ayuda si la imagen tiene fondo blanco */
    }
    .discount-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        z-index: 10;
    }
    .add-btn-circle {
        border: 1px solid #333;
        background: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .add-btn-circle:hover {
        background-color: #333;
        color: white;
    }
</style>

<div class="hero-section">
    <h1 class="hero-title">Compra con Propósito</h1>
    <p class="hero-subtitle">
        Todo lo que necesitas para tu día a día, libre de químicos dañinos.<br>
        Saludable, confiable y al alcance de un clic.
    </p>
</div>

<div class="container section-padding">
    <div class="text-center mb-5">
        <h2 class="section-title">Novedades y Promos de la Semana</h2>
        <p class="section-desc">Encuentra descuentos, nuevos productos y ediciones limitadas,<br>todos con la garantía de estar libres de químicos dañinos.</p>
        <a href="tienda.php" class="btn-dark-pill">Empieza tu súper <i class="fas fa-chevron-right ms-2"></i></a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="tienda.php?cat=temporada" class="gray-card card-tall"
               style="background-image: url('front/multimedia/d1.png'); position: relative;">
                <span class="position-absolute bottom-0 start-0 m-4 text-white fw-bold text-uppercase" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Temporada</span>
            </a>
        </div>

        <div class="col-md-4">
            <div class="d-flex flex-column h-100 gap-4">
                <a href="tienda.php?filter=nuevos" class="gray-card card-medium"
                   style="background-image: url('front/multimedia/d2.png'); position: relative;">
                    <span class="position-absolute bottom-0 start-0 m-4 text-white fw-bold text-uppercase" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Nuevos Productos</span>
                </a>
                <a href="nosotros.php" class="gray-card card-medium"
                   style="background-image: url('front/multimedia/d3.png'); position: relative;">
                    <span class="position-absolute bottom-0 start-0 m-4 text-white fw-bold text-uppercase" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Campañas de impacto</span>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <a href="tienda.php?filter=ofertas" class="gray-card card-tall"
               style="background-image: url('front/multimedia/d4.png'); position: relative;">
                <span class="position-absolute bottom-0 start-0 m-4 text-white fw-bold text-uppercase" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Descuentos</span>
            </a>
        </div>
    </div>
</div>


<div class="container section-padding">

    <div class="row align-items-end mb-5">
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

    <div class="row g-4">

        <div class="col-6 col-md-3">
            <a href="tienda.php?cat=frutas" class="text-decoration-none">
                <div class="cat-card">
                    <img src="front/multimedia/t1.png" alt="Frutas y verduras">
                </div>
                <p class="cat-label">Frutas y verduras</p>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="tienda.php?cat=pan" class="text-decoration-none">
                <div class="cat-card">
                    <img src="front/multimedia/t2.png" alt="Pan, Cereales y Granos">
                </div>
                <p class="cat-label">Pan, Cereales y Granos</p>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="tienda.php?cat=limpieza" class="text-decoration-none">
                <div class="cat-card">
                    <img src="front/multimedia/t3.png" alt="Limpieza del Hogar">
                </div>
                <p class="cat-label">Limpieza del Hogar</p>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="tienda.php?cat=carnes" class="text-decoration-none">
                <div class="cat-card">
                    <img src="front/multimedia/t4.png" alt="Carnes, Pescados y Huevo">
                </div>
                <p class="cat-label">Carnes, Pescados y Huevo</p>
            </a>
        </div>

    </div>
</div>

<div class="container section-padding">
    <div class="mb-4">
        <h2 class="section-title">Lo Mejor de Roots</h2>
        <p class="section-desc">Desde los más vendidos hasta los favoritos de Roots.</p>
    </div>

    <div class="row g-4">
        <?php if (!empty($productos_top)): ?>
            <?php foreach ($productos_top as $prod): ?>
                <div class="col-6 col-md-3">
                    <div class="product-card-minimal">
                        <div class="product-placeholder">
                            <?php if ($prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                <?php
                                    $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100);
                                ?>
                                <span class="discount-badge">-<?php echo $descuento; ?>%</span>
                            <?php endif; ?>

                            <a href="producto.php?id=<?php echo $prod['id']; ?>">
                                <?php if (!empty($prod['imagen_principal'])): ?>
                                    <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_principal'], '/')); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                                <?php else: ?>
                                    <img src="front/multimedia/productos.png" alt="Producto sin imagen">
                                <?php endif; ?>
                            </a>
                        </div>
                        <h5 class="fw-normal mb-1 text-truncate">
                            <a href="producto.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($prod['nombre']); ?>
                            </a>
                        </h5>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <?php if ($prod['precio_oferta']): ?>
                                    <span class="text-muted text-decoration-line-through small me-1">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                    <span class="fw-bold">$<?php echo number_format($prod['precio_oferta'], 2); ?></span>
                                <?php else: ?>
                                    <span class="fw-bold">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="add-btn-circle"
        onclick="addToCart(
            <?php echo $prod['id']; ?>,
            '<?php echo htmlspecialchars($prod['nombre']); ?>',
            <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>,
            '<?php echo htmlspecialchars(ltrim($prod['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>'
        )">
    <i class="fas fa-plus"></i>
</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">Aún no hay productos destacados.</div>
        <?php endif; ?>
    </div>
</div>




<div class="container mb-5">
    <div class="p-5 rounded-3 text-white d-flex align-items-center"
         style="
            background-image: url('front/multimedia/fondo3.png');
            background-size: cover;
            background-position: center;
            min-height: 450px;
            position: relative;
            overflow: hidden;
         ">

        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.2); z-index: 1;"></div>

        <div class="row w-100 position-relative" style="z-index: 2;">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h2 class="fw-bold display-5 mb-4 text-uppercase text-shadow" style="line-height: 1.1;">
                    Lo que nos hace<br>diferente
                </h2>
                <a href="registro.php" class="btn rounded-pill px-4 py-2 fw-bold text-white"
                   style="background-color: #E67E22; border: none; padding: 12px 30px;">
                    Únete a la comunidad Roots <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>

            <div class="col-lg-7 ps-lg-5">
                <p class="mb-4 text-shadow" style="font-size: 1rem; line-height: 1.6; font-weight: 500;">
                    En Roots Market combinamos la practicidad de un súper tradicional con la
                    tranquilidad de saber que todos nuestros productos están libres de químicos dañinos.
                </p>
                <p class="mb-5 text-shadow" style="font-size: 1rem; line-height: 1.6; font-weight: 500;">
                    Aquí encontrarás productos curados con cuidado, precios justos y la comodidad
                    de hacer tu súper completo desde casa, mientras cuidas tu salud y la del planeta.
                </p>

                <div class="row">
                    <div class="col-6">
                        <h3 class="fw-bold display-5 mb-0 text-shadow">100%</h3>
                        <p class="small text-uppercase fw-bold text-shadow">libre de químicos dañinos</p>
                    </div>
                    <div class="col-6">
                        <h3 class="fw-bold display-5 mb-0 text-shadow">+500</h3>
                        <p class="small text-uppercase fw-bold text-shadow">Productos curados y verificados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-shadow {
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    }
</style>


<style>
    .impact-section { padding: 4rem 0; background-color: #fff; }
    .impact-card { background-color: #E0E0E0; border-radius: 20px; height: 300px; width: 100%; margin-bottom: 1rem; position: relative; overflow: hidden; }
    .impact-title { font-weight: 500; font-size: 1.1rem; color: #333; margin-top: 10px;}
    .local-impulse-section { padding: 4rem 0; background-color: #fff; }
    .faq-card { border: 1px solid #e0e0e0; border-radius: 10px; margin-bottom: 1rem; overflow: hidden; }
    .faq-header { background-color: #fff; padding: 1.5rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
    .faq-title { font-size: 1.1rem; margin: 0; color: #333; font-weight: 500; }
    .faq-body { padding: 0 1.5rem 1.5rem 1.5rem; color: #666; line-height: 1.6; }
    .chevron-icon { transition: transform 0.3s ease; }
    .collapsed .chevron-icon { transform: rotate(180deg); }
</style>


<div class="container impact-section mb-5" id="iniciativas">

    <div class="row align-items-end mb-5">
        <div class="col-md-7">
            <h2 class="section-title mb-3" style="font-size: 2rem; letter-spacing: 0.5px;">HAZ QUE TU COMPRA CUENTE</h2>
            <p class="section-desc mb-0" style="font-size: 1rem; line-height: 1.5;">
                En Roots, cada compra tiene un propósito.<br>
                Con nuestros programas, transformar tu súper en acciones que<br>
                cuidan el planeta y apoyan a la comunidad es más fácil de lo que imaginas.
            </p>
        </div>
        <div class="col-md-5 text-md-end mt-4 mt-md-0">
            <a href="nosotros.php" class="btn rounded-pill px-4 py-2 fw-bold text-white"
               style="background-color: #E67E22; border: none; padding: 10px 25px;">
                Conoce más <i class="fas fa-chevron-right ms-2"></i>
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="impact-card" style="
                background-image: url('front/multimedia/r1.png');
                background-size: cover;
                background-position: center;
                height: 280px;
                border-radius: 24px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            "></div>
            <p class="impact-title mt-3" style="font-weight: 600; color: #333; font-size: 1.1rem;">Raíces Verdes</p>
        </div>

        <div class="col-md-4">
            <div class="impact-card" style="
                background-image: url('front/multimedia/r2.png');
                background-size: cover;
                background-position: center;
                height: 280px;
                border-radius: 24px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            "></div>
            <p class="impact-title mt-3" style="font-weight: 600; color: #333; font-size: 1.1rem;">Cero Basura</p>
        </div>

        <div class="col-md-4">
            <div class="impact-card" style="
                background-image: url('front/multimedia/r3.png');
                background-size: cover;
                background-position: center;
                height: 280px;
                border-radius: 24px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            "></div>
            <p class="impact-title mt-3" style="font-weight: 600; color: #333; font-size: 1.1rem;">Impulso Local</p>
        </div>

    </div>
</div>


<style>
    .local-impulse-section {
        padding: 5rem 0;
        background-color: #fff;
    }
    /* Tarjetas del acordeón con borde VERDE */
    .faq-card {
        border: 1px solid #4EAE3E; /* Color verde Roots */
        border-radius: 8px;
        margin-bottom: 1rem;
        overflow: hidden;
        background-color: #fff;
    }
    .faq-header {
        padding: 1.2rem 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        transition: background-color 0.2s;
    }
    .faq-header:hover {
        background-color: #f9f9f9;
    }
    .faq-title {
        font-size: 1.1rem;
        margin: 0;
        color: #333; /* Texto oscuro */
        font-weight: 500;
    }
    .faq-body {
        padding: 0 1.5rem 1.5rem 1.5rem;
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
    }
    .chevron-icon {
        color: #333;
        transition: transform 0.3s ease;
    }
    /* Rotar flecha cuando está colapsado (ajusta según tu preferencia inicial) */
    .collapsed .chevron-icon {
        transform: rotate(180deg);
    }
</style>

<div class="container local-impulse-section">
    <div class="row gx-5 align-items-start">
        <div class="col-lg-5 mb-5 mb-lg-0 pt-2">
            <h2 class="fw-bold text-uppercase mb-4" style="font-size: 2rem; color: #333;">IMPULSO LOCAL</h2>
            <p class="text-muted mb-3">En Roots creemos en el talento y la calidad mexicana.</p>
            <p class="text-muted mb-5" style="line-height: 1.6;">
                Con Impulso Local, cada compra ayuda a pequeñas y medianas marcas del país a crecer y ofrecer
                productos honestos y de confianza para tu día a día.
            </p>

            <a href="tienda.php?origen=local" class="btn rounded-pill px-4 py-3 fw-bold text-white"
               style="background-color: #E67E22; border: none; width: fit-content; padding-left: 30px; padding-right: 30px;">
                Compra productos mexicanos <i class="fas fa-chevron-right ms-2"></i>
            </a>
        </div>

        <div class="col-lg-7">
            <div class="accordion" id="accordionImpulsoLocal">

                <div class="faq-card">
                    <div class="faq-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5 class="faq-title">¿Qué es Impulso Local?</h5>
                        <i class="fas fa-chevron-up chevron-icon"></i>
                    </div>
                    <div id="collapseOne" class="collapse show" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            Es nuestro programa que apoya marcas mexicanas, para que cada compra impulse la economía local y productos de calidad.
                        </div>
                    </div>
                </div>

                <div class="faq-card">
                    <div class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h5 class="faq-title">¿Cómo sé que un producto es local?</h5>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div id="collapseTwo" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            Buscamos identificar claramente estos productos con un sello distintivo en nuestra tienda para que puedas reconocerlos fácilmente.
                        </div>
                    </div>
                </div>

                <div class="faq-card">
                    <div class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h5 class="faq-title">¿Puedo comprar solo productos locales?</h5>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div id="collapseThree" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            ¡Claro! Puedes usar nuestros filtros de búsqueda para ver exclusivamente productos de nuestros socios locales.
                        </div>
                    </div>
                </div>

                <div class="faq-card">
                    <div class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <h5 class="faq-title">¿Hay beneficios adicionales por comprar local?</h5>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div id="collapseFour" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            Sí, a menudo tenemos promociones especiales para incentivar el apoyo a marcas nacionales y fortalecer la comunidad.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>