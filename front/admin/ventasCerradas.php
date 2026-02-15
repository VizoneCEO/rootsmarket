<div class="container-fluid">
    <h1 class="h2 mb-4">Pedidos Cerrados (Entregados)</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Historial de Entregas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Repartidor</th>
                            <th>Recibido Por</th>
                            <th class="text-center">Evidencia</th>
                            <th class="text-end">Total</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="closed-orders-container">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadClosedOrders();
    });

    function loadClosedOrders() {
        const formData = new FormData();
        formData.append('action', 'get_orders_admin');
        formData.append('status_envio', 'entregado');

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('closed-orders-container');
                container.innerHTML = '';

                if (data.status === 'success' && data.data.length > 0) {
                    // Normalize global orders for detail view
                    if (!window.closedOrders) window.closedOrders = {};

                    data.data.forEach(order => {
                        window.closedOrders[order.id] = order;

                        const date = new Date(order.fecha).toLocaleDateString('es-ES');
                        const driverName = order.nombre_repartidor ? `${order.nombre_repartidor} ${order.apellido_repartidor}` : 'N/A';

                        // Handle evidence path - clean up relative path if needed
                        let evidenceBtn = '<span class="text-muted small">Sin Foto</span>';
                        if (order.evidencia_foto) {
                            // Fix path for display if it's relative
                            const evidenceUrl = order.evidencia_foto.replace('../front/', '../../front/');
                            evidenceBtn = `
                                <button class="btn btn-sm btn-outline-secondary" onclick="showEvidence('${evidenceUrl}')">
                                    <i class="fas fa-image"></i> Ver Foto
                                </button>`;
                        }

                        const html = `
                        <tr>
                            <td class="fw-bold">#ORD-${order.id}</td>
                            <td>
                                <div>${order.nombre_cliente} ${order.apellido_paterno}</div>
                            </td>
                            <td>${driverName}</td>
                            <td>${order.recibido_por || '<span class="text-muted">N/A</span>'}</td>
                            <td class="text-center">${evidenceBtn}</td>
                            <td class="text-end fw-bold">$${parseFloat(order.total).toFixed(2)}</td>
                            <td>${date}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="print_order.php?id=${order.id}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Imprimir Ticket">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-info" onclick="showOrderDetails(${order.id})">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                        container.innerHTML += html;
                    });
                } else {
                    container.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay pedidos entregados aún.</td></tr>';
                }
            })
            .catch(err => console.error('Error loading orders:', err));
    }

    function showEvidence(url) {
        document.getElementById('evidenceImage').src = url;
        new bootstrap.Modal(document.getElementById('evidenceModal')).show();
    }

    function showOrderDetails(orderId) {
        const order = window.closedOrders[orderId];
        if (!order) return;

        document.getElementById('modalOrderId').textContent = `#ORD-${order.id}`;
        document.getElementById('modalOrderDate').textContent = new Date(order.fecha).toLocaleDateString('es-ES');

        // Address Parsing
        let addr = {};
        try { addr = JSON.parse(order.direccion_envio); } catch (e) { }
        document.getElementById('modalOrderAddress').innerHTML = `
        <strong>${addr.calle_numero || ''}</strong><br>
        ${addr.colonia || ''}, ${addr.ciudad || ''}<br>
        Tel: ${addr.telefono_contacto || ''}<br>
        Ref: ${addr.referencias || ''}
    `;

        // Items
        const itemsContainer = document.getElementById('modalOrderItems');
        itemsContainer.innerHTML = '';
        let subtotal = 0;

        if (order.detalles) {
            order.detalles.forEach(item => {
                const itemTotal = item.cantidad * item.precio_unitario;
                subtotal += itemTotal;
                itemsContainer.innerHTML += `
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                    <div>
                        <span class="fw-bold fs-6">${item.nombre_producto}</span><br>
                        <span class="text-muted small">${item.cantidad} x $${parseFloat(item.precio_unitario).toFixed(2)}</span>
                    </div>
                    <span class="fw-bold">$${itemTotal.toFixed(2)}</span>
                </div>
            `;
            });
        }

        document.getElementById('modalOrderTotal').textContent = `$${parseFloat(order.total).toFixed(2)}`;

        new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
    }
</script>

<!-- Modal Evidencia -->
<div class="modal fade" id="evidenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Evidencia de Entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="evidenceImage" src="" alt="Evidencia" class="img-fluid rounded" style="max-height: 80vh;">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Pedido (Reused) -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detalle del Pedido <span id="modalOrderId" class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold">FECHA</label>
                        <div id="modalOrderDate" class="fs-5"></div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="text-muted small fw-bold">TOTAL</label>
                        <div id="modalOrderTotal" class="fs-4 fw-bold text-success"></div>
                    </div>
                </div>

                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Dirección de
                            Envío</h6>
                        <address id="modalOrderAddress" class="mb-0 fst-normal"></address>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Productos</h6>
                <div id="modalOrderItems" class="mb-3"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>