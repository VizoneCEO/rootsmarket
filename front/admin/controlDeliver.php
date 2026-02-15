<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Control de Entregas - En Ruta</h1>
        <button onclick="loadDeliverOrders()" class="btn btn-primary">
            <i class="fas fa-sync-alt me-1"></i> Actualizar
        </button>
    </div>

    <!-- Cards Container (Grid) -->
    <div id="deliver-orders-container" class="row g-4">
        <!-- Dynamic Cards -->
    </div>

    <!-- Floating Logout Button for Mobile -->
    <div class="d-md-none position-fixed bottom-0 start-0 w-100 p-3 pointer-events-none"
        style="z-index: 1050; pointer-events: none;">
        <a href="../../back/login/aut.php?action=logout"
            class="btn btn-danger rounded-circle shadow-lg d-flex align-items-center justify-content-center"
            style="width: 50px; height: 50px; pointer-events: auto;">
            <i class="fas fa-power-off"></i>
        </a>
    </div>
</div>

<script>
    let currentScanOrderId = null;
    let currentDeliveryOrderId = null;
    let html5QrCode = null;

    document.addEventListener('DOMContentLoaded', () => {
        loadDeliverOrders();

        // Modal Events
        const scanModal = document.getElementById('scanQrModal');
        if (scanModal) {
            scanModal.addEventListener('shown.bs.modal', function () {
                document.getElementById('qrInput').focus();
            });

            scanModal.addEventListener('hidden.bs.modal', function () {
                stopCamera();
            });
        }
    });

    function startCamera() {
        if (html5QrCode) {
            // Already running or initialized
            return;
        }

        // Check if library is loaded
        if (typeof Html5Qrcode === 'undefined') {
            alert('Error: La librería del escáner no se cargó correctamente. Por favor recarga la página.');
            return;
        }

        document.getElementById('startCameraBtn').classList.add('d-none');
        document.getElementById('stopCameraBtn').classList.remove('d-none');

        try {
            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
                .catch(err => {
                    console.error("Error starting camera", err);
                    alert("No se pudo acceder a la cámara. Asegúrate de dar permisos y usar HTTPS.");
                    stopCamera();
                });
        } catch (e) {
            console.error("Critical error initializing camera:", e);
            alert("Error crítico al iniciar la cámara: " + e.message);
            stopCamera();
        }
    }

    function stopCamera() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
                resetCameraButtons();
            }).catch(err => {
                console.error("Failed to stop", err);
                html5QrCode = null; // Force reset
                resetCameraButtons();
            });
        } else {
            resetCameraButtons();
        }
    }

    function resetCameraButtons() {
        const startBtn = document.getElementById('startCameraBtn');
        const stopBtn = document.getElementById('stopCameraBtn');
        
        if(startBtn) startBtn.classList.remove('d-none');
        if(stopBtn) stopBtn.classList.add('d-none');
        
        const reader = document.getElementById('reader');
        if(reader) reader.innerHTML = ''; // Clear container
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Play beep
        // fill input
        if(document.getElementById('qrInput')) {
            document.getElementById('qrInput').value = decodedText;
        }

        // Stop camera automatically on success
        stopCamera();

        // Trigger process
        processScan();
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // console.warn(`Code scan error = ${error}`);
    }

    function loadDeliverOrders() {
        const formData = new FormData();
        formData.append('action', 'get_orders_admin');
        formData.append('status_envio', 'asignado_o_enviado'); // Fetch assigned and en_route

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('deliver-orders-container');
                container.innerHTML = '';

                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(order => {
                        // Address Parsing
                        let addr = {};
                        try { addr = JSON.parse(order.direccion_envio); } catch (e) { }

                        const fullAddress = `${addr.calle_numero || ''}, ${addr.colonia || ''}, ${addr.ciudad || ''}`;
                        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(fullAddress)}`;
                        const phone = addr.telefono_contacto || '';

                        // Format products summary
                        let productsHtml = '<ul class="list-unstyled mb-0 small text-muted">';
                        if (order.detalles) {
                            order.detalles.forEach(d => {
                                productsHtml += `<li>${d.cantidad}x ${d.nombre_producto}</li>`;
                            });
                        }
                        productsHtml += '</ul>';

                        // Determine Status Badge and Action Button
                        let statusBadge = '';
                        let actionButton = '';
                        let cardBorderClass = '';

                        if (order.estatus_envio === 'asignado') {
                            statusBadge = '<span class="badge bg-secondary">Asignado</span>';
                            cardBorderClass = 'border-warning'; // Visual cue
                            actionButton = `
                                <button class="btn btn-warning w-100" onclick="openScanModal(${order.id})">
                                    <i class="fas fa-qrcode me-1"></i> Escanear para Ruta
                                </button>
                            `;
                        } else if (order.estatus_envio === 'enviado') {
                            statusBadge = '<span class="badge bg-info text-dark">En Ruta</span>';
                            cardBorderClass = 'border-success'; // Visual cue
                            actionButton = `
                                <button class="btn btn-success w-100" onclick="openDeliveryModal(${order.id})">
                                    <i class="fas fa-check-circle me-1"></i> Entregar Pedido
                                </button>
                            `;
                        }

                        const cardHtml = `
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm ${cardBorderClass}">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 fw-bold">#ORD-${order.id}</h5>
                                    ${statusBadge}
                                </div>
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="fas fa-user me-1"></i> ${order.nombre_cliente} ${order.apellido_paterno}
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <a href="${mapsUrl}" target="_blank" class="text-decoration-none text-dark d-block mb-1">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i> ${fullAddress}
                                        </a>
                                        ${phone ? `<a href="tel:${phone}" class="text-decoration-none text-dark d-block">
                                            <i class="fas fa-phone text-success me-2"></i> ${phone}
                                        </a>` : ''}
                                    </div>

                                    <hr>
                                    
                                    <div class="mb-3">
                                        <h6 class="small fw-bold text-uppercase text-muted">Productos</h6>
                                        ${productsHtml}
                                    </div>
                                    
                                    <div class="mb-3">
                                         <h6 class="small fw-bold text-uppercase text-muted">Pago</h6>
                                         <span class="badge ${order.estatus === 'pagado' ? 'bg-success' : 'bg-warning text-dark'}">
                                            ${order.metodo_pago || 'N/A'} - ${order.estatus}
                                         </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-grid gap-2">
                                        ${actionButton}
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <a href="${mapsUrl}" target="_blank" class="btn btn-outline-primary w-100 btn-sm">
                                                    <i class="fas fa-map"></i> Mapa
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                ${phone ? `<a href="tel:${phone}" class="btn btn-outline-secondary w-100 btn-sm">
                                                    <i class="fas fa-phone"></i> Llamar
                                                </a>` : '<button class="btn btn-outline-secondary w-100 btn-sm" disabled>Sin Tel</button>'}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                        container.innerHTML += cardHtml;
                    });
                } else {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                                <h3>No tienes pedidos asignados</h3>
                                <p>Tus pedidos aparecerán aquí cuando se te asignen.</p>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(err => console.error('Error loading orders:', err));
    }

    function openScanModal(orderId) {
        currentScanOrderId = orderId;
        if(document.getElementById('qrInput')) {
            document.getElementById('qrInput').value = '';
        }
        new bootstrap.Modal(document.getElementById('scanQrModal')).show();
    }

    function processScan() {
        const qrInput = document.getElementById('qrInput');
        if (!qrInput) return;
        
        const qrCode = qrInput.value;
        if (!qrCode) return;

        const formData = new FormData();
        formData.append('action', 'start_route');
        formData.append('order_id', currentScanOrderId);
        formData.append('qr_code', qrCode);

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Success beep or visual cue could go here
                    const modalEl = document.getElementById('scanQrModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if(modal) modal.hide();
                    
                    alert('¡Código Correcto! Pedido en ruta.');
                    loadDeliverOrders();
                } else {
                    alert('Error: ' + data.message);
                    qrInput.value = ''; // Clear for retry
                    qrInput.focus();
                }
            })
            .catch(err => {
                console.error('Error starting route:', err);
                alert('Error de conexión');
            });
    }

    function openDeliveryModal(orderId) {
        currentDeliveryOrderId = orderId;
        // Reset form
        document.getElementById('receivedBy').value = '';
        document.getElementById('paymentMethodDeliver').value = '';
        document.getElementById('evidencePhoto').value = '';
        new bootstrap.Modal(document.getElementById('deliveryModal')).show();
    }

    function completeDelivery() {
        const receivedBy = document.getElementById('receivedBy').value;
        const paymentMethod = document.getElementById('paymentMethodDeliver').value;
        const photoInput = document.getElementById('evidencePhoto');

        if (!receivedBy) {
            alert('Por favor ingrese quien recibe el pedido');
            return;
        }

        if (!paymentMethod) {
            alert('Por favor seleccione el método de pago/cobro');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'complete_delivery');
        formData.append('order_id', currentDeliveryOrderId);
        formData.append('received_by', receivedBy);
        formData.append('metodo_pago_entrega', paymentMethod);

        if (photoInput.files[0]) {
            formData.append('evidence_photo', photoInput.files[0]);
        }

        const btn = document.querySelector('#deliveryModal .btn-primary');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Cerrar Pedido';

                if (data.status === 'success') {
                    alert('Pedido entregado correctamente');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deliveryModal'));
                    if(modal) modal.hide();
                    loadDeliverOrders();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = 'Cerrar Pedido';
                console.error('Error completing delivery:', err);
                alert('Error al procesar la entrega');
            });
    }
