<div class="container-fluid">
    <h1 class="h2 mb-4">Ventas Asignadas</h1>

    <div id="assigned-orders-container">
        <!-- Dynamic Content: Cards Grouped by Driver -->
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>
</div>

<script>
    let currentOrderIdForAssignment = null;

    document.addEventListener('DOMContentLoaded', () => {
        loadAssignedOrders();
    });

    function loadAssignedOrders() {
        const formData = new FormData();
        formData.append('action', 'get_orders_admin');
        formData.append('status_envio', 'enviado');

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('assigned-orders-container');
                container.innerHTML = '';

                if (data.status === 'success' && data.data.length > 0) {
                    if (!window.assignedOrders) window.assignedOrders = {};

                    // Group by Driver
                    const groups = {};
                    data.data.forEach(order => {
                        window.assignedOrders[order.id] = order;

                        const driverId = order.repartidor_id || 'unassigned';
                        if (!groups[driverId]) {
                            groups[driverId] = {
                                name: order.nombre_repartidor ? `${order.nombre_repartidor} ${order.apellido_repartidor}` : 'Sin Asignar',
                                orders: []
                            };
                        }
                        groups[driverId].orders.push(order);
                    });

                    // Render Groups
                    Object.keys(groups).forEach(driverId => {
                        const group = groups[driverId];
                        const cardHtml = `
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-motorcycle me-2"></i>${group.name} 
                                <span class="badge bg-secondary ms-2">${group.orders.length} Pedidos</span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Pedido</th>
                                            <th>Cliente</th>
                                            <th>Fecha</th>
                                            <th class="text-center">Detalle</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${group.orders.map(order => renderAssignedRow(order)).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;
                        container.innerHTML += cardHtml;
                    });

                } else {
                    container.innerHTML = `
                    <div class="alert alert-info text-center">
                        No hay pedidos asignados o en ruta actualmente.
                    </div>`;
                }
            })
            .catch(err => {
                console.error('Error loading orders:', err);
                document.getElementById('assigned-orders-container').innerHTML = `
                <div class="alert alert-danger">Error cargando los pedidos. Por favor intente de nuevo.</div>
            `;
            });
    }

    function renderAssignedRow(order) {
        const date = new Date(order.fecha).toLocaleDateString('es-ES');
        return `
        <tr>
            <td class="fw-bold">#ORD-${order.id}</td>
            <td>
                <div>${order.nombre_cliente} ${order.apellido_paterno}</div>
                <small class="text-muted">ID: ${order.user_id}</small>
            </td>
            <td>${date}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-info" onclick="showOrderDetails(${order.id})">
                    <i class="fas fa-eye me-1"></i> Ver Detalle
                </button>
            </td>
            <td class="text-end fw-bold">$${parseFloat(order.total).toFixed(2)}</td>
            <td class="text-center"><span class="badge bg-info text-dark">Enviado</span></td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-primary mb-1" onclick="openAssignDriverModal(${order.id})" title="Reasignar Repartidor">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger mb-1" onclick="cancelOrder(${order.id})" title="Cancelar Pedido">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>
    `;
    }

    function showOrderDetails(orderId) {
        const order = window.assignedOrders[orderId];
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

        if (order.detalles) {
            order.detalles.forEach(item => {
                const itemTotal = item.cantidad * item.precio_unitario;
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

    function openAssignDriverModal(orderId) {
        currentOrderIdForAssignment = orderId;

        // Fetch drivers
        const formData = new FormData();
        formData.append('action', 'get_drivers');

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('driverSelect');
                select.innerHTML = '<option value="">Seleccionar Repartidor...</option>';

                if (data.status === 'success' && data.data) {
                    data.data.forEach(driver => {
                        select.innerHTML += `<option value="${driver.id}">${driver.nombre} ${driver.apellido_paterno}</option>`;
                    });
                }
                new bootstrap.Modal(document.getElementById('driverAssignModal')).show();
            })
            .catch(err => alert('Error cargando repartidores'));
    }

    function assignDriver() {
        const driverId = document.getElementById('driverSelect').value;
        if (!driverId) {
            alert('Por favor seleccione un repartidor');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'assign_driver');
        formData.append('order_id', currentOrderIdForAssignment);
        formData.append('driver_id', driverId);

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Repartidor reasignado correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('driverAssignModal')).hide();
                    loadAssignedOrders();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => console.error('Error assigning driver:', err));
    }

    function cancelOrder(orderId) {
        if (!confirm('¿Está seguro de que desea CANCELAR este pedido? Esta acción es irreversible.')) return;

        const formData = new FormData();
        formData.append('action', 'cancel_order');
        formData.append('order_id', orderId);

        fetch('../../back/admin_order_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Pedido cancelado');
                    loadAssignedOrders();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => console.error('Error cancelling order:', err));
    }
</script>

<!-- Modal Detalle Pedido -->
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

<!-- Modal Asignar/Reasignar Repartidor -->
<div class="modal fade" id="driverAssignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Seleccionar Repartidor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Seleccione el repartidor:</p>
                <select class="form-select form-select-lg mb-3" id="driverSelect">
                    <option value="">Cargando...</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="assignDriver()">Confirmar</button>
            </div>
        </div>
    </div>
</div>