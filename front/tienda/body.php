<?php
// --- 1. CONEXIÓN Y LÓGICA DE BASE DE DATOS ---
require_once(__DIR__ . '/../../back/conection/db.php');

// Configuración de Paginación / Carga
$limit_inicial = 50;
$limit_actual = isset($_GET['ver_hasta']) ? (int) $_GET['ver_hasta'] : $limit_inicial;

// Filtros (Categoría y Búsqueda)
// Filtros (Categoría y Búsqueda)
$categoria_id = isset($_GET['categoria']) ? $_GET['categoria'] : 0;
$busqueda = isset($_GET['q']) ? $_GET['q'] : null;
$filtro_especial = isset($_GET['filter']) ? $_GET['filter'] : null; // Nuevo filtro

// C) OBTENER CONFIGURACIÓN
try {
    $stmt_config = $pdo->query("SELECT clave, valor FROM configuracion");
    $configuracion = $stmt_config->fetchAll(PDO::FETCH_KEY_PAIR);
    $nombre_temporada = $configuracion['nombre_temporada'] ?? 'Temporada';
} catch (PDOException $e) {
    $nombre_temporada = 'Temporada';
}

try {
    // A) OBTENER CATEGORÍAS PARA EL SELECT (Y GRID MOVIL) - Updated to fetch imagen_url
    // Assuming 'imagen_url' exists as per index.php usage.
    $stmt_cat = $pdo->prepare("SELECT id, nombre, imagen_url FROM catalogos WHERE estatus = 'activo' ORDER BY nombre ASC");
    $stmt_cat->execute();
    $categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

    // B) CONSTRUIR CONSULTA DE PRODUCTOS
    $sql_productos = "
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo'
    ";
    $params = [];

    // Filtro de Categoría
    if ($categoria_id > 0) {
        $sql_productos .= " AND p.catalogo_id = ?";
        $params[] = $categoria_id;
    }

    // Filtro de Búsqueda
    if ($busqueda) {
        $sql_productos .= " AND (p.nombre LIKE ? OR p.descripcion_corta LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
    }

    // Nuevo: Filtros Especiales (Temporada / Mejores)
    if ($filtro_especial === 'temporada') {
        $sql_productos .= " AND p.es_temporada = 1";
    } elseif ($filtro_especial === 'mejores') {
        $sql_productos .= " AND p.es_mejor = 1";
    } elseif ($filtro_especial === 'nuevos') { // Mantiene compatibilidad con links del home
        $sql_productos .= " AND p.es_novedad = 1";
    } elseif ($filtro_especial === 'ofertas') { // Mantiene compatibilidad
        $sql_productos .= " AND p.precio_oferta > 0";
    } elseif ($filtro_especial === 'promocion') {
        $sql_productos .= " AND p.es_promocion = 1";
    }


    // Obtener el TOTAL de resultados (para el botón cargar más)
    $stmt_count = $pdo->prepare(str_replace("p.*, \n               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal", "COUNT(*)", $sql_productos));
    $stmt_count->execute($params);
    $total_productos = $stmt_count->fetchColumn();

    // Aplicar Límite y Orden
    $sql_productos .= " ORDER BY p.id DESC LIMIT $limit_actual";

    // Ejecutar consulta final
    $stmt_prod = $pdo->prepare($sql_productos);
    $stmt_prod->execute($params);
    $productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error al cargar productos: " . $e->getMessage();
}

// Pastel Colors Array for Categories
$pastel_colors = ['#E8F5E9', '#FFFDE7', '#FFEBEE', '#E3F2FD', '#F3E5F5', '#E0F2F1'];
?>

<style>
    /* --- ESTILOS GENERALES --- */
    body {
        background-color: #ffffff;
    }

    /* Desktop Styles (Preserved) */
    .header-image {
        height: 300px;
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        margin-bottom: 2rem;
    }

    .header-image img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }

    .overlay-text {
        font-size: 2.5rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        width: 100%;
    }

    .filters-row {
        margin-bottom: 2rem;
    }

    .form-select-custom,
    .form-input-custom {
        border-radius: 50px;
        padding: 12px 20px;
        border: 1px solid #ddd;
        width: 100%;
        outline: none;
        transition: border-color 0.3s;
    }

    .form-select-custom:focus,
    .form-input-custom:focus {
        border-color: #4EAE3E;
        box-shadow: 0 0 0 3px rgba(78, 174, 62, 0.1);
    }

    .product-card-figma {
        border: none;
        background: transparent;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }

    .product-card-figma:hover {
        transform: translateY(-5px);
    }

    .img-placeholder {
        background-color: #FFFFFF;
        border: 1px solid #e0e0e0;
        border-radius: 15px;
        aspect-ratio: 1 / 1.1;
        position: relative;
        overflow: hidden;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .img-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 15px;
    }

    .discount-tag {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #333;
        color: white;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 20px;
        z-index: 2;
    }

    .prod-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .prod-desc {
        font-size: 0.85rem;
        color: #888;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .prod-price {
        font-weight: 700;
        color: #333;
    }

    .old-price {
        text-decoration: line-through;
        color: #aaa;
        font-size: 0.85rem;
        margin-right: 5px;
    }

    .add-btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid #ccc;
        background: white;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .add-btn-icon:hover {
        background-color: #4EAE3E;
        border-color: #4EAE3E;
        color: white;
    }

    .btn-load-more {
        background-color: #f0f0f0;
        color: #333;
        border: none;
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-load-more:hover {
        background-color: #e0e0e0;
        color: #000;
    }

    @media (max-width: 768px) {
        .header-image {
            height: 200px;
        }

        .overlay-text {
            font-size: 1.8rem;
        }
    }

    /* --- MOBILE CATEGORIES STYLES --- */
    .mobile-cat-header {
        background-color: white;
        padding: 40px 20px 15px 20px;
        /* Safe area top padding */
        position: sticky;
        top: 0;
        z-index: 1040;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .mobile-header-row-1 {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        margin-bottom: 15px;
    }

    .mobile-header-row-1-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .entrega-label-dark {
        color: #333;
        /* Dark text for white background */
        font-size: 0.9rem;
        font-weight: 600;
        margin-left: 5px;
    }

    .location-pill-mobile {
        background-color: #f5f5f5;
        /* Light gray for contrast on white header? Or White with shadow? User said "Header... White". Let's use F5F5F5 to pop or White with Shadow. Home used White on Image. Here Header is White. Let's use F9F9F9 with shadow. */
        background-color: #f8f8f8;
        padding: 8px 15px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        /* box-shadow: 0 2px 5px rgba(0,0,0,0.05); */
        border: 1px solid #eee;
        font-weight: 700;
        color: #333;
        font-size: 1rem;
        flex-grow: 1;
        margin-right: 15px;
        min-width: 0;
    }

    /* Mobile Search Pill */
    .mobile-search-pill {
        width: 100%;
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 50px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        color: #999;
    }

    .mobile-search-pill i {
        margin-right: 10px;
        color: #4EAE3E;
    }

    .mobile-search-input {
        border: none;
        outline: none;
        width: 100%;
        background: transparent;
        color: #333;
    }

    /* Category Card Mobile */
    .cat-mobile-card {
        background: white;
        border-radius: 16px;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100px;
        border: 1px solid #f0f0f0;
        /* Subtle border */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        /* Very subtle shadow */
        text-decoration: none;
        color: inherit;
    }

    .cat-mobile-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: #000;
        width: 50%;
        line-height: 1.2;
    }

    .cat-mobile-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: 10px;
    }

    .cat-mobile-circle img {
        width: 70%;
        height: 70%;
        object-fit: contain;
    }

    /* Botones de Filtro Especial */
    .btn-filter-special {
        border-radius: 50px;
        padding: 8px 15px;
        font-weight: 700;
        text-transform: uppercase;
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-filter-special:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        color: white;
    }

    .btn-season {
        background: linear-gradient(45deg, #FF9800, #F57C00);
        color: white;
    }

    .btn-best {
        background: linear-gradient(45deg, #4EAE3E, #2E7D32);
        color: white;
    }

    .btn-season.active,
    .btn-best.active {
        box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2);
        transform: translateY(1px);
        opacity: 0.9;
    }
</style>

<!-- ================= MOBILE VIEW (Categories Grid) ================= -->
<div class="d-lg-none">

    <!-- Mobile Sticky Header -->
    <div class="mobile-cat-header">
        <!-- Row 1: Address + Bell -->
        <div class="mobile-header-row-1">
            <div class="entrega-label-dark">Entrega</div>
            <div class="mobile-header-row-1-content">
                <div class="location-pill-mobile">
                    <i class="fas fa-map-marker-alt" style="color: #4EAE3E; margin-right: 8px;"></i>
                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex-grow: 1;">Casa de
                        Luis</span>
                    <i class="fas fa-chevron-down" style="color: #333; margin-left: 8px; font-size: 0.8rem;"></i>
                </div>
                <!-- Notif Bell -->
                <div
                    style="width: 45px; height: 45px; border-radius: 50%; background: #f8f8f8; display: flex; align-items: center; justify-content: center; border: 1px solid #eee; flex-shrink: 0;">
                    <i class="fas fa-bell" style="color: #4EAE3E; font-size: 1.2rem;"></i>
                </div>
            </div>
        </div>

        <!-- Row 2: Filter Buttons (Mobile) - MOVED UP -->
        <div class="d-flex gap-2 mb-3 overflow-auto pb-1 no-scrollbar" style="white-space: nowrap;">
            <a href="tienda.php?filter=temporada"
                class="btn btn-sm rounded-pill <?php echo ($filtro_especial === 'temporada') ? 'btn-warning text-white' : 'btn-outline-warning text-dark'; ?> fw-bold px-3"
                style="border-width: 2px;">
                <i class="fas fa-sun me-1"></i> <?php echo htmlspecialchars($nombre_temporada); ?>
            </a>
            <a href="tienda.php?filter=mejores"
                class="btn btn-sm rounded-pill <?php echo ($filtro_especial === 'mejores') ? 'btn-success text-white' : 'btn-outline-success text-dark'; ?> fw-bold px-3"
                style="border-width: 2px;">
                <i class="fas fa-star me-1"></i> Mejores
            </a>
            <a href="tienda.php?filter=promocion"
                class="btn btn-sm rounded-pill <?php echo ($filtro_especial === 'promocion') ? 'btn-danger text-white' : 'btn-outline-danger text-dark'; ?> fw-bold px-3"
                style="border-width: 2px;">
                <i class="fas fa-tag me-1"></i> Promociones
            </a>
            <?php if ($filtro_especial): ?>
                <a href="tienda.php" class="btn btn-sm btn-secondary rounded-pill px-3 text-white">
                    <i class="fas fa-times"></i>
                </a>
            <?php endif; ?>
        </div>

        <!-- Row 3: Search Pill -->
        <div class="mb-3">
            <form action="tienda.php" method="GET" class="mobile-search-pill">
                <i class="fas fa-search"></i>
                <input type="text" name="q" class="mobile-search-input" placeholder="Buscar en Roots..."
                    value="<?php echo htmlspecialchars($busqueda ?? ''); ?>">
            </form>
        </div>

        <!-- Row 3: Title -->
        <div>
            <h5 class="fw-bold m-0" style="color: #333;">
                <?php
                $titulo_header = "Todas las categorías";
                if ($busqueda)
                    $titulo_header = "Resultados: " . htmlspecialchars($busqueda);
                elseif ($filtro_especial == 'temporada')
                    $titulo_header = htmlspecialchars($nombre_temporada);
                elseif ($filtro_especial == 'mejores')
                    $titulo_header = "Lo Mejor";
                elseif ($filtro_especial == 'promocion')
                    $titulo_header = "Promociones";
                elseif ($categoria_id > 0) {
                    foreach ($categorias as $c) {
                        if ($c['id'] == $categoria_id) {
                            $titulo_header = $c['nombre'];
                            break;
                        }
                    }
                }
                echo $titulo_header;
                ?>
                <span class="text-muted ms-2"
                    style="font-size: 0.9em; font-weight: 500; color: #666;"><?php echo $total_productos; ?></span>
            </h5>
        </div>
    </div>

    <!-- Category Grid Body -->
    <div class="container pb-5" style="padding-top: 20px;">
        <?php if ($categoria_id == 0 && empty($busqueda) && empty($filtro_especial)): ?>
            <!-- SHOW CATEGORY GRID -->
            <div class="row g-3">
                <?php foreach ($categorias as $index => $cat): ?>
                    <?php
                    $color_bg = $pastel_colors[$index % count($pastel_colors)];
                    ?>
                    <div class="col-6">
                        <a href="tienda.php?categoria=<?php echo $cat['id']; ?>" class="cat-mobile-card">
                            <div class="cat-mobile-name"><?php echo htmlspecialchars($cat['nombre']); ?></div>
                            <div class="cat-mobile-circle" style="background-color: <?php echo $color_bg; ?>;">
                                <?php if (!empty($cat['imagen_url'])): ?>
                                    <img src="<?php echo htmlspecialchars(ltrim($cat['imagen_url'], '/')); ?>" alt="Cat">
                                <?php else: ?>
                                    <img src="front/multimedia/cat_placeholder.png" alt="Cat">
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Spacing for bottom nav -->
            <div style="height: 80px;"></div>

        <?php else: ?>
            <!-- SHOW PRODUCT LIST (Filtered) inside Mobile Layout -->
            <div class="d-flex align-items-center mb-3 px-3">
                <a href="tienda.php" class="text-dark me-3"><i class="fas fa-arrow-left"></i></a>
                <h5 class="m-0 fw-bold">Resultados</h5>
            </div>

            <!-- Render Products Grid Mobile -->
            <div class="row g-2 px-2">
                <?php foreach ($productos as $prod): ?>
                    <div class="col-6">
                        <div class="product-card-figma" style="margin-bottom: 1rem;">
                            <div class="img-placeholder" style="border-radius: 12px; margin-bottom: 0.5rem;">
                                <!-- Discount -->
                                <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                    <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                                    <span class="discount-tag"
                                        style="top:5px; left:5px; font-size:0.6rem;">-<?php echo $descuento; ?>%</span>
                                <?php endif; ?>

                                <a href="producto.php?id=<?php echo $prod['id']; ?>">
                                    <?php if (!empty($prod['imagen_principal'])): ?>
                                        <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_url'] ?? $prod['imagen_principal'], '/')); ?>"
                                            style="padding: 10px;">
                                    <?php else: ?>
                                        <div class="text-secondary text-opacity-25"><i class="fas fa-leaf fa-2x"></i></div>
                                    <?php endif; ?>
                                </a>
                            </div>

                            <h6 class="prod-title" style="font-size: 0.9rem;"><?php echo htmlspecialchars($prod['nombre']); ?>
                            </h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0): ?>
                                        <div style="font-size: 0.8rem; font-weight:700;">
                                            $<?php echo number_format($prod['precio_oferta'], 2); ?>
                                            <span class="text-muted text-decoration-line-through ms-1"
                                                style="font-size: 0.7rem;">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div style="font-size: 0.8rem; font-weight:700;">
                                            $<?php echo number_format($prod['precio_venta'], 2); ?></div>
                                    <?php endif; ?>
                                </div>
                                <button class="add-btn-icon" style="width: 28px; height: 28px;"
                                    onclick="addToCart(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars($prod['nombre']); ?>', <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($prod['imagen_url'] ?? $prod['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>')">
                                    <i class="fas fa-plus" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="height: 40px;"></div>
            <?php if ($total_productos > $limit_actual): ?>
                <div class="text-center mb-5 pb-5">
                    <?php $mostrando = min($limit_actual, $total_productos); ?>
                    <p class="text-muted mb-3 small">Mostrando <?php echo $mostrando; ?> de <?php echo $total_productos; ?>
                        productos</p>

                    <?php
                    // Construir URL para cargar más
                    $params = $_GET;
                    $params['ver_hasta'] = $limit_actual + 50;
                    $new_query_string = http_build_query($params);
                    ?>
                    <a href="?<?php echo $new_query_string; ?>" class="btn btn-outline-success rounded-pill px-4">
                        Cargar más productos
                    </a>
                </div>
            <?php endif; ?>
            <div style="height: 40px;"></div>
        <?php endif; ?>
    </div>
</div>

<!-- ================= DESKTOP VIEW (Existing) ================= -->
<div class="container my-5 d-none d-lg-block">

    <h2 class="text-center fw-bold mb-4" style="color: #333;">Bienvenido a tu espacio orgánico</h2>
    <hr class="mb-5">

    <!-- FILTERS ROW with BUTTONS INLINE -->
    <div class="row filters-row gx-3 gy-3 align-items-center">
        <!-- Categories Select -->
        <div class="col-md-3">
            <select class="form-select-custom" id="filtroCategoria" onchange="aplicarFiltros()">
                <option value="0">Todas las Categorías</option>
                <?php foreach ($categorias as $cat): ?>
                    <?php $selected = ($cat['id'] == $categoria_id) ? 'selected' : ''; ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($cat['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Search Input -->
        <div class="col-md-5">
            <input type="text" class="form-input-custom" id="buscadorTienda"
                placeholder="Busca tu producto por nombre..." value="<?php echo htmlspecialchars($busqueda ?? ''); ?>">
        </div>

        <!-- Filter Buttons (Same Row) -->
        <div class="col-md-4 d-flex justify-content-end gap-2">
            <a href="tienda.php?filter=temporada"
                class="btn-filter-special btn-season <?php echo ($filtro_especial === 'temporada') ? 'active' : ''; ?>">
                <i class="fas fa-sun"></i> <?php echo htmlspecialchars($nombre_temporada); ?>
            </a>
            <a href="tienda.php?filter=mejores"
                class="btn-filter-special btn-best <?php echo ($filtro_especial === 'mejores') ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Mejores
            </a>
            <a href="tienda.php?filter=promocion"
                class="btn-filter-special btn-danger <?php echo ($filtro_especial === 'promocion') ? 'active' : 'text-danger bg-white border-danger'; ?> rounded-pill"
                style="border: 2px solid #dc3545;">
                <i class="fas fa-tag"></i> Promociones
            </a>
            <?php if ($filtro_especial): ?>
                <a href="tienda.php" class="btn btn-outline-secondary rounded-pill d-flex align-items-center"
                    style="padding: 8px 15px; font-size: 0.9rem;">
                    <i class="fas fa-times"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $prod): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card-figma">
                        <div class="img-placeholder">
                            <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                                <span class="discount-tag">-<?php echo $descuento; ?>%</span>
                            <?php endif; ?>

                            <a href="producto.php?id=<?php echo $prod['id']; ?>">
                                <?php if (!empty($prod['imagen_principal'])): ?>
                                    <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_url'] ?? $prod['imagen_principal'], '/')); ?>"
                                        alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                                <?php else: ?>
                                    <div class="text-secondary text-opacity-25"><i class="fas fa-leaf fa-3x"></i></div>
                                <?php endif; ?>
                            </a>
                        </div>

                        <h5 class="prod-title">
                            <a href="producto.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($prod['nombre']); ?>
                            </a>
                        </h5>
                        <p class="prod-desc"><?php echo htmlspecialchars($prod['descripcion_corta'] ?? ''); ?></p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php if (($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0): ?>
                                    <span class="old-price">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                    <span class="prod-price">$<?php echo number_format($prod['precio_oferta'], 2); ?></span>
                                <?php else: ?>
                                    <span class="prod-price">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                <?php endif; ?>
                            </div>

                            <button class="add-btn-icon" onclick="addToCart(
                                        <?php echo $prod['id']; ?>,
                                        '<?php echo htmlspecialchars($prod['nombre']); ?>',
                                        <?php echo $prod['precio_oferta'] ?: $prod['precio_venta']; ?>,
                                        '<?php echo htmlspecialchars(ltrim($prod['imagen_url'] ?? $prod['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>'
                                    )">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">No se encontraron productos con estos criterios.</h4>
                <a href="tienda.php" class="btn btn-outline-success mt-3 rounded-pill px-4">Ver todos</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($total_productos > $limit_actual): ?>
        <div class="text-center mt-5 mb-5">
            <?php $mostrando = min($limit_actual, $total_productos); ?>
            <p class="text-muted mb-3 small">Mostrando <?php echo $mostrando; ?> de <?php echo $total_productos; ?>
                productos</p>

            <div class="progress mb-4 mx-auto" style="height: 4px; max-width: 200px; background-color: #e9ecef;">
                <div class="progress-bar bg-dark" role="progressbar"
                    style="width: <?php echo ($mostrando / $total_productos) * 100; ?>%;"></div>
            </div>

            <?php
            // Construir URL para cargar más
            $params = $_GET;
            $params['ver_hasta'] = $limit_actual + 50;
            $new_query_string = http_build_query($params);
            ?>
            <a href="?<?php echo $new_query_string; ?>" class="btn-load-more">
                Cargar más productos
            </a>
        </div>
    <?php endif; ?>

</div>

<script>
    let timeout = null;

    // Detectar tecla Enter o pausa al escribir en el buscador
    const inputBuscador = document.getElementById('buscadorTienda');

    if (inputBuscador) {
        inputBuscador.addEventListener('keyup', function (e) {
            // Si presiona Enter, busca inmediatamente
            if (e.key === 'Enter') {
                aplicarFiltros();
                return;
            }

            // Si no, espera 800ms a que termine de escribir (Debounce)
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                aplicarFiltros();
            }, 800);
        });
    }

    function aplicarFiltros() {
        const categoriaId = document.getElementById('filtroCategoria') ? document.getElementById('filtroCategoria').value : 0;
        const textoBusqueda = document.getElementById('buscadorTienda').value;

        // Construir la URL con los parámetros
        let url = 'tienda.php?';

        if (categoriaId > 0) {
            url += 'categoria=' + categoriaId + '&';
        }

        if (textoBusqueda.trim() !== '') {
            url += 'q=' + encodeURIComponent(textoBusqueda.trim()) + '&';
        }

        // Redirigir (Esto hace la búsqueda en el servidor para soportar 500k productos)
        window.location.href = url;
    }
</script>