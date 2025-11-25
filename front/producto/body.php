<?php
// --- 1. CONEXIÓN Y LÓGICA ---
require_once(__DIR__ . '/../../back/conection/db.php');

// Obtener ID del producto desde la URL
$id_producto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

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
    /* --- ESTILOS FICHA DE PRODUCTO (FIGMA COLORS) --- */
    body { background-color: #ffffff; }

    /* GALERÍA */
    .gallery-wrapper {
        display: flex;
        gap: 15px;
        height: 500px;
    }

    .thumbnails-col {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 80px;
        overflow-y: auto;
    }

    .thumb-img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: contain;
        border-radius: 10px;
        background-color: #fff; /* Fondo blanco limpio */
        border: 1px solid #eee;
        cursor: pointer;
        transition: all 0.2s;
        padding: 5px;
    }

    .thumb-img:hover, .thumb-img.active {
        border-color: #E67E22; /* Borde naranja al seleccionar */
    }

    .main-image-col {
        flex-grow: 1;
        background-color: #F9F9F9; /* Gris muy claro casi blanco */
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .main-img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    /* INFO PRODUCTO */
    .product-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }

    .product-price {
        font-size: 1.8rem;
        font-weight: 400;
        color: #333;
        margin-bottom: 1rem;
    }

    .rating-stars { color: #E67E22; /* Estrellas naranjas */ font-size: 0.9rem; }
    .review-count { color: #888; font-size: 0.9rem; margin-left: 5px; }

    .product-description {
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
        margin: 1.5rem 0;
    }

    /* BOTONES DE ACCIÓN - ACTUALIZACIÓN DE COLORES */
    .action-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 2rem;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 50px;
        padding: 5px 15px;
        height: 50px;
    }

    .qty-btn { border: none; background: none; font-size: 1.2rem; cursor: pointer; color: #333; }
    .qty-input { border: none; width: 40px; text-align: center; font-weight: 600; outline: none; }

    /* BOTÓN NARANJA (Añadir al carrito) */
    .btn-add-cart {
        background-color: #E67E22; /* Naranja Figma */
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 12px 40px;
        font-weight: 600;
        height: 50px;
        flex-grow: 1;
        transition: background 0.3s;
    }
    .btn-add-cart:hover { background-color: #D35400; /* Naranja más oscuro al hover */ }

    /* BOTÓN BLANCO/NEGRO (Comprar ahora) */
    .btn-buy-now {
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
    .btn-buy-now:hover { background-color: #f5f5f5; }

    /* ICONOS ENVÍO (Verdes) */
    .shipping-info {
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 0.9rem;
        color: #555;
    }
    .shipping-item { display: flex; align-items: center; gap: 10px; }
    .shipping-item i { color: #4EAE3E; /* Iconos verdes */ font-size: 1.1rem; }

    /* INFO NUTRICIONAL */
    .nutrition-box {
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 15px;
        padding: 20px;
        margin-top: 3rem;
    }
    .nutrition-item {
        text-align: center;
        border-right: 1px solid #eee;
    }
    .nutrition-item:last-child { border-right: none; }
    .nutri-val { font-weight: 700; font-size: 1.1rem; display: block; color: #333; }
    .nutri-label { font-size: 0.8rem; color: #888; text-transform: uppercase; }

    /* SIMILARES */
    .similar-card { border: none; background: transparent; transition: transform 0.3s; }
    .similar-card:hover { transform: translateY(-5px); }
    .similar-img-box {
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
    .similar-img-box img { width: 80%; height: 80%; object-fit: contain; }
</style>

<div class="container my-5">
    <div class="row gx-5">

        <div class="col-lg-7 mb-5 mb-lg-0">
            <div class="gallery-wrapper">
                <div class="thumbnails-col d-none d-md-flex">
                    <?php foreach ($imagenes as $index => $img): ?>
                        <img src="<?php echo htmlspecialchars(ltrim($img, '/')); ?>"
                             class="thumb-img <?php echo $index === 0 ? 'active' : ''; ?>"
                             onclick="changeImage(this.src, this)"
                             alt="Thumbnail">
                    <?php endforeach; ?>
                </div>

                <div class="main-image-col">
                    <img id="mainImage" src="<?php echo htmlspecialchars(ltrim($imagenes[0], '/')); ?>" class="main-img" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <h1 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h1>

            <div class="d-flex align-items-center mb-3">
                <div class="rating-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <span class="review-count">(32 reviews)</span>
                <i class="far fa-heart ms-auto fs-5 text-muted" style="cursor: pointer;"></i>
            </div>

            <div class="product-price">
                <?php if ($producto['precio_oferta']): ?>
                    <span class="text-decoration-line-through text-muted fs-5 me-2">$<?php echo number_format($producto['precio_venta'], 2); ?></span>
                    $<?php echo number_format($producto['precio_oferta'], 2); ?>
                <?php else: ?>
                    $<?php echo number_format($producto['precio_venta'], 2); ?>
                <?php endif; ?>
            </div>

            <div class="product-description">
                <p class="mb-2"><?php echo htmlspecialchars($producto['descripcion_corta']); ?></p>
                <ul class="list-unstyled text-muted small mt-3">
                    <li>• Origen: <?php echo htmlspecialchars($producto['origen'] ?? 'Nacional'); ?></li>
                    <?php if($producto['es_organico']): ?> <li>• Producto Orgánico</li> <?php endif; ?>
                    <?php if($producto['es_vegano']): ?> <li>• Apto para veganos</li> <?php endif; ?>
                </ul>
            </div>

            <div class="action-row">
                <div class="quantity-selector">
                    <button class="qty-btn" onclick="updateQty(-1)">-</button>
                    <input type="text" class="qty-input" id="qtyInput" value="1" readonly>
                    <button class="qty-btn" onclick="updateQty(1)">+</button>
                </div>
                <button class="btn-add-cart"
                        onclick="addToCart(
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

    <?php if($producto['calorias'] || $producto['proteinas_g']): ?>
    <div class="nutrition-box">
        <h5 class="fw-bold mb-4 text-center text-uppercase" style="font-size: 1rem; letter-spacing: 1px;">Información Nutricional</h5>
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
                                    <img src="<?php echo htmlspecialchars(ltrim($sim['imagen_principal'] ?? $sim['imagen_url'], '/')); ?>" alt="<?php echo htmlspecialchars($sim['nombre']); ?>">
                                </a>
                            </div>
                            <h6 class="fw-bold text-truncate mt-2">
                                <a href="producto.php?id=<?php echo $sim['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($sim['nombre']); ?>
                                </a>
                            </h6>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="fw-bold text-dark">$<?php echo number_format($sim['precio_venta'], 2); ?></span>
                                <i class="fas fa-plus-circle fs-4 text-dark" style="cursor: pointer;" onclick="addToCart(<?php echo $sim['id']; ?>, '<?php echo htmlspecialchars($sim['nombre']); ?>', <?php echo $sim['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($sim['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>')"></i>
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
</script>