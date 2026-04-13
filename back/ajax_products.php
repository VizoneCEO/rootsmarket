<?php
// back/ajax_products.php
require_once(__DIR__ . '/conection/db.php');

// Validar solicitud AJAX (opcional pero recomendado)
// if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
//     die('Acceso denegado');
// }

// Obtener parámetros
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50;
$categoria_id = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;
$busqueda = isset($_GET['q']) ? $_GET['q'] : null;
$filtro_especial = isset($_GET['filter']) ? $_GET['filter'] : null;

try {
    // Construir consulta base (MISMA LOGICA QUE EN body.php)
    $sql_productos = "
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo'
    ";
    $params = [];

    // Filtros
    if ($categoria_id > 0) {
        $sql_productos .= " AND p.catalogo_id = ?";
        $params[] = $categoria_id;
    }

    if ($busqueda) {
        $sql_productos .= " AND (p.nombre LIKE ? OR p.descripcion_corta LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
    }

    if ($filtro_especial === 'temporada') {
        $sql_productos .= " AND p.es_temporada = 1";
    } elseif ($filtro_especial === 'mejores') {
        $sql_productos .= " AND p.es_mejor = 1";
    } elseif ($filtro_especial === 'nuevos') {
        $sql_productos .= " AND p.es_novedad = 1";
    } elseif ($filtro_especial === 'ofertas') {
        $sql_productos .= " AND p.precio_oferta > 0";
    } elseif ($filtro_especial === 'promocion') {
        $sql_productos .= " AND p.es_promocion = 1";
    }

    // Orden y Límite
    $sql_productos .= " ORDER BY p.id DESC LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($sql_productos);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generar HTML de respuesta
    if (!empty($productos)) {
        foreach ($productos as $prod) {
            // Replicar estructura de tarjeta (Desktop y Mobile comparten clases similares en body.php, 
            // pero body.php tiene lógica separada para Desktop y Mobile. 
            // Para AJAX, lo ideal es retornar un HTML que sea adaptable O retornar un JSON.
            // Dado que el usuario pidió "load more", asumo que usamos el mismo grid.
            // body.php usa "product-card-figma" tanto en mobile (dentro de result list) como desktop.
            // Vamos a usar la estructura de Desktop que es la más completa y 'responsive' col-Classes.

            // NOTA: En mobile (line 527) usa col-6. En desktop (line 648) usa col-6 col-md-4 col-lg-3.
            // El JS se encargará de inyectarlo en el contenedor correcto. Si el contenedor tiene row, las cols funcionan.

            $imagen_src = htmlspecialchars(ltrim($prod['imagen_url'] ?? $prod['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/'));
            $nombre = htmlspecialchars($prod['nombre']);
            $desc_corta = htmlspecialchars($prod['descripcion_corta'] ?? '');

            // Calcular precios / promociones
            $es_promo = ($prod['es_promocion'] ?? 0) == 1 && $prod['precio_oferta'] > 0;
            $precio_venta = $prod['precio_venta'];
            $precio_oferta = $prod['precio_oferta'];
            $precio_final = $es_promo ? $precio_oferta : $precio_venta;

            // HTML del Producto
            ?>
            <div class="col-6 col-md-4 col-lg-3 product-item-ajax">
                <div class="product-card-figma">
                    <div class="img-placeholder">
                        <?php if ($es_promo && $precio_oferta < $precio_venta): ?>
                            <?php $descuento = round((($precio_venta - $precio_oferta) / $precio_venta) * 100); ?>
                            <span class="discount-tag">-
                                <?php echo $descuento; ?>%
                            </span>
                        <?php endif; ?>

                        <a href="producto.php?id=<?php echo $prod['id']; ?>">
                            <?php if (!empty($prod['imagen_principal']) || !empty($prod['imagen_url'])): ?>
                                <img src="<?php echo $imagen_src; ?>" alt="<?php echo $nombre; ?>">
                            <?php else: ?>
                                <div class="text-secondary text-opacity-25"><i class="fas fa-leaf fa-3x"></i></div>
                            <?php endif; ?>
                        </a>
                    </div>

                    <h5 class="prod-title">
                        <a href="producto.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none text-dark">
                            <?php echo $nombre; ?>
                        </a>
                    </h5>
                    <!-- Descripcion solo visible en desktop típicamente, pero la clase lo maneja CSS -->
                    <p class="prod-desc">
                        <?php echo $desc_corta; ?>
                    </p>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php if ($es_promo): ?>
                                <span class="old-price">$
                                    <?php echo number_format($precio_venta, 2); ?>
                                </span>
                                <span class="prod-price">$
                                    <?php echo number_format($precio_oferta, 2); ?>
                                </span>
                            <?php else: ?>
                                <span class="prod-price">$
                                    <?php echo number_format($precio_venta, 2); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <button class="add-btn-icon" onclick="addToCart(
                                    <?php echo $prod['id']; ?>,
                                    '<?php echo $nombre; ?>',
                                    <?php echo $precio_final; ?>,
                                    '<?php echo $imagen_src; ?>'
                                )">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        // Retornar vacío si no hay más
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>