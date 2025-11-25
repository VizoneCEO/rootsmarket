<?php
// --- 1. OBTENER PRODUCTOS RECOMENDADOS ---
require_once(__DIR__ . '/../../back/conection/db.php');

$recomendados = [];
try {
    $stmt = $pdo->prepare("
        SELECT p.*, 
               (SELECT imagen_url FROM producto_imagenes pi WHERE pi.producto_id = p.id ORDER BY pi.orden ASC LIMIT 1) as imagen_principal 
        FROM productos p 
        WHERE p.estatus = 'activo' 
        ORDER BY RAND() LIMIT 4
    ");
    $stmt->execute();
    $recomendados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo silencioso
}
?>

<style>
    /* --- ESTILOS CARRITO FIGMA (COLORS) --- */
    body { background-color: #ffffff; }

    /* Encabezados VERDES */
    .cart-header-row {
        background-color: #599332; /* Verde Roots */
        color: white;
        border-radius: 8px 8px 0 0;
        padding: 15px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-header {
        background-color: #599332; /* Verde Roots */
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Filas de Productos */
    .cart-item-row {
        border-bottom: 1px solid #eee;
        padding: 20px 10px;
        display: flex;
        align-items: center;
        background-color: #fff;
    }
    .cart-item-row:last-child { border-bottom: none; }

    /* Imagen pequeña */
    .cart-img-box {
        width: 60px;
        height: 60px;
        min-width: 60px;
        background-color: #FFFFFF;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .cart-img-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 5px;
    }

    /* Botón Eliminar (X) */
    .btn-remove {
        color: #888;
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1rem;
        margin-right: 15px;
        transition: color 0.2s;
    }
    .btn-remove:hover { color: #dc3545; }

    /* Selector de Cantidad */
    .qty-selector-sm {
        display: inline-flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 2px 8px;
        background: #fff;
    }
    .qty-btn-sm { border: none; background: none; cursor: pointer; font-weight: bold; color: #555; padding: 0 8px; }
    .qty-val-sm { width: 30px; text-align: center; border: none; outline: none; font-size: 0.9rem; color: #333; }

    /* Caja de Resumen */
    .summary-box {
        border: 1px solid #eee; /* Borde sutil */
        border-radius: 8px;
        background-color: #f9f9f9; /* Fondo muy ligero para el cuerpo */
    }
    .summary-body { padding: 25px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 0.9rem; color: #555; font-weight: 500; }

    .summary-total {
        display: flex; justify-content: space-between; margin-top: 20px;
        border-top: 1px solid #ddd; pt-3;
        font-weight: 700; color: #333; font-size: 1.1rem;
    }

    /* Botón Checkout NARANJA */
    .btn-checkout-orange {
        background-color: #E67E22; /* Naranja Figma */
        color: white;
        width: 100%;
        padding: 14px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        margin-top: 20px;
        transition: background 0.3s;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    .btn-checkout-orange:hover { background-color: #D35400; color: white; }

    /* Botón Vaciar Carrito */
    .btn-empty-cart {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .btn-empty-cart:hover { opacity: 0.7; text-decoration: underline; }

    /* --- Estilos Recomendados --- */
    .similar-title {
        font-weight: 700; margin-bottom: 2rem; color: #333; font-size: 1.5rem;
        text-transform: uppercase;
    }
    .product-card-minimal { border: none; background: transparent; }
    .product-placeholder {
        background-color: #FFFFFF; border: 1px solid #e0e0e0; border-radius: 15px;
        height: 250px; width: 100%; position: relative; margin-bottom: 15px;
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .product-placeholder img { width: 100%; height: 100%; object-fit: contain; padding: 20px; }

    /* --- AJUSTES MÓVILES --- */
    @media (max-width: 768px) {
        .cart-item-grid {
            grid-template-columns: auto 60px 1fr;
            align-items: start;
        }
        .mobile-price { font-weight: bold; font-size: 1.1rem; }
    }
</style>

<div class="container my-5">

    <div id="empty-cart-msg" class="text-center py-5" style="display: none;">
        <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
        <h3 class="fw-bold text-muted">Tu carrito está vacío</h3>
        <p class="text-muted mb-4">¡Llena tu despensa con los mejores productos orgánicos!</p>
        <a href="tienda.php" class="btn rounded-pill px-4 py-2 text-white fw-bold" style="background-color: #599332;">Ir a la tienda</a>
    </div>

    <div class="row" id="cart-content-wrapper">

        <div class="col-lg-8 mb-5">

            <div class="d-flex justify-content-between align-items-end mb-3">
                <h4 class="fw-bold mb-0 text-dark">Tu Carrito</h4>
                <button class="btn-empty-cart" onclick="emptyCart()">
                    <i class="fas fa-trash-alt me-1"></i> Vaciar carrito
                </button>
            </div>

            <div class="cart-header-row d-none d-lg-flex">
                <div class="col-6 ps-4">Producto</div>
                <div class="col-2 text-center">Precio</div>
                <div class="col-2 text-center">Cantidad</div>
                <div class="col-2 text-end pe-3">Total</div>
            </div>

            <div id="cart-items-list" style="border: 1px solid #eee; border-top: none; border-radius: 0 0 8px 8px;">
                </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="summary-box sticky-top" style="top: 100px; z-index: 1;">
                <div class="summary-header">Resumen</div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span>SUBTOTAL</span>
                        <span id="summary-subtotal" class="fw-bold">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>DESCUENTO</span>
                        <span class="text-muted">--</span>
                    </div>

                    <div class="summary-total">
                        <span>TOTAL</span>
                        <span id="summary-total">$0.00</span>
                    </div>

                    <button class="btn-checkout-orange" onclick="window.location.href='checkout.php'">CONTINUAR CON LA COMPRA</button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-4 border-top">
        <h3 class="similar-title mt-4">También te puede gustar</h3>
        <div class="row g-4">
            <?php foreach ($recomendados as $rec): ?>
            <div class="col-6 col-md-3">
                <div class="product-card-minimal">
                    <div class="product-placeholder">
                        <?php if ($rec['precio_oferta'] > 0 && $rec['precio_oferta'] < $rec['precio_venta']): ?>
                            <?php $desc = round((($rec['precio_venta'] - $rec['precio_oferta']) / $rec['precio_venta']) * 100); ?>
                            <span class="badge bg-dark position-absolute top-0 start-0 m-3 rounded-pill" style="font-size: 0.75rem;">-<?php echo $desc; ?>%</span>
                        <?php endif; ?>

                        <a href="producto.php?id=<?php echo $rec['id']; ?>">
                            <img src="<?php echo htmlspecialchars(ltrim($rec['imagen_principal'], '/')); ?>" alt="<?php echo htmlspecialchars($rec['nombre']); ?>">
                        </a>
                    </div>
                    <h6 class="fw-bold text-truncate mb-1 text-dark"><?php echo htmlspecialchars($rec['nombre']); ?></h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php if ($rec['precio_oferta']): ?>
                                <small class="text-muted text-decoration-line-through me-1">$<?php echo number_format($rec['precio_venta'], 2); ?></small>
                                <span class="fw-bold text-dark">$<?php echo number_format($rec['precio_oferta'], 2); ?></span>
                            <?php else: ?>
                                <span class="fw-bold text-dark">$<?php echo number_format($rec['precio_venta'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <i class="fas fa-plus-circle fs-4 text-dark" style="cursor: pointer;" onclick="addToCart(<?php echo $rec['id']; ?>, '<?php echo htmlspecialchars($rec['nombre']); ?>', <?php echo $rec['precio_oferta'] ?: $rec['precio_venta']; ?>, '<?php echo htmlspecialchars(ltrim($rec['imagen_principal'] ?? 'front/multimedia/productos/default.png', '/')); ?>')"></i>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    renderCartPage();
});

function renderCartPage() {
    let cart = JSON.parse(localStorage.getItem('roots_cart')) || [];
    const listContainer = document.getElementById('cart-items-list');
    const wrapper = document.getElementById('cart-content-wrapper');
    const emptyMsg = document.getElementById('empty-cart-msg');
    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl = document.getElementById('summary-total');

    if (cart.length === 0) {
        wrapper.style.display = 'none';
        emptyMsg.style.display = 'block';
        return;
    }

    wrapper.style.display = 'flex';
    emptyMsg.style.display = 'none';
    listContainer.innerHTML = '';

    let totalAmount = 0;

    cart.forEach((item) => {
        const rowTotal = item.price * item.quantity;
        totalAmount += rowTotal;

        const html = `
            <div class="cart-item-row">
                <div class="row w-100 align-items-center m-0 d-none d-lg-flex">
                    <div class="col-6 d-flex align-items-center ps-0">
                        <span class="btn-remove" onclick="removeCartItem(${item.id})">×</span>
                        <div class="cart-img-box me-3">
                            <img src="${item.image}" alt="${item.name}" onerror="this.src='front/multimedia/productos/default.png'">
                        </div>
                        <div>
                            <h6 class="mb-0 text-truncate" style="max-width: 220px; color: #333;">${item.name}</h6>
                        </div>
                    </div>
                    <div class="col-2 text-center text-muted">$${item.price.toFixed(2)}</div>
                    <div class="col-2 text-center">
                        <div class="qty-selector-sm">
                            <button class="qty-btn-sm" onclick="updateCartQty(${item.id}, -1)">-</button>
                            <input type="text" class="qty-val-sm" value="${item.quantity}" readonly>
                            <button class="qty-btn-sm" onclick="updateCartQty(${item.id}, 1)">+</button>
                        </div>
                    </div>
                    <div class="col-2 text-end fw-bold pe-0 text-dark">$${rowTotal.toFixed(2)}</div>
                </div>

                <div class="d-flex d-lg-none w-100 align-items-start">
                    <span class="btn-remove mt-1" onclick="removeCartItem(${item.id})">×</span>
                    <div class="cart-img-box me-3">
                        <img src="${item.image}" alt="${item.name}" onerror="this.src='front/multimedia/productos/default.png'">
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-dark" style="font-size: 0.95rem; line-height: 1.2;">${item.name}</h6>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="qty-selector-sm">
                                <button class="qty-btn-sm" onclick="updateCartQty(${item.id}, -1)">-</button>
                                <input type="text" class="qty-val-sm" value="${item.quantity}" readonly>
                                <button class="qty-btn-sm" onclick="updateCartQty(${item.id}, 1)">+</button>
                            </div>
                            <div class="fw-bold mobile-price text-dark">$${rowTotal.toFixed(2)}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        listContainer.insertAdjacentHTML('beforeend', html);
    });

    subtotalEl.innerText = '$' + totalAmount.toFixed(2);
    totalEl.innerText = '$' + totalAmount.toFixed(2);
}

function updateCartQty(id, change) {
    let cart = JSON.parse(localStorage.getItem('roots_cart')) || [];
    const item = cart.find(i => i.id === id);
    if (item) {
        item.quantity += change;
        if (item.quantity < 1) item.quantity = 1;
        localStorage.setItem('roots_cart', JSON.stringify(cart));
        renderCartPage();
        updateCartCounter();
    }
}

function removeCartItem(id) {
    let cart = JSON.parse(localStorage.getItem('roots_cart')) || [];
    cart = cart.filter(i => i.id !== id);
    localStorage.setItem('roots_cart', JSON.stringify(cart));
    renderCartPage();
    updateCartCounter();
}

function emptyCart() {
    if (confirm("¿Estás seguro de que deseas vaciar el carrito?")) {
        localStorage.removeItem('roots_cart');
        renderCartPage();
        updateCartCounter();
    }
}
</script>