<?php
// PHP Logic to fetch user data if logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userData = [
    'nombre' => '',
    'apellido_paterno' => '',
    'telefono' => '',
    'email' => ''
];
$userAddresses = [];

if ($isLoggedIn) {
    require_once 'back/conection/db.php';
    $userId = $_SESSION['user_id'];

    // Fetch User Info
    try {
        $stmt = $pdo->prepare("SELECT nombre, apellido_paterno, telefono, email FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $userResult = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userResult) {
            $userData = $userResult;
        }

        // Fetch User Addresses
        $stmtAddr = $pdo->prepare("SELECT * FROM direcciones_envio WHERE user_id = ? ORDER BY es_principal DESC");
        $stmtAddr->execute([$userId]);
        $userAddresses = $stmtAddr->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Handle error silently or log it
    }
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Left Column: Forms -->
        <div class="col-lg-8">
            <div class="mb-4">
                <h4 class="fw-bold mb-3" style="color: #599332;">1. Datos de Contacto</h4>
                <div class="p-4 bg-light rounded shadow-sm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nombre</label>
                            <input type="text" class="form-control" id="contactNombre"
                                value="<?php echo htmlspecialchars($userData['nombre'] . ' ' . $userData['apellido_paterno']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Teléfono</label>
                            <input type="text" class="form-control" id="contactTelefono"
                                value="<?php echo htmlspecialchars($userData['telefono']); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Correo Electrónico</label>
                            <input type="email" class="form-control" id="contactEmail"
                                value="<?php echo htmlspecialchars($userData['email']); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="fw-bold mb-3" style="color: #599332;">2. Dirección de Entrega</h4>
                <div class="p-4 bg-light rounded shadow-sm">

                    <?php if ($isLoggedIn && count($userAddresses) > 0): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mis Direcciones Guardadas:</label>
                            <select class="form-select" id="savedAddresses" onchange="fillAddress(this.value)">
                                <option value="">-- Seleccionar Dirección --</option>
                                <?php foreach ($userAddresses as $addr): ?>
                                    <option value='<?php echo json_encode($addr); ?>'>
                                        <?php echo htmlspecialchars($addr['alias'] . ' - ' . $addr['calle_numero']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <hr class="my-3">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small">Calle y Número</label>
                            <input type="text" class="form-control" id="shippingCalle"
                                placeholder="Ej. Av. Reforma 222">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Colonia</label>
                            <input type="text" class="form-control" id="shippingColonia">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Código Postal</label>
                            <input type="text" class="form-control" id="shippingCP">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Ciudad</label>
                            <input type="text" class="form-control" id="shippingCiudad">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Estado</label>
                            <input type="text" class="form-control" id="shippingEstado">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="fw-bold mb-3" style="color: #599332;">3. Método de Pago</h4>
                <p class="text-muted small">La cuenta de Azteca está pendiente. Simulación de pasarela.</p>
                <div class="p-4 bg-light rounded shadow-sm">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="card" checked>
                        <label class="form-check-label fw-bold" for="card">
                            Tarjeta de Crédito / Débito
                        </label>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small text-muted">Nombre en la tarjeta</label>
                            <input type="text" class="form-control" placeholder="Como aparece en el plástico">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted">Número de tarjeta</label>
                            <input type="text" class="form-control" placeholder="0000 0000 0000 0000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Vencimiento (MM/AA)</label>
                            <input type="text" class="form-control" placeholder="MM/AA">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">CVC</label>
                            <input type="text" class="form-control" placeholder="123">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Summary -->
        <div class="col-lg-4">
            <div class="p-4 border rounded shadow-sm bg-white sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4" style="color: #599332;">RESUMEN DEL PEDIDO</h5>

                <div id="checkout-items-summary" class="mb-3 border-bottom pb-3">
                    <!-- Items inserted via JS -->
                    <p class="text-muted small text-center">Cargando productos...</p>
                </div>

                <!-- Discount Code Section -->
                <div class="mb-3 border-bottom pb-3">
                    <label class="form-label small fw-bold text-muted">Código de descuento</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="discountCode" placeholder="Ingresa tu código">
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="applyDiscount()">Aplicar</button>
                    </div>
                    <div id="discountMsg" class="small mt-1 text-danger"></div>
                </div>

                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Subtotal</span>
                    <span id="checkout-subtotal">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3 small text-muted">
                    <span>Envío</span>
                    <span>$0.00</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                    <span>Total</span>
                    <span id="checkout-total">$0.00</span>
                </div>

                <button class="btn w-100 py-3 fw-bold text-white shadow-sm"
                    style="background-color: #E67E22; border-radius: 8px;" onclick="processPayment()">
                    REALIZAR PAGO
                </button>
                <p class="text-center mt-3 text-muted" style="font-size: 0.8rem;">
                    <i class="fas fa-lock me-1"></i> Pagos seguros encriptados
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    let appliedDiscount = null;

    document.addEventListener('DOMContentLoaded', () => {
        loadCheckoutSummary();
    });

    function loadCheckoutSummary() {
        const cart = JSON.parse(localStorage.getItem('roots_cart')) || [];
        const container = document.getElementById('checkout-items-summary');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const totalEl = document.getElementById('checkout-total');

        let subtotal = 0;

        if (cart.length === 0) {
            container.innerHTML = '<p class="text-muted text-center">Tu carrito está vacío.</p>';
            return;
        }

        let html = '';
        cart.forEach(item => {
            const total = item.price * item.quantity;
            subtotal += total;
            html += `
<div class="d-flex justify-content-between mb-2 small">
    <span class="text-truncate me-2" style="max-width: 180px;">${item.quantity}x ${item.name}</span>
    <span class="fw-bold">$${total.toFixed(2)}</span>
</div>
`;
        });

        // Discount calculation
        let total = subtotal;
        let discountAmount = 0;

        // Remove existing discount row if present
        const existingDiscountRow = document.getElementById('row-descuento');
        if (existingDiscountRow) existingDiscountRow.remove();

        if (appliedDiscount) {
            if (appliedDiscount.type === 'porcentaje') {
                discountAmount = subtotal * (parseFloat(appliedDiscount.value) / 100);
            } else {
                discountAmount = parseFloat(appliedDiscount.value);
            }

            // Ensure discount doesn't exceed total
            if (discountAmount > subtotal) discountAmount = subtotal;

            total = subtotal - discountAmount;

            // Add discount row before total
            const discountRow = document.createElement('div');
            discountRow.id = 'row-descuento';
            discountRow.className = 'd-flex justify-content-between mb-3 small text-success fw-bold';
            discountRow.innerHTML = `
<span>Descuento (${appliedDiscount.code})</span>
<span>-$${discountAmount.toFixed(2)}</span>
`;
            // Insert before the Shipping row (which is the previous sibling of total, but let's just append before Total container
            // parent's last children or find a stable anchor)
            // Easier: Append it to container or insert it before the shipping row if we can identify it.
            // In the current structure, subtotal and shipping are distinct divs.
            // Let's insert it after subtotal.
            subtotalEl.parentNode.insertAdjacentElement('afterend', discountRow);
        }

        container.innerHTML = html;
        subtotalEl.innerText = '$' + subtotal.toFixed(2);
        totalEl.innerText = '$' + total.toFixed(2);
    }

    function fillAddress(jsonAddress) {
        if (!jsonAddress) {
            document.getElementById('shippingCalle').value = '';
            document.getElementById('shippingColonia').value = '';
            document.getElementById('shippingCP').value = '';
            document.getElementById('shippingCiudad').value = '';
            document.getElementById('shippingEstado').value = '';
            return;
        }

        const addr = JSON.parse(jsonAddress);
        document.getElementById('shippingCalle').value = addr.calle_numero;
        document.getElementById('shippingColonia').value = addr.colonia;
        document.getElementById('shippingCP').value = addr.codigo_postal;
        document.getElementById('shippingCiudad').value = addr.ciudad;
        document.getElementById('shippingEstado').value = addr.estado;
    }

    function processPayment() {
        const cart = JSON.parse(localStorage.getItem('roots_cart')) || [];
        if (cart.length === 0) {
            Swal.fire('Carrito vacío', 'Agrega productos antes de pagar', 'warning');
            return;
        }

        const shippingData = {
            calle_numero: document.getElementById('shippingCalle').value,
            colonia: document.getElementById('shippingColonia').value,
            codigo_postal: document.getElementById('shippingCP').value,
            ciudad: document.getElementById('shippingCiudad').value,
            estado: document.getElementById('shippingEstado').value
        };

        const contactData = {
            nombre: document.getElementById('contactNombre').value,
            telefono: document.getElementById('contactTelefono').value,
            email: document.getElementById('contactEmail').value
        };

        let subtotal = 0;
        cart.forEach(item => subtotal += item.price * item.quantity);

        let total = subtotal;
        let discountAmt = 0;

        if (appliedDiscount) {
            if (appliedDiscount.type === 'porcentaje') {
                discountAmt = subtotal * (parseFloat(appliedDiscount.value) / 100);
            } else {
                discountAmt = parseFloat(appliedDiscount.value);
            }
            if (discountAmt > subtotal) discountAmt = subtotal;
            total = subtotal - discountAmt;
        }

        const formData = new FormData();
        formData.append('action', 'create_order');
        formData.append('cart', JSON.stringify(cart));
        formData.append('shipping', JSON.stringify(shippingData));
        formData.append('contact_nombre', contactData.nombre);
        formData.append('contact_telefono', contactData.telefono);
        formData.append('contact_email', contactData.email);
        formData.append('total', total);

        if (appliedDiscount) {
            formData.append('discount_code', appliedDiscount.code);
            formData.append('discount_amount', discountAmt.toFixed(2));
        }

        Swal.fire({
            title: 'Procesando...',
            text: 'Estamos generando tu pedido',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('back/client_manager.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: '¡Pago Exitoso!',
                        text: 'Tu pedido ha sido procesado correctamente. Gracias por comprar en Roots.',
                        icon: 'success',
                        confirmButtonColor: '#4EAE3E',
                        confirmButtonText: 'Ver mis pedidos'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            localStorage.removeItem('roots_cart');
                            window.location.href = 'front/cliente/perfil.php?section=pedidos';
                        }
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Ocurrió un error al procesar el pedido', 'error');
            });
    }

    function applyDiscount() {
        const code = document.getElementById('discountCode').value.trim();
        const msg = document.getElementById('discountMsg');

        if (!code) {
            msg.textContent = 'Ingresa un código';
            return;
        }

        msg.textContent = 'Validando...';
        msg.className = 'small mt-1 text-info';

        const formData = new FormData();
        formData.append('code', code);

        fetch('back/validate_discount.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    appliedDiscount = data;
                    msg.textContent = '¡Código aplicado correctamente!';
                    msg.className = 'small mt-1 text-success';
                    loadCheckoutSummary(); // Recalculate totals
                } else {
                    appliedDiscount = null;
                    msg.textContent = data.message;
                    msg.className = 'small mt-1 text-danger';
                    loadCheckoutSummary(); // Reset totals if needed
                }
            })
            .catch(() => {
                msg.textContent = 'Error al validar el código';
                msg.className = 'small mt-1 text-danger';
            });
    }
</script>