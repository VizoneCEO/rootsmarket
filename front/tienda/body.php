<?php
// --- 1. CONEXIÓN Y LÓGICA DE BASE DE DATOS ---
require_once(__DIR__ . '/../../back/conection/db.php');

// Configuración de Paginación
$limit_inicial = 50;
$limit_actual = isset($_GET['ver_hasta']) ? (int)$_GET['ver_hasta'] : $limit_inicial;

// Filtros
$categoria_id = isset($_GET['categoria']) ? $_GET['categoria'] : null;
// Nota: 'q' debe coincidir con el name o id que usaremos en el JS para actualizar la URL
$busqueda = isset($_GET['q']) ? $_GET['q'] : null;

try {
    // A) OBTENER CATEGORÍAS (Para el Sidebar)
    $stmt_cat = $pdo->prepare("
        SELECT c.id, c.nombre, COUNT(p.id) as total_productos 
        FROM catalogos c 
        LEFT JOIN productos p ON c.id = p.catalogo_id AND p.estatus = 'activo'
        WHERE c.estatus = 'activo' 
        GROUP BY c.id 
        ORDER BY c.nombre ASC
    ");
    $stmt_cat->execute();
    $categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

    // B) OBTENER PRODUCTOS
    $sql_productos = "
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo'
    ";
    $params = [];

    // Filtro de Categoría
    if ($categoria_id) {
        $sql_productos .= " AND p.catalogo_id = ?";
        $params[] = $categoria_id;
    }

    // Filtro de Búsqueda
    if ($busqueda) {
        $sql_productos .= " AND (p.nombre LIKE ? OR p.descripcion_corta LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
    }

    // Contar total para la paginación
    $stmt_count = $pdo->prepare(str_replace(
        "p.*, \n               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal",
        "COUNT(*)",
        $sql_productos
    ));
    $stmt_count->execute($params);
    $total_productos = $stmt_count->fetchColumn();

    // Aplicar Límite
    $sql_productos .= " ORDER BY p.id DESC LIMIT $limit_actual";

    $stmt_prod = $pdo->prepare($sql_productos);
    $stmt_prod->execute($params);
    $productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<style>
    /* --- ESTILOS TIENDA --- */
    body { background-color: #ffffff; }

    /* Sidebar */
    .sidebar-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #333;
        margin-bottom: 1.5rem;
        padding-left: 10px;
        border-left: 3px solid #4EAE3E;
    }
    .cat-list a {
        display: flex;
        justify-content: space-between;
        color: #666;
        text-decoration: none;
        padding: 8px 0;
        font-size: 0.95rem;
        transition: color 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }
    .cat-list a:hover, .cat-list a.active {
        color: #4EAE3E;
        font-weight: 600;
    }

    /* Header Sección */
    .shop-header h2 { font-weight: 700; color: #333; margin-bottom: 0.5rem; }
    .shop-header p { color: #888; font-size: 0.9rem; }

    /* Tarjetas de Producto (Fondo Blanco) */
    .product-card-figma {
        border: none;
        background: transparent;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }
    .product-card-figma:hover { transform: translateY(-5px); }

    .img-placeholder {
        background-color: #FFFFFF; /* Blanco */
        border: 1px solid #e0e0e0; /* Borde sutil */
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
        padding: 15px; /* Espacio para que no toque el borde */
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
</style>

<div class="container my-5">
    <div class="row">

        <div class="col-lg-3 mb-5">
            <h5 class="sidebar-title">Categorías</h5>
            <div class="cat-list">
                <a href="tienda.php" class="<?php echo ($categoria_id === null) ? 'active' : ''; ?>">
                    <span>Ver todo</span>
                    <i class="fas fa-chevron-right small mt-1"></i>
                </a>
                <?php foreach ($categorias as $cat): ?>
                    <a href="tienda.php?categoria=<?php echo $cat['id']; ?>" class="<?php echo ($categoria_id == $cat['id']) ? 'active' : ''; ?>">
                        <span><?php echo htmlspecialchars($cat['nombre']); ?></span>
                        <span class="text-muted small">(<?php echo $cat['total_productos']; ?>)</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-9">

            <div class="shop-header mb-4">
                <h2>Nuestros productos</h2>
                <p>
                    <?php
                    if ($total_productos > 0) {
                        $mostrando = min($limit_actual, $total_productos);
                        echo "Mostrando 1-$mostrando de $total_productos resultados";
                    } else {
                        echo "No se encontraron productos.";
                    }
                    ?>
                </p>
            </div>

            <div id="product-grid-container">
                <div class="row g-4">
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                            <div class="col-6 col-md-4">
                                <div class="product-card-figma">
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
    <i class="fas fa-plus">s</i>
</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <h4 class="text-muted">No se encontraron productos.</h4>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="load-more-container">
                <?php if ($total_productos > $limit_actual): ?>
                    <div class="text-center mt-5 mb-5">
                        <p class="text-muted mb-3 small">Has visto <?php echo $mostrando; ?> de <?php echo $total_productos; ?> productos</p>
                        <div class="progress mb-4 mx-auto" style="height: 4px; max-width: 200px; background-color: #e9ecef;">
                            <div class="progress-bar bg-dark" role="progressbar" style="width: <?php echo ($mostrando / $total_productos) * 100; ?>%;"></div>
                        </div>
                        <?php
                            $params = $_GET;
                            $params['ver_hasta'] = $limit_actual + 50;
                            $new_query_string = http_build_query($params);
                        ?>
                        <a href="?<?php echo $new_query_string; ?>" class="btn-load-more">Cargar más productos</a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // CORRECCIÓN: Usamos querySelectorAll para obtener AMBOS inputs (escritorio y móvil)
    const searchInputs = document.querySelectorAll('.search-input');

    const productGrid = document.getElementById('product-grid-container');
    const loadMore = document.getElementById('load-more-container');
    let timeout = null;

    if (searchInputs.length > 0) {
        // Iteramos sobre cada input encontrado para agregarle el evento
        searchInputs.forEach(input => {
            input.addEventListener('keyup', function() {
                clearTimeout(timeout);
                const query = this.value;

                // Sincronizar el texto en el otro buscador para que se vea igual
                searchInputs.forEach(otherInput => {
                    if(otherInput !== this) otherInput.value = query;
                });

                timeout = setTimeout(() => {
                    productGrid.style.opacity = '0.5';

                    const url = new URL(window.location.href);
                    url.searchParams.set('q', query);
                    url.searchParams.delete('ver_hasta');

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            const newGrid = doc.getElementById('product-grid-container').innerHTML;
                            const newLoadMore = doc.getElementById('load-more-container').innerHTML;

                            productGrid.innerHTML = newGrid;
                            loadMore.innerHTML = newLoadMore;

                            productGrid.style.opacity = '1';
                            window.history.pushState({}, '', url);
                        })
                        .catch(err => {
                            console.error('Error en búsqueda:', err);
                            productGrid.style.opacity = '1';
                        });
                }, 300);
            });
        });
    }
});
</script>