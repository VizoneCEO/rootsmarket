<div class="container my-5">
    <h2 class="fw-bold mb-4">Hola, <br> <?php echo htmlspecialchars($user['nombre']); ?></h2>
    <p class="text-end text-green fw-bold mb-3"><?php echo htmlspecialchars($user['email']); ?></p>
    <hr>

    <style>
        /* Styling based on Screenshots */
        .profile-sidebar {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .profile-link {
            color: #555;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.2s;
            font-weight: 500;
            text-decoration: none;
            display: block;
            margin-bottom: 5px;
        }

        .profile-link:hover,
        .profile-link.active {
            background-color: #F5F5F5;
            color: #000;
            font-weight: 700;
            text-decoration: none;
        }

        /* Form Styles */
        .form-label-custom {
            font-weight: 700;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 5px;
        }

        .form-control-custom {
            border-radius: 25px;
            padding: 10px 20px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        .form-control-custom:focus {
            box-shadow: none;
            border-color: #4EAE3E;
        }

        .btn-save-custom {
            background-color: #387C2B;
            /* Dark Green */
            color: white;
            font-weight: 700;
            border-radius: 50px;
            padding: 12px 60px;
            border: none;
            font-size: 1.1rem;
            transition: background 0.3s;
        }

        .btn-save-custom:hover {
            background-color: #2e6623;
            color: white;
        }

        /* Progress Bar custom styles */
        .progress-track {
            position: relative;
            height: 6px;
            background-color: #D3D3D3;
            border-radius: 10px;
            margin: 40px 0 60px 0;
        }

        .progress-fill {
            position: absolute;
            height: 100%;
            background-color: #4EAE3E;
            border-radius: 10px;
            top: 0;
            left: 0;
        }

        .progress-node {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 30px;
            height: 30px;
            background-color: #D3D3D3;
            border-radius: 50%;
            z-index: 2;
        }

        .progress-node.active {
            background-color: #4EAE3E;
            width: 40px;
            height: 40px;
        }

        .node-label-bottom {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8rem;
            color: #888;
            white-space: nowrap;
        }

        .current-badge {
            position: absolute;
            top: -45px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #F39C12;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .current-badge::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px 5px 0;
            border-style: solid;
            border-color: #F39C12 transparent transparent transparent;
        }
    </style>

    <div class="container my-5">
        <div class="row">
            <!-- Sidebar Menu -->
            <div class="col-md-3 mb-4">
                <div class="profile-sidebar">
                    <a href="#" class="profile-link active" onclick="showSection('personal', this)">Mis Datos
                        Personales</a>
                    <a href="#" class="profile-link" onclick="showSection('facturacion', this)">Facturación</a>
                    <a href="#" class="profile-link" onclick="showSection('pedidos', this)">Mis Pedidos</a>
                    <a href="#" class="profile-link" onclick="showSection('direcciones', this)">Mis Direcciones</a>
                    <!-- <a href="#" class="profile-link" onclick="showSection('progreso', this)">Mi Progreso</a> -->
                    <a href="#" class="profile-link" onclick="showSection('ayuda', this)">Ayuda</a>
                    <div class="border-top mt-3 pt-3">
                        <a href="../../back/login/aut.php?action=logout" class="profile-link text-danger">Salir</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="bg-white p-4 rounded-3 shadow-sm" style="min-height: 500px;">

                    <!-- 1. MIS DATOS PERSONALES -->
                    <div id="section-personal" class="profile-section">
                        <h2 class="fw-bold mb-1">Hola,</h2>
                        <h2 class="fw-bold mb-4"><?php echo htmlspecialchars($user['nombre']); ?></h2>
                        <!-- Apellido could be split if available -->

                        <h6 class="text-muted mb-4 small">Mis Datos Personales</h6>

                        <form>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Nombre</label>
                                    <input type="text" id="inputNombre" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['nombre']); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Primer Apellido</label>
                                    <input type="text" id="inputApellidoPaterno"
                                        class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['apellido_paterno'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Segundo Apellido</label>
                                    <input type="text" id="inputApellidoMaterno"
                                        class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['apellido_materno'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Teléfono</label>
                                    <input type="text" id="inputTelefono" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Dirección</label>
                                    <input type="text" id="inputDireccion" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['direccion'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-custom">Ciudad</label>
                                    <input type="text" id="inputCiudad" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['ciudad'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-custom">Estado</label>
                                    <input type="text" id="inputEstado" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['estado'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Correo</label>
                                    <input type="email" id="inputEmail" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['email']); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Contraseña</label>
                                    <div class="position-relative">
                                        <input type="password" id="inputPassword"
                                            class="form-control form-control-custom" value="******">
                                        <i
                                            class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="termsProfile"
                                    style="border-radius: 4px;">
                                <label class="form-check-label text-muted small" for="termsProfile">
                                    He leído y acepto los <a href="#" class="text-secondary">Términos de servicio</a> y
                                    la <a href="#" class="text-secondary">Política de privacidad</a>.
                                </label>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn-save-custom"
                                    onclick="savePersonalData()">Guardar</button>
                            </div>
                        </form>
                    </div>

                    <!-- 2. FACTURACIÓN -->
                    <div id="section-facturacion" class="profile-section" style="display: none;">
                        <h2 class="fw-bold mb-1">Hola,</h2>
                        <h2 class="fw-bold mb-4"><?php echo htmlspecialchars($user['nombre']); ?></h2>

                        <h6 class="text-muted mb-4 small">Facturación</h6>

                        <form>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Nombre / Razón Social</label>
                                    <input type="text" id="inputRazonSocial" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['razon_social'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">RFC</label>
                                    <input type="text" id="inputRfc" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['rfc'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Régimen Fiscal</label>
                                    <select class="form-select form-control-custom" id="inputRegimenFiscal"
                                        style="padding-right: 30px;">
                                        <option
                                            value="Régimen de Personas Físicas con Actividades Empresariales y Profesionales"
                                            <?php echo ($user['regimen_fiscal'] ?? '') == 'Régimen de Personas Físicas con Actividades Empresariales y Profesionales' ? 'selected' : ''; ?>>Régimen de
                                            Personas Físicas con Actividades Empresariales y Profesionales</option>
                                        <option value="Sueldos y Salarios" <?php echo ($user['regimen_fiscal'] ?? '') == 'Sueldos y Salarios' ? 'selected' : ''; ?>>Sueldos y Salarios</option>
                                        <option value="RESICO" <?php echo ($user['regimen_fiscal'] ?? '') == 'RESICO' ? 'selected' : ''; ?>>RESICO</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-5">
                                <div class="col-md-4">
                                    <label class="form-label-custom">C.P. Fiscal</label>
                                    <input type="text" id="inputCpFiscal" class="form-control form-control-custom"
                                        value="<?php echo htmlspecialchars($user['cp_fiscal'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Uso De CFDI</label>
                                    <select class="form-select form-control-custom" id="inputUsoCfdi">
                                        <option value="G03 - Gastos en general" <?php echo ($user['uso_cfdi'] ?? '') == 'G03 - Gastos en general' ? 'selected' : ''; ?>>G03 - Gastos en general
                                        </option>
                                        <option value="P01 - Por definir" <?php echo ($user['uso_cfdi'] ?? '') == 'P01 - Por definir' ? 'selected' : ''; ?>>P01 - Por definir</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="termsBilling"
                                    style="border-radius: 4px;">
                                <label class="form-check-label text-muted small" for="termsBilling">
                                    He leído y acepto los <a href="#" class="text-secondary">Términos de servicio</a> y
                                    la <a href="#" class="text-secondary">Política de privacidad</a>.
                                </label>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn-save-custom"
                                    onclick="saveBillingData()">Guardar</button>
                            </div>
                        </form>
                    </div>

                    <!-- 3. MI PROGRESO -->
                    <div id="section-progreso" class="profile-section" style="display: none;">
                        <!-- MODULE DISABLED BY USER REQUEST
                        <h2 class="fw-bold mb-0">Hola, <?php echo htmlspecialchars($user['nombre']); ?></h2>
                        <h3 class="fw-bold mb-5">¡Conoce tu progreso!</h3>

                        <div class="mb-5">
                            <h6 class="fw-bold text-muted mb-2">Raíces Verdes</h6>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: 35%;"></div>

                                <div class="progress-node active" style="left: 35%;">
                                    <div class="current-badge">Tu impacto verde</div>
                                    <div class="node-label-bottom fw-bold text-success">269 Puntos</div>
                                </div>

                                <div class="progress-node" style="left: 55%;">
                                    <div class="node-label-bottom">500 Puntos</div>
                                </div>

                                <div class="progress-node" style="left: 75%;">
                                    <div class="node-label-bottom">750 Puntos</div>
                                </div>

                                <div class="progress-node"
                                    style="left: 95%; width: 50px; height: 50px; background-color: #D3D3D3;">
                                    <div class="node-label-bottom">1 Árbol</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold text-muted mb-2">Cero Basura</h6>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: 18%;"></div>

                                <div class="progress-node active" style="left: 18%;">
                                    <div class="current-badge"
                                        style="background-color: #E67E22; border-color: #E67E22 transparent transparent transparent;">
                                        Menos basura, más impacto</div>
                                    <div class="node-label-bottom fw-bold text-success">180 Puntos</div>
                                </div>

                                <div class="progress-node" style="left: 25%;">
                                    <div class="node-label-bottom">250 Puntos</div>
                                </div>

                                <div class="progress-node" style="left: 50%;">
                                    <div class="node-label-bottom">500 Puntos</div>
                                </div>

                                <div class="progress-node" style="left: 75%;">
                                    <div class="node-label-bottom">750 Puntos</div>
                                </div>

                                <div class="progress-node"
                                    style="left: 95%; width: 50px; height: 50px; background-color: #D3D3D3;">
                                    <div class="node-label-bottom">1000 Puntos</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="tienda.php" class="btn-save-custom text-decoration-none">Compra con propósito</a>
                        </div>
                        -->
                    </div>

                    <!-- 4. MIS PEDIDOS -->
                    <div id="section-pedidos" class="profile-section" style="display: none;">
                        <h4 class="fw-bold mb-4">Mis Compras</h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="border-0 rounded-start">Pedido #</th>
                                        <th scope="col" class="border-0">Fecha</th>
                                        <th scope="col" class="border-0">Total</th>
                                        <th scope="col" class="border-0">Pago</th>
                                        <th scope="col" class="border-0">Envío</th>
                                        <th scope="col" class="border-0 rounded-end text-end">Detalles</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-container">
                                    <!-- Dynamic content -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 5. MIS DIRECCIONES -->
                    <div id="section-direcciones" class="profile-section" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold m-0">Mis Direcciones</h4>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal"
                                data-bs-target="#addressModal" onclick="resetAddressForm()">+ Nueva Dirección</button>
                        </div>
                        <div class="row g-3" id="addresses-container">
                            <!-- Addresses will be loaded here via JS -->
                        </div>
                    </div>

                    <!-- Address Modal -->
                    <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Agregar/Editar Dirección</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addressForm">
                                        <input type="hidden" id="addressId">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Alias (Ej. Casa,
                                                    Oficina)</label>
                                                <input type="text" class="form-control rounded-pill" id="addrAlias"
                                                    placeholder="Ej. Casa">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Nombre de quien recibe</label>
                                                <input type="text" class="form-control rounded-pill" id="addrNombre"
                                                    value="<?php echo htmlspecialchars($user['nombre']); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Teléfono de contacto</label>
                                                <input type="text" class="form-control rounded-pill" id="addrTelefono"
                                                    value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Código Postal</label>
                                                <input type="text" class="form-control rounded-pill" id="addrCP">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold small">Calle y Número</label>
                                                <input type="text" class="form-control rounded-pill" id="addrCalle">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">Colonia</label>
                                                <input type="text" class="form-control rounded-pill" id="addrColonia">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">Ciudad</label>
                                                <input type="text" class="form-control rounded-pill" id="addrCiudad"
                                                    value="<?php echo htmlspecialchars($user['ciudad'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">Estado</label>
                                                <input type="text" class="form-control rounded-pill" id="addrEstado"
                                                    value="<?php echo htmlspecialchars($user['estado'] ?? ''); ?>">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="addrDefault">
                                                    <label class="form-check-label small" for="addrDefault">
                                                        Establecer como dirección predeterminada
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary rounded-pill"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success rounded-pill px-4"
                                        onclick="saveAddress()">Guardar Dirección</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. AYUDA (Placeholder) -->
                <div id="section-ayuda" class="profile-section" style="display: none;">
                    <h4 class="fw-bold mb-4">Ayuda</h4>
                    <p>¿Tienes dudas? Contáctanos.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showSection(sectionId, linkElement) {
        // Hide all sections
        const sections = document.querySelectorAll('.profile-section');
        sections.forEach(sec => sec.style.display = 'none');

        // Show target section
        const target = document.getElementById('section-' + sectionId);
        if (target) {
            target.style.display = 'block';
            if (sectionId === 'direcciones') {
                loadAddresses();
            } else if (sectionId === 'pedidos') {
                loadOrders();
            }
        }

        // Update active link state
        const links = document.querySelectorAll('.profile-link');
        links.forEach(link => link.classList.remove('active'));
        if (linkElement) {
            linkElement.classList.add('active');
        }
    }

    // Check URL params for section
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');
        if (section) {
            showSection(section);
        }
    });

    function savePersonalData() {
        const data = new FormData();
        data.append('action', 'update_personal');
        data.append('nombre', document.getElementById('inputNombre').value);
        data.append('apellido_paterno', document.getElementById('inputApellidoPaterno').value);
        data.append('apellido_materno', document.getElementById('inputApellidoMaterno').value);
        data.append('telefono', document.getElementById('inputTelefono').value);
        data.append('direccion', document.getElementById('inputDireccion').value);
        data.append('ciudad', document.getElementById('inputCiudad').value);
        data.append('estado', document.getElementById('inputEstado').value);
        data.append('email', document.getElementById('inputEmail').value);
        data.append('password', document.getElementById('inputPassword').value);

        fetch('../../back/client_manager.php', {
            method: 'POST',
            body: data
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Datos actualizados correctamente');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al guardar los datos');
            });
    }

    function saveBillingData() {
        const data = new FormData();
        data.append('action', 'update_billing');
        data.append('razon_social', document.getElementById('inputRazonSocial').value);
        data.append('rfc', document.getElementById('inputRfc').value);
        data.append('regimen_fiscal', document.getElementById('inputRegimenFiscal').value);
        data.append('cp_fiscal', document.getElementById('inputCpFiscal').value);
        data.append('uso_cfdi', document.getElementById('inputUsoCfdi').value);

        fetch('../../back/client_manager.php', {
            method: 'POST',
            body: data
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Datos de facturación actualizados correctamente');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al guardar los datos');
            });
    }

    /* --- ORDER FUNCTIONS --- */
    function loadOrders() {
        const formData = new FormData();
        formData.append('action', 'get_orders');

        fetch('../../back/client_manager.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('orders-container');
                container.innerHTML = '';

                // Store orders globally for access
                window.userOrders = data.data;

                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach((order, index) => {
                        let statusBadge = '';
                        switch (order.estatus) {
                            case 'pagado': statusBadge = '<span class="badge bg-primary text-white px-3 py-2 rounded-pill">Pagado</span>'; break;
                            case 'enviado': statusBadge = '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Enviado</span>'; break;
                            case 'entregado': statusBadge = '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Entregado</span>'; break;
                            case 'cancelado': statusBadge = '<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Cancelado</span>'; break;
                            default: statusBadge = `<span class="badge bg-light text-dark border px-3 py-2 rounded-pill">${order.estatus}</span>`;
                        }

                        let shippingBadge = '';
                        switch (order.estatus_envio) {
                            case 'en_preparacion': shippingBadge = '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill">En Preparación</span>'; break;
                            case 'enviado': shippingBadge = '<span class="badge bg-info text-white px-3 py-2 rounded-pill">Enviado</span>'; break;
                            case 'entregado': shippingBadge = '<span class="badge bg-success text-white px-3 py-2 rounded-pill">Entregado</span>'; break;
                            default: shippingBadge = `<span class="badge bg-light text-dark border px-3 py-2 rounded-pill">${order.estatus_envio}</span>`;
                        }

                        let invoiceBtn = '';
                        // Logic:
                        // 1. If files exist -> Show Download Buttons (Force Download)
                        // 2. If requested but pending -> Show Pending Icon
                        // 3. If not requested -> Show "Solicitar Factura" button instead of "Ver" (User requested replacement)

                        if (order.invoice_pdf && order.invoice_xml) {
                            invoiceBtn = `
                                <a href="../../${order.invoice_pdf}" download class="btn btn-sm btn-outline-danger border-0" title="Descargar PDF"><i class="fas fa-file-pdf"></i></a>
                                <a href="../../${order.invoice_xml}" download class="btn btn-sm btn-outline-primary border-0" title="Descargar XML"><i class="fas fa-file-code"></i></a>
                             `;
                        } else if (order.solicita_factura == 1) {
                            invoiceBtn = `<button class="btn btn-sm btn-outline-secondary border-0" disabled title="Factura Solicitada"><i class="fas fa-clock"></i></button>`;
                        } else {
                            // HERE: User wants "Solicitar Factura" button primarily.
                            // We can keep the "Ver" button for details, but user asked "en vez del boton verde en detalles pon un boton. que diga solicitar factura"
                            // The "Ver" button was: <button class="btn btn-sm btn-outline-secondary rounded-pill ms-1" onclick="showOrderDetails(${index})">Ver</button>
                            // I will replace the "Ver" button with the "Solicitar Factura" button in the main row.
                            // And maybe keep a small icon for details if needed, or just follow instruction to replace.
                            // Let's replace the "Ver" button with "Solicitar Factura" and keep a small 'eye' icon for details just in case they need to see what they bought.
                            invoiceBtn = `<button class="btn btn-sm btn-success rounded-pill" onclick="requestInvoice(${order.id})">Solicitar Factura</button>`;
                        }

                        const date = new Date(order.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });

                        const html = `
                        <tr>
                            <td class="fw-bold">#ORD-${order.id}</td>
                            <td>${date}</td>
                            <td>$${parseFloat(order.total).toFixed(2)}</td>
                            <td>${statusBadge}</td>
                            <td>${shippingBadge}</td>
                            <td class="text-end">
                                ${invoiceBtn}
                                <a href="../admin/print_order.php?id=${order.id}" target="_blank" class="btn btn-sm btn-outline-dark border-0 ms-1" title="Ver Recibo"><i class="fas fa-receipt"></i></a>
                                ${(!order.invoice_pdf && order.solicita_factura != 1) ?
                                `<button class="btn btn-sm btn-outline-secondary border-0 ms-1" onclick="showOrderDetails(${index})" title="Ver Detalles"><i class="fas fa-eye"></i></button>` :
                                `<button class="btn btn-sm btn-outline-secondary border-0 ms-1" onclick="showOrderDetails(${index})" title="Ver Detalles"><i class="fas fa-eye"></i></button>`
                            }
                            </td>
                        </tr>
                    `;
                        container.innerHTML += html;
                    });
                } else {
                    container.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No has realizado ninguna compra aún.</td></tr>';
                }
            });
    }

    function showOrderDetails(index) {
        const order = window.userOrders[index];
        const orderId = order.id;
        document.getElementById('orderModalTitle').innerText = 'Detalles del Pedido #' + order.id;
        document.getElementById('orderModalId').innerText = '#ORD-' + order.id;
        document.getElementById('orderModalDate').innerText = new Date(order.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });

        let addr = {};
        try { addr = JSON.parse(order.direccion_envio); } catch (e) { }

        document.getElementById('orderModalAddress').innerHTML = `
            ${addr.calle_numero || ''}<br>
            ${addr.colonia || ''}, CP ${addr.codigo_postal || ''}<br>
            ${addr.ciudad || ''}, ${addr.estado || ''}
        `;

        const itemsContainer = document.getElementById('orderModalItems');
        let itemsHtml = '';
        if (order.detalles) {
            order.detalles.forEach(item => {
                const total = item.cantidad * item.precio_unitario;
                itemsHtml += `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-box text-muted me-3"></i>
                            <div>
                                <p class="mb-0 fw-bold small">${item.nombre_producto}</p>
                                <p class="mb-0 small text-muted">${item.cantidad} x $${parseFloat(item.precio_unitario).toFixed(2)}</p>
                            </div>
                        </div>
                        <span class="fw-bold small">$${total.toFixed(2)}</span>
                    </div>
                `;
            });
        }
        itemsContainer.innerHTML = itemsHtml;

        document.getElementById('orderModalSubtotal').innerText = '$' + parseFloat(order.total).toFixed(2);
        document.getElementById('orderModalTotal').innerText = '$' + parseFloat(order.total).toFixed(2);

        // --- INVOICE LOGIC ---
        const footer = document.querySelector('#orderDetailsModal .modal-footer');
        // Clear previous buttons but keep Close
        footer.innerHTML = '<button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cerrar</button>';

        // Check invoice status
        if (order.invoice_pdf && order.invoice_xml) {
            // Files available
            const pdfBtn = `<a href="../../${order.invoice_pdf}" target="_blank" class="btn btn-danger text-white rounded-pill ms-2"><i class="fas fa-file-pdf me-2"></i>PDF</a>`;
            const xmlBtn = `<a href="../../${order.invoice_xml}" target="_blank" class="btn btn-primary text-white rounded-pill ms-2"><i class="fas fa-file-code me-2"></i>XML</a>`;
            footer.innerHTML += pdfBtn + xmlBtn;
        } else if (order.solicita_factura == 1) {
            // Requested but pending
            footer.innerHTML += '<button class="btn btn-secondary rounded-pill ms-2" disabled><i class="fas fa-clock me-2"></i>Factura Solicitada</button>';
        } else {
            // Can request
            footer.innerHTML += `<button class="btn btn-success rounded-pill ms-2" onclick="requestInvoice(${order.id})"><i class="fas fa-file-invoice me-2"></i>Solicitar Factura</button>`;
        }

        var myModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        myModal.show();
    }

    function requestInvoice(orderId) {
        if (!confirm('¿Deseas solicitar la factura para este pedido?')) return;

        const data = new FormData();
        data.append('action', 'request_invoice');
        data.append('order_id', orderId);

        fetch('../../back/client_manager.php', {
            method: 'POST',
            body: data
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Solicitud enviada correctamente');
                    // Reload orders to update UI
                    loadOrders();
                    // Close modal
                    const modalEl = document.getElementById('orderDetailsModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => console.error(err));
    }

    /* --- ADDRESS FUNCTIONS --- */
    function loadAddresses() {
        const formData = new FormData();
        formData.append('action', 'get_addresses');

        fetch('../../back/client_manager.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('addresses-container');
                container.innerHTML = '';

                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(addr => {
                        const isDefault = addr.es_principal == 1;
                        const cardClass = isDefault ? 'border-success shadow-sm' : 'border-light bg-light';
                        const badge = isDefault ? '<span class="badge bg-success">Predeterminada</span>' : '';
                        const defaultBtn = !isDefault ? `<button class="btn btn-sm btn-link text-decoration-none p-0 ms-auto text-success" onclick="setDefaultAddress(${addr.id})">Hacer default</button>` : '';

                        const html = `
                        <div class="col-md-6">
                            <div class="card h-100 ${cardClass}" style="border-width: 1px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold mb-0 text-capitalize">${addr.alias}</h6>
                                        ${badge}
                                    </div>
                                    <p class="text-muted small mb-3">
                                        ${addr.calle_numero}<br>
                                        ${addr.colonia}, ${addr.ciudad}<br>
                                        ${addr.estado}, CP ${addr.codigo_postal}<br>
                                        <strong>Recibe:</strong> ${addr.nombre_contacto}
                                    </p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-light text-danger" onclick="deleteAddress(${addr.id})">Eliminar</button>
                                        ${defaultBtn}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                        container.innerHTML += html;
                    });
                } else {
                    container.innerHTML = '<div class="col-12 text-center text-muted">No tienes direcciones guardadas.</div>';
                }
            });
    }

    function resetAddressForm() {
        document.getElementById('addressForm').reset();
        document.getElementById('addressId').value = '';
    }

    function saveAddress() {
        const data = new FormData();
        data.append('action', 'add_address');
        data.append('alias', document.getElementById('addrAlias').value);
        data.append('nombre_contacto', document.getElementById('addrNombre').value);
        data.append('telefono_contacto', document.getElementById('addrTelefono').value);
        data.append('calle_numero', document.getElementById('addrCalle').value);
        data.append('codigo_postal', document.getElementById('addrCP').value);
        data.append('colonia', document.getElementById('addrColonia').value);
        data.append('ciudad', document.getElementById('addrCiudad').value);
        data.append('estado', document.getElementById('addrEstado').value);
        if (document.getElementById('addrDefault').checked) {
            data.append('es_principal', 'on');
        }

        fetch('../../back/client_manager.php', {
            method: 'POST',
            body: data
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Close modal
                    const modalEl = document.getElementById('addressModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    loadAddresses();
                    alert('Dirección guardada');
                } else {
                    alert('Error: ' + data.message);
                }
            });
    }

    function deleteAddress(id) {
        if (!confirm('¿Estás seguro de eliminar esta dirección?')) return;

        const data = new FormData();
        data.append('action', 'delete_address');
        data.append('address_id', id);

        fetch('../../back/client_manager.php', { method: 'POST', body: data })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    loadAddresses();
                } else {
                    alert(data.message);
                }
            });
    }

    function setDefaultAddress(id) {
        const data = new FormData();
        data.append('action', 'set_default_address');
        data.append('address_id', id);

        fetch('../../back/client_manager.php', { method: 'POST', body: data })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    loadAddresses();
                } else {
                    alert(data.message);
                }
            });
    }
</script>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="orderModalTitle">Detalles del Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Pedido</p>
                        <h6 class="fw-bold" id="orderModalId">#ORD-0000</h6>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="text-muted small mb-1">Fecha</p>
                        <h6 class="fw-bold" id="orderModalDate">01 Ene, 2026</h6>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold small text-muted mb-2">DIRECCIÓN DE ENVÍO</h6>
                        <p class="small mb-0" id="orderModalAddress">
                            Calle Falsa 123<br>Colonia Centro<br>Ciudad, Estado
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold small text-muted mb-2">MÉTODO DE PAGO</h6>
                        <p class="small mb-0"><i class="fas fa-credit-card me-2"></i> Tarjeta de Crédito</p>
                    </div>
                </div>

                <h6 class="fw-bold small text-muted mb-3">PRODUCTOS</h6>
                <div id="orderModalItems" class="mb-4">
                    <!-- Items injected here -->
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span>Subtotal</span>
                        <span id="orderModalSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span>Envío</span>
                        <span>$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span id="orderModalTotal">$0.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</div>