<div class="container my-5">
    <h2 class="fw-bold mb-4">Hola, <br> <?php echo htmlspecialchars($user['nombre']); ?></h2>
    <p class="text-end text-green fw-bold mb-3"><?php echo htmlspecialchars($user['email']); ?></p>
    <hr>

    <div class="row">
        <div class="col-md-3">
            <div class="bg-light p-3 rounded shadow-sm profile-menu">
                <div class="mb-3">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
                        <i class="fas fa-user-circle me-2"></i> Información
                    </a>
                </div>
                <div class="mb-3">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
                        <i class="fas fa-box me-2"></i> Mis Pedidos
                    </a>
                </div>
                <div class="mb-3">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
                        <i class="fas fa-map-marker-alt me-2"></i> Mis Direcciones
                    </a>
                </div>
                <div class="mb-3">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
                        <i class="fas fa-credit-card me-2"></i> Métodos de Pago
                    </a>
                </div>
                <div>
                    <a href="../../back/login/aut.php?action=logout" class="d-flex align-items-center text-dark text-decoration-none">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="p-4 bg-light rounded shadow-sm">
                <h4 class="fw-bold mb-4">Información de la Cuenta</h4>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold">Nombre</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($user['nombre']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold">Correo Electrónico</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold">Dirección de Envío Principal</h5>
                        <p class="text-muted">- Aún no has agregado una dirección -</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold">Datos de Facturación</h5>
                        <p class="text-muted">- Aún no has agregado datos de facturación -</p>
                    </div>
                </div>
                <button class="btn" style="background-color: #2d4c48; color: white;">Editar Información</button>
            </div>
        </div>
    </div>
</div>