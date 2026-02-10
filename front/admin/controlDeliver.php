<div class="container-fluid">
    <h1 class="h2 mb-4">Control de Entregas - En Ruta</h1>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pedidos en Ruta de Entrega</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Dirección Entrega</th>
                            <th>Productos</th>
                            <th class="text-center">Estado Envío</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="deliver-orders-container">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let currentDeliveryOrderId = null;

    document.addEventListener('DOMContentLoaded', () => {
        loadDeliverOrders();
    });

    function loadDeliverOrders() {
        const formData = new FormData();
        formData.append('action', 'get_orders_admin');
        formData.append('status_envio', 'enviado');

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

                        const addrString = `
                        <strong>${addr.calle_numero || ''}</strong><br>
                        ${addr.colonia || ''}, ${addr.ciudad || ''}<br>
                        ${phone}
                    `;

                        // Format products summary
                        let productsHtml = '<ul class="list-unstyled mb-0 small">';
                        if (order.detalles) {
                            order.detalles.forEach(d => {
                                productsHtml += `<li>${d.cantidad}x ${d.nombre_producto}</li>`;
                            });
                        }
                        productsHtml += '</ul>';

                        const html = `
                        <tr>
                            <td class="fw-bold">#ORD-${order.id}</td>
                            <td>
                                <div>${order.nombre_cliente} ${order.apellido_paterno}</div>
                            </td>
                            <td>${addrString}</td>
                            <td>${productsHtml}</td>
                            <td class="text-center"><span class="badge bg-info text-dark">En Ruta</span></td>
                            <td class="text-center">
                                <div class="btn-group-vertical" role="group">
                                    <a href="${mapsUrl}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="fas fa-map-marker-alt me-1"></i> Ver en Mapa
                                    </a>
                                    ${phone ? `<a href="tel:${phone}" class="btn btn-sm btn-outline-secondary mb-1">
                                        <i class="fas fa-phone me-1"></i> Llamar Cliente
                                    </a>` : ''}
                                    <a href="tel:+528112345678" class="btn btn-sm btn-outline-warning mb-1">
                                        <i class="fas fa-headset me-1"></i> Soporte
                                    </a>
                                    <button class="btn btn-sm btn-success" onclick="openDeliveryModal(${order.id})">
                                        <i class="fas fa-check-circle me-1"></i> Entregar Pedido
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                        container.innerHTML += html;
                    });
                } else {
                    container.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay pedidos asignados para entrega.</td></tr>';
                }
            })
            .catch(err => console.error('Error loading orders:', err));
    }

    function openDeliveryModal(orderId) {
        currentDeliveryOrderId = orderId;
        // Reset form
        document.getElementById('receivedBy').value = '';
        document.getElementById('evidencePhoto').value = '';
        new bootstrap.Modal(document.getElementById('deliveryModal')).show();
    }

    function completeDelivery() {
        const receivedBy = document.getElementById('receivedBy').value;
        const photoInput = document.getElementById('evidencePhoto');

        if (!receivedBy) {
            alert('Por favor ingrese quien recibe el pedido');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'complete_delivery');
        formData.append('order_id', currentDeliveryOrderId);
        formData.append('received_by', receivedBy);

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
                    bootstrap.Modal.getInstance(document.getElementById('deliveryModal')).hide();
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