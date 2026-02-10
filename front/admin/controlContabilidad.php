<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Control de Facturación</h2>
        <div>
            <span class="badge bg-white text-dark border p-2 shadow-sm">
                <i class="fas fa-user-tie me-2"></i>
                <?php echo $_SESSION['user_name']; ?>
            </span>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Solicitudes de Factura</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">Pedido</th>
                            <th class="border-0">Fecha</th>
                            <th class="border-0">Cliente</th>
                            <th class="border-0">RFC</th>
                            <th class="border-0">Total</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 rounded-end text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-requests-container">
                        <!-- Content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Subir Factura (Pedido #<span id="uploadOrderIdText"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="uploadOrderId">

                <div class="mb-3">
                    <label class="form-label fw-bold small">Archivo PDF</label>
                    <div class="drop-zone p-4 text-center border rounded bg-light" id="dropZonePdf"
                        onclick="document.getElementById('filePdf').click()">
                        <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                        <p class="small text-muted mb-0">Arrastra o click para subir PDF</p>
                        <span id="fileNamePdf" class="d-block small fw-bold text-dark mt-2"></span>
                    </div>
                    <input type="file" id="filePdf" accept="application/pdf" class="d-none"
                        onchange="handleFileSelect(this, 'fileNamePdf', 'dropZonePdf')">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small">Archivo XML</label>
                    <div class="drop-zone p-4 text-center border rounded bg-light" id="dropZoneXml"
                        onclick="document.getElementById('fileXml').click()">
                        <i class="fas fa-file-code fa-2x text-primary mb-2"></i>
                        <p class="small text-muted mb-0">Arrastra o click para subir XML</p>
                        <span id="fileNameXml" class="d-block small fw-bold text-dark mt-2"></span>
                    </div>
                    <input type="file" id="fileXml" accept=".xml" class="d-none"
                        onchange="handleFileSelect(this, 'fileNameXml', 'dropZoneXml')">
                </div>

                <div class="d-grid">
                    <button class="btn btn-success fw-bold rounded-pill py-2" onclick="uploadFiles()">
                        Subir Archivos y Notificar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .drop-zone {
        border: 2px dashed #ccc !important;
        cursor: pointer;
        transition: all 0.2s;
    }

    .drop-zone:hover {
        background-color: #e9ecef !important;
        border-color: #4EAE3E !important;
    }

    .drop-zone.active {
        background-color: #d1e7dd !important;
        border-color: #198754 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', loadRequests);

    function loadRequests() {
        const formData = new FormData();
        formData.append('action', 'get_requests');

        fetch('../../back/admin_accounting.php', { method: 'POST', body: formData })
            .then(res => res.text()) // Get text first
            .then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON Parse Error:', text);
                    throw new Error('Server returned invalid JSON: ' + text.substring(0, 100));
                }
            })
            .then(data => {
                const container = document.getElementById('invoice-requests-container');
                container.innerHTML = '';

                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(order => {
                        const hasFiles = order.invoice_pdf && order.invoice_xml;
                        const statusBadge = hasFiles
                            ? '<span class="badge bg-success bg-opacity-10 text-success px-3 rounded-pill">Facturado</span>'
                            : '<span class="badge bg-warning bg-opacity-10 text-warning px-3 rounded-pill">Pendiente</span>';

                        const actionBtn = hasFiles
                            ? `<button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="openUploadModal(${order.id}, true)">Ver / Reemplazar</button>`
                            : `<button class="btn btn-sm btn-primary rounded-pill px-3" onclick="openUploadModal(${order.id}, false)">Subir Factura</button>`;

                        const row = `
                            <tr>
                                <td class="fw-bold">#ORD-${order.id}</td>
                                <td>${new Date(order.fecha).toLocaleDateString()}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold small">${order.cliente_nombre}</span>
                                        <span class="text-muted small" style="font-size: 0.75rem;">${order.cliente_email}</span>
                                    </div>
                                </td>
                                <td>${order.rfc || '<span class="text-muted">-</span>'}</td>
                                <td class="fw-bold">$${parseFloat(order.total).toFixed(2)}</td>
                                <td>${statusBadge}</td>
                                <td class="text-end">${actionBtn}</td>
                            </tr>
                        `;
                        container.innerHTML += row;
                    });
                } else {
                    container.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">No hay solicitudes de facturación pendientes.</td></tr>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('invoice-requests-container').innerHTML =
                    `<tr><td colspan="7" class="text-center py-5 text-danger">Error cargando datos: ${err.message}</td></tr>`;
            });
    }

    function openUploadModal(orderId, isEdit) {
        document.getElementById('uploadOrderId').value = orderId;
        document.getElementById('uploadOrderIdText').innerText = orderId;

        // Reset fields
        document.getElementById('filePdf').value = '';
        document.getElementById('fileNamePdf').innerText = '';
        document.getElementById('dropZonePdf').classList.remove('active');

        document.getElementById('fileXml').value = '';
        document.getElementById('fileNameXml').innerText = '';
        document.getElementById('dropZoneXml').classList.remove('active');

        new bootstrap.Modal(document.getElementById('uploadModal')).show();
    }

    function handleFileSelect(input, labelId, zoneId) {
        const file = input.files[0];
        if (file) {
            document.getElementById(labelId).innerText = file.name;
            document.getElementById(zoneId).classList.add('active');
        }
    }

    // Add Drag and Drop listeners
    ['dropZonePdf', 'dropZoneXml'].forEach(id => {
        const zone = document.getElementById(id);
        const inputId = id === 'dropZonePdf' ? 'filePdf' : 'fileXml';

        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('active');
        });

        zone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            if (!document.getElementById(inputId).files[0]) {
                zone.classList.remove('active');
            }
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            const input = document.getElementById(inputId);
            input.files = e.dataTransfer.files;
            handleFileSelect(input, id === 'dropZonePdf' ? 'fileNamePdf' : 'fileNameXml', id);
        });
    });

    function uploadFiles() {
        const orderId = document.getElementById('uploadOrderId').value;
        const pdf = document.getElementById('filePdf').files[0];
        const xml = document.getElementById('fileXml').files[0];

        if (!pdf || !xml) {
            alert('Debes seleccionar ambos archivos (PDF y XML)');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'upload_invoice');
        formData.append('order_id', orderId);
        formData.append('file_pdf', pdf);
        formData.append('file_xml', xml);

        const btn = document.querySelector('#uploadModal button.btn-success');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Subiendo...';

        fetch('../../back/admin_accounting.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerText = originalText;

                if (data.status === 'success') {
                    alert('Factura subida correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
                    loadRequests();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerText = originalText;
                console.error(err);
                alert('Error de conexión');
            });
    }
</script>