</script>

<!-- Modal Escanear QR -->
<div class="modal fade" id="scanQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-qrcode"></i> Escanear Paquete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2">Escanea el código QR del paquete.</p>

                <!-- Camera Container -->
                <div id="reader" class="mb-3 border rounded bg-light" style="width: 100%; min-height: 250px;"></div>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <button class="btn btn-primary" id="startCameraBtn" onclick="startCamera()">
                        <i class="fas fa-camera"></i> Activar Cámara
                    </button>
                    <button class="btn btn-danger d-none" id="stopCameraBtn" onclick="stopCamera()">
                        <i class="fas fa-stop"></i> Detener
                    </button>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <hr class="flex-grow-1">
                        <span class="mx-2 text-muted small">O ingresa manual</span>
                        <hr class="flex-grow-1">
                    </div>
                    <input type="text" id="qrInput" class="form-control text-center fw-bold"
                        placeholder="Código manual..." autocomplete="off">
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-dark" onclick="processScan()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmación Entrega -->
<div class="modal fade" id="deliveryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Confirmar Entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deliveryForm">
                    <div class="mb-3">
                        <label for="receivedBy" class="form-label fw-bold">Recibido por:</label>
                        <input type="text" class="form-control" id="receivedBy" placeholder="Nombre de quien recibe"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="paymentMethodDeliver" class="form-label fw-bold">Método de Pago (Cobrado):</label>
                        <select class="form-select" id="paymentMethodDeliver" required>
                            <option value="" selected disabled>Seleccione...</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta (Terminal)">Tarjeta (Terminal)</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Ya Pagado (Online)">Ya Pagado (Online)</option>
                            <option value="No Cobrado (Crédito)">No Cobrado (Crédito)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="evidencePhoto" class="form-label fw-bold">Evidencia Fotográfica:</label>
                        <div class="border border-2 border-dashed rounded p-4 text-center bg-light"
                            onclick="document.getElementById('evidencePhoto').click()"
                            style="cursor: pointer; border-style: dashed !important;">
                            <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                            <p class="mb-0 text-muted small">Haz clic para subir foto</p>
                            <input type="file" class="d-none" id="evidencePhoto" accept="image/*" capture="environment">
                        </div>
                        <div id="filePreview" class="mt-2 text-center text-success small"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="completeDelivery()">Cerrar Pedido</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple file preview feedback
    document.getElementById('evidencePhoto').addEventListener('change', function (e) {
        const fileName = e.target.files[0]?.name;
        document.getElementById('filePreview').textContent = fileName ? 'Archivo seleccionado: ' + fileName : '';
    });
</script>