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
                                <?php
                                $sessionAddrId = $_SESSION['selected_address_id'] ?? 0;
                                foreach ($userAddresses as $addr):
                                    $selected = ($addr['id'] == $sessionAddrId) ? 'selected' : '';
                                    ?>
                                    <option value='<?php echo json_encode($addr); ?>' <?php echo $selected; ?>>
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
                <h4 class="fw-bold mb-3" style="color: #599332;">3. Horario de Entrega</h4>
                <div class="p-4 bg-light rounded shadow-sm">
                    <p class="text-muted small">Selecciona la fecha y hora preferida para recibir tu pedido .</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Fecha de Entrega</label>
                            <input type="date" class="form-control" id="deliveryDate" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Hora Aproximada</label>
                            <select class="form-select" id="deliveryTime" required>
                                <option value="">-- Selecciona hora --</option>
                                <?php
                                for ($h = 9; $h <= 20; $h++) {
                                    $timeStr = sprintf('%02d:00', $h);
                                    $displayStr = ($h < 12) ? $h . ':00 AM' : (($h == 12) ? '12:00 PM' : ($h - 12) . ':00 PM');
                                    echo "<option value=\"$timeStr\">$displayStr</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="fw-bold mb-3" style="color: #599332;">4. Método de Pago</h4>
                <p class="text-muted small">La cuenta de Azteca está pendiente. Simulación de pasarela.</p>
                <div class="p-4 bg-light rounded shadow-sm">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="card" value="card" checked
                            onchange="togglePaymentMethod()">
                        <label class="form-check-label fw-bold" for="card">
                            Tarjeta de Crédito / Débito
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="cash"
                            onchange="togglePaymentMethod()">
                        <label class="form-check-label fw-bold" for="cash">
                            Efectivo contra entrega (Máx $3,000)
                        </label>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <!-- Contenedor del formulario de Clip -->
                            <form id="payment-form" style="display: contents;">
                                <div id="checkout" style="min-height: 200px;"></div>
                                <!-- El botón de pago real está fuera, manejaremos el submit manualmente -->
                            </form>
                            <div id="cash-info" class="alert alert-info d-none">
                                <i class="fas fa-money-bill-wave me-2"></i> Pagarás al recibir tu pedido. Solo válido
                                para montos menores a $3,000 MXN.
                            </div>
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

                <!-- Bag Purchase Section -->
                <div class="mb-3 border-bottom pb-3">
                    <label class="form-label small fw-bold text-success"><i class="fas fa-shopping-bag me-1"></i> Bolsas
                        Reutilizables ($13.00)</label>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small text-muted" style="font-size: 0.85rem;">¿Cuántas necesitas?</span>
                        <div class="input-group input-group-sm" style="width: 100px;">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="updateBagCount(-1)">-</button>
                            <input type="text" class="form-control text-center bg-white" id="bagQuantity" value="0"
                                readonly>
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="updateBagCount(1)">+</button>
                        </div>
                    </div>
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
                    <span id="checkout-shipping">$0.00</span>
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
    let clip = null;
    let card = null;
    let bagCount = 0;
    const BAG_PRICE = 13.00;
    const CLIP_API_KEY = "970fcb5c-51fc-45dc-a7e9-b16ba501ef55"; // Tu llave pública

    document.addEventListener('DOMContentLoaded', () => {
        loadCheckoutSummary();
        initClip();

        // Auto-fill address if pre-selected
        const savedAddrSelect = document.getElementById('savedAddresses');
        if (savedAddrSelect && savedAddrSelect.value) {
            fillAddress(savedAddrSelect.value);
        }

        // Set Date Picker default to tomorrow
        const dateInput = document.getElementById('deliveryDate');
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const yyyy = tomorrow.getFullYear();
        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd = String(tomorrow.getDate()).padStart(2, '0');
        const minDate = `${yyyy}-${mm}-${dd}`;

        dateInput.min = minDate;
        dateInput.value = minDate; // Default select tomorrow
    });

    function togglePaymentMethod() {
        const method = document.querySelector('input[name="paymentMethod"]:checked').value;
        const clipContainer = document.getElementById('checkout');
        const cashInfo = document.getElementById('cash-info');

        if (method === 'cash') {
            clipContainer.style.display = 'none';
            cashInfo.classList.remove('d-none');
        } else {
            clipContainer.style.display = 'block';
            cashInfo.classList.add('d-none');
        }
    }

    function initClip() {
        try {
            clip = new ClipSDK(CLIP_API_KEY);
            card = clip.element.create("Card", {
                locale: "es",
                theme: "light"
            });
            card.mount("checkout");
        } catch (e) {
            console.error("Error inicializando Clip:", e);
        }
    }

    const SHIPPING_THRESHOLD = 900;
    const SHIPPING_COST = 50.00;

    function loadCheckoutSummary() {
        const cart = JSON.parse(localStorage.getItem('roots_cart')) || [];
        const container = document.getElementById('checkout-items-summary');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const totalEl = document.getElementById('checkout-total');
        const shippingEl = document.getElementById('checkout-shipping'); // We need to add this ID to HTML or use existing structure

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

        // Shipping calculation
        let shippingCost = 0;

        // Add bag cost to subtotal for display? Or separate? 
        // User said "guarda el dato en el detalle del pedido como un producto mas".
        // So visually it should look like a product.

        // Let's add bags to displayed HTML list if count > 0
        if (bagCount > 0) {
            const bagsTotal = bagCount * BAG_PRICE;
            subtotal += bagsTotal;
            // Append bag row
            const bagHtml = `
            <div class="d-flex justify-content-between mb-2 small text-success">
                <span class="text-truncate me-2" style="max-width: 180px;">${bagCount}x Bolsa Reutilizable</span>
                <span class="fw-bold">$${bagsTotal.toFixed(2)}</span>
            </div>
            `;
            // Insert bag HTML at end of list inside container
            // Actually container.innerHTML was rewritten above. We should append.
            html += bagHtml; // Append to html list
        }

        // Refill container with updated html
        container.innerHTML = html;

        if (subtotal < SHIPPING_THRESHOLD) {
            shippingCost = SHIPPING_COST;
        }

        // Discount calculation
        let total = subtotal + shippingCost;
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

            // Ensure discount doesn't exceed subtotal (usually discount applies to products, not shipping)
            if (discountAmount > subtotal) discountAmount = subtotal;

            total = (subtotal - discountAmount) + shippingCost;

            // Add discount row before total
            const discountRow = document.createElement('div');
            discountRow.id = 'row-descuento';
            discountRow.className = 'd-flex justify-content-between mb-3 small text-success fw-bold';
            discountRow.innerHTML = `
<span>Descuento (${appliedDiscount.code})</span>
<span>-$${discountAmount.toFixed(2)}</span>
`;
            subtotalEl.parentNode.insertAdjacentElement('afterend', discountRow);
        }

        container.innerHTML = html;
        subtotalEl.innerText = '$' + subtotal.toFixed(2);

        // Update shipping display
        // Assuming the shipping element is the one with $0.00 in the HTML structure:
        // <div class="d-flex justify-content-between mb-3 small text-muted"><span>Envío</span><span>$0.00</span></div>
        // We need to target it more precisely. Let's update the HTML structure separately or traverse safely.
        // For now, let's assume I will update the HTML to have id="checkout-shipping"
        if (document.getElementById('checkout-shipping')) {
            document.getElementById('checkout-shipping').innerText = '$' + shippingCost.toFixed(2);
        } else {
            // Fallback if ID not yet added (I will add it in next step, but failsafe here)
            // It was the next sibling of the subtotal div in original code
            subtotalEl.parentElement.nextElementSibling.lastElementChild.innerText = '$' + shippingCost.toFixed(2);
        }

        totalEl.innerText = '$' + total.toFixed(2);

        // Min Purchase Validation
        const payBtn = document.querySelector('button[onclick="processPayment()"]');
        if (subtotal < 500) { // Min purchase usually applies to subtotal
            payBtn.disabled = true;
            payBtn.innerText = 'Mínimo de compra $500';
            payBtn.classList.add('btn-secondary');
            payBtn.classList.remove('text-white');
            payBtn.style.backgroundColor = '#ccc';
        } else {
            payBtn.disabled = false;
            payBtn.innerText = 'REALIZAR PAGO';
            payBtn.classList.remove('btn-secondary');
            payBtn.classList.add('text-white');
            payBtn.style.backgroundColor = '#E67E22';
        }
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

    async function processPayment() {
        // 1. Validar Carrito y Datos shipping
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

        const deliveryData = {
            fecha: document.getElementById('deliveryDate').value,
            hora: document.getElementById('deliveryTime').value
        };

        if (!contactData.nombre || !contactData.email || !shippingData.calle_numero || !deliveryData.fecha || !deliveryData.hora) {
            Swal.fire('Datos incompletos', 'Por favor completa la información de contacto, envío y horario de entrega.', 'warning');
            return;
        }

        // Business Rules Validation
        // 1. Delivery State (Querétaro only)
        const estado = shippingData.estado.toLowerCase();
        if (!estado.includes('querétaro') && !estado.includes('queretaro')) {
            Swal.fire('Zona fuera de cobertura', 'Por el momento solo realizamos entregas en el estado de Querétaro.', 'error');
            return;
        }

        let subtotal = 0;
        cart.forEach(item => subtotal += item.price * item.quantity);

        let shippingCost = 0;
        if (subtotal < SHIPPING_THRESHOLD) {
            shippingCost = SHIPPING_COST;
        }

        let total = subtotal + shippingCost;
        let discountAmt = 0;

        if (appliedDiscount) {
            if (appliedDiscount.type === 'porcentaje') {
                discountAmt = subtotal * (parseFloat(appliedDiscount.value) / 100);
            } else {
                discountAmt = parseFloat(appliedDiscount.value);
            }
            if (discountAmt > subtotal) discountAmt = subtotal;
            total = (subtotal - discountAmt) + shippingCost;
        }

        // 2. Minimum Purchase
        if (subtotal < 500) {
            Swal.fire('Compra Mínima', 'El monto mínimo de compra es de $500 MXN.', 'warning');
            return;
        }

        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        let cardTokenID = null;

        if (paymentMethod === 'card') {
            // Tokenizar Tarjeta con Clip
            Swal.fire({
                title: 'Procesando pago...',
                text: 'Validando tarjeta con el banco...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const tokenResult = await card.cardToken();
                cardTokenID = tokenResult.id;
            } catch (error) {
                console.error("Error tokenizando:", error);
                let msg = "Error al procesar la tarjeta.";
                if (error.code) msg += " (" + error.code + ")";
                Swal.fire('Error de Tarjeta', msg, 'error');
                return;
            }

            // Enviar pago al Backend (Clip)
            Swal.update({ text: 'Realizando cargo...' });

            const paymentPayload = {
                token: cardTokenID,
                amount: total,
                email: contactData.email,
                phone: contactData.telefono,
                reference: 'Orden Roots ' + new Date().getTime()
            };

            try {
                const paymentResp = await fetch('back/process_payment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(paymentPayload)
                });
                const paymentData = await paymentResp.json();

                if (paymentData.status !== 'success') {
                    throw new Error(paymentData.message || 'Error en el pago');
                }

                // Set reference for order creation
                createOrderInBackend(cart, shippingData, contactData, total, discountAmt, shippingCost, paymentData.data.id, 'card');

            } catch (err) {
                console.error(err);
                Swal.fire('Error', err.message, 'error');
            }

        } else if (paymentMethod === 'cash') {
            // Cash Payment
            if (total > 3000) {
                Swal.fire('Límite Excedido', 'Los pagos en efectivo están limitados a $3,000 MXN. Por favor usa tarjeta.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Procesando pedido...',
                text: 'Registrando orden...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Create order directly (status pending payment or similar, generally "pagado" is distinct from "pendiente")
            // Re-using logic but maybe we need to pass payment method to backend order creation
            createOrderInBackend(cart, shippingData, contactData, total, discountAmt, shippingCost, 'CASH-' + new Date().getTime(), 'cash');
        }
    }

    async function createOrderInBackend(cart, shippingData, contactData, total, discountAmt, shippingCost, payRef, payMethod) {
        Swal.update({ text: 'Generando orden...' });

        const deliveryData = {
            fecha: document.getElementById('deliveryDate').value,
            hora: document.getElementById('deliveryTime').value
        };

        const formData = new FormData();
        formData.append('action', 'create_order');
        formData.append('bag_quantity', bagCount); // Send bag quantity
        formData.append('cart', JSON.stringify(cart));
        formData.append('shipping', JSON.stringify(shippingData));
        formData.append('contact_nombre', contactData.nombre);
        formData.append('contact_telefono', contactData.telefono);
        formData.append('contact_email', contactData.email);
        formData.append('delivery_date', deliveryData.fecha);
        formData.append('delivery_time', deliveryData.hora);
        formData.append('total', total);
        formData.append('shipping_cost', shippingCost);
        formData.append('payment_reference', payRef);
        formData.append('payment_method', payMethod); // Need to handle this in backend or just store in ref

        if (appliedDiscount) {
            formData.append('discount_code', appliedDiscount.code);
            formData.append('discount_amount', discountAmt.toFixed(2));
        }

        try {
            const orderResp = await fetch('back/client_manager.php', {
                method: 'POST',
                body: formData
            });
            const orderData = await orderResp.json();

            if (orderData.status === 'success') {
                Swal.fire({
                    title: '¡Pedido Exitoso!',
                    text: 'Tu pedido ha sido procesado correctamente.',
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
                Swal.fire('Error', 'Hubo un error al guardar la orden: ' + orderData.message, 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Error de conexión: ' + e.message, 'error');
        }
    }

    // REMOVED OLD processPayment to avoid duplication
    /*
    async function processPayment_OLD() {
        // ...
    }
    */

    function updateBagCount(delta) {
        let newCount = bagCount + delta;
        if (newCount < 0) newCount = 0;
        if (newCount > 50) newCount = 50; // Max limit safety

        bagCount = newCount;
        document.getElementById('bagQuantity').value = bagCount;
        loadCheckoutSummary(); // Recalculate totals
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