<?php
// --- 1. CONEXIÓN Y LÓGICA DE BASE DE DATOS ---
require_once(__DIR__ . '/../../back/conection/db.php');

// Configuración de Paginación / Carga
$limit_inicial = 50;
$limit_actual = isset($_GET['ver_hasta']) ? (int)$_GET['ver_hasta'] : $limit_inicial;

// Filtros (Categoría y Búsqueda)
$categoria_id = isset($_GET['categoria']) ? $_GET['categoria'] : 0;
$busqueda = isset($_GET['q']) ? $_GET['q'] : null;

try {
    // A) OBTENER CATEGORÍAS PARA EL SELECT
    $stmt_cat = $pdo->prepare("SELECT id, nombre FROM catalogos WHERE estatus = 'activo' ORDER BY nombre ASC");
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
?>

<style>
    /* --- ESTILOS GENERALES --- */
    body { background-color: #ffffff; }

    /* Header Image (Estilo Version 1) */
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
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        width: 100%;
    }

    /* Filtros (Select y Buscador) */
    .filters-row {
        margin-bottom: 2rem;
    }
    .form-select-custom, .form-input-custom {
        border-radius: 50px;
        padding: 12px 20px;
        border: 1px solid #ddd;
        width: 100%;
        outline: none;
        transition: border-color 0.3s;
    }
    .form-select-custom:focus, .form-input-custom:focus {
        border-color: #4EAE3E;
        box-shadow: 0 0 0 3px rgba(78, 174, 62, 0.1);
    }

    /* Tarjetas de Producto (Estilo Blanco Limpio) */
    .product-card-figma {
        border: none;
        background: transparent;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }
    .product-card-figma:hover { transform: translateY(-5px); }

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
        top: 10px; left: 10px;
        background-color: #333;
        color: white;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 20px;
        z-index: 2;
    }

    .prod-title {
        font-size: 1rem; font-weight: 600; color: #333;
        margin-bottom: 0.2rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .prod-desc {
        font-size: 0.85rem; color: #888;
        margin-bottom: 0.5rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .prod-price { font-weight: 700; color: #333; }
    .old-price { text-decoration: line-through; color: #aaa; font-size: 0.85rem; margin-right: 5px; }

    .add-btn-icon {
        width: 32px; height: 32px;
        border-radius: 50%; border: 1px solid #ccc;
        background: white; color: #333;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .add-btn-icon:hover { background-color: #4EAE3E; border-color: #4EAE3E; color: white; }

    .btn-load-more {
        background-color: #f0f0f0; color: #333;
        border: none; padding: 12px 40px;
        border-radius: 50px; font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-load-more:hover { background-color: #e0e0e0; color: #000; }

    @media (max-width: 768px) {
        .header-image { height: 200px; }
        .overlay-text { font-size: 1.8rem; }
    }
</style>

<div class="container my-5">



    <h2 class="text-center fw-bold mb-4" style="color: #333;">Bienvenido a tu espacio orgánico</h2>
    <hr class="mb-5">

    <div class="row filters-row gx-3 gy-3">
        <div class="col-md-4">
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
        <div class="col-md-8">
            <input type="text" class="form-input-custom" id="buscadorTienda"
                   placeholder="Busca tu producto por nombre..."
                   value="<?php echo htmlspecialchars($busqueda ?? ''); ?>">
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $prod): ?>
                <div class="col-6 col-md-4 col-lg-3"> <div class="product-card-figma">
                        <div class="img-placeholder">
                            <?php if ($prod['precio_oferta'] > 0 && $prod['precio_oferta'] < $prod['precio_venta']): ?>
                                <?php $descuento = round((($prod['precio_venta'] - $prod['precio_oferta']) / $prod['precio_venta']) * 100); ?>
                                <span class="discount-tag">-<?php echo $descuento; ?>%</span>
                            <?php endif; ?>

                            <a href="producto.php?id=<?php echo $prod['id']; ?>">
                                <?php if (!empty($prod['imagen_principal'])): ?>
                                    <img src="<?php echo htmlspecialchars(ltrim($prod['imagen_url'] ?? $prod['imagen_principal'], '/')); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
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
                                <?php if ($prod['precio_oferta']): ?>
                                    <span class="old-price">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                    <span class="prod-price">$<?php echo number_format($prod['precio_oferta'], 2); ?></span>
                                <?php else: ?>
                                    <span class="prod-price">$<?php echo number_format($prod['precio_venta'], 2); ?></span>
                                <?php endif; ?>
                            </div>

                            <button class="add-btn-icon"
                                    onclick="addToCart(
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
            <p class="text-muted mb-3 small">Mostrando <?php echo $mostrando; ?> de <?php echo $total_productos; ?> productos</p>

            <div class="progress mb-4 mx-auto" style="height: 4px; max-width: 200px; background-color: #e9ecef;">
                <div class="progress-bar bg-dark" role="progressbar" style="width: <?php echo ($mostrando / $total_productos) * 100; ?>%;"></div>
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

    inputBuscador.addEventListener('keyup', function(e) {
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

    function aplicarFiltros() {
        const categoriaId = document.getElementById('filtroCategoria').value;
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