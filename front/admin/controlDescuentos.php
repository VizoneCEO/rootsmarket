<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold"><i class="fas fa-tags me-2 text-success"></i>Control de Descuentos</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalDiscount" onclick="resetForm()">
            <i class="fas fa-plus me-2"></i>Nuevo Código
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Expiración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="discountTableBody">
                        <!-- Loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDiscount" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalTitle">Nuevo Código de Descuento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDiscount">
                    <input type="hidden" id="discountId">
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control" id="code" required style="text-transform: uppercase;">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" id="type">
                                <option value="porcentaje">Porcentaje (%)</option>
                                <option value="fijo">Monto Fijo ($)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valor</label>
                            <input type="number" step="0.01" class="form-control" id="value" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Expiración (Opcional)</label>
                        <input type="date" class="form-control" id="expiration">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="saveDiscount()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', loadDiscounts);

    function loadDiscounts() {
        const tbody = document.getElementById('discountTableBody');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Cargando...</td></tr>';

        const formData = new FormData();
        formData.append('action', 'list');

        fetch('../../back/admin_discount_manager.php', {
            method: 'POST',
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                tbody.innerHTML = '';
                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(d => {
                        const statusBadge = d.activo == 1
                            ? '<span class="badge bg-success">Activo</span>'
                            : '<span class="badge bg-danger">Inactivo</span>';

                        const valueDisplay = d.tipo === 'porcentaje' ? d.valor + '%' : '$' + d.valor;

                        tbody.innerHTML += `
                        <tr>
                            <td class="fw-bold">${d.codigo}</td>
                            <td>${d.tipo}</td>
                            <td>${valueDisplay}</td>
                            <td>${d.fecha_expiracion || 'Sin exp.'}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1" onclick='editDiscount(${JSON.stringify(d)})'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-warning me-1" onclick="toggleStatus(${d.id}, ${d.activo})">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteDiscount(${d.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay códigos registrados</td></tr>';
                }
            });
    }

    function resetForm() {
        document.getElementById('formDiscount').reset();
        document.getElementById('discountId').value = '';
        document.getElementById('modalTitle').innerText = 'Nuevo Código de Descuento';
    }

    function editDiscount(data) {
        document.getElementById('discountId').value = data.id;
        document.getElementById('code').value = data.codigo;
        document.getElementById('type').value = data.tipo;
        document.getElementById('value').value = data.valor;
        document.getElementById('expiration').value = data.fecha_expiracion;

        document.getElementById('modalTitle').innerText = 'Editar Código';
        new bootstrap.Modal(document.getElementById('modalDiscount')).show();
    }

    function saveDiscount() {
        const id = document.getElementById('discountId').value;
        const action = id ? 'update' : 'create';

        const formData = new FormData();
        formData.append('action', action);
        formData.append('id', id);
        formData.append('code', document.getElementById('code').value.toUpperCase());
        formData.append('type', document.getElementById('type').value);
        formData.append('value', document.getElementById('value').value);
        formData.append('expiration', document.getElementById('expiration').value);

        fetch('../../back/admin_discount_manager.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Éxito', data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalDiscount')).hide();
                    loadDiscounts();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
    }

    function toggleStatus(id, currentStatus) {
        const newStatus = currentStatus == 1 ? 0 : 1;
        const formData = new FormData();
        formData.append('action', 'toggle_status');
        formData.append('id', id);
        formData.append('status', newStatus);

        fetch('../../back/admin_discount_manager.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') loadDiscounts();
            });
    }

    function deleteDiscount(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('../../back/admin_discount_manager.php', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Eliminado', 'El código ha sido eliminado.', 'success');
                            loadDiscounts();
                        }
                    });
            }
        });
    }
</script>