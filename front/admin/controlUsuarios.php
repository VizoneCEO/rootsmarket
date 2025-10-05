<?php
//======================================================================
// INICIO DE LA LÓGICA DE LA PÁGINA
//======================================================================
session_start();

// --- CONEXIÓN A LA BASE DE DATOS ---
require_once(__DIR__ . '/../../back/conection/db.php');

// --- CONSULTA #1: OBTENER TODOS LOS USUARIOS CON SUS ROLES ---
try {
    $stmt_usuarios = $pdo->prepare(
        "SELECT u.id, u.nombre, u.email, u.estatus, u.rol_id, r.nombre_rol 
         FROM usuarios u 
         JOIN roles r ON u.rol_id = r.id 
         ORDER BY u.id ASC"
    );
    $stmt_usuarios->execute();
    $usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar los usuarios: " . $e->getMessage());
}

// --- CONSULTA #2: OBTENER TODOS LOS ROLES DISPONIBLES (para los modales) ---
try {
    $stmt_roles = $pdo->prepare("SELECT id, nombre_rol FROM roles ORDER BY nombre_rol ASC");
    $stmt_roles->execute();
    $roles = $stmt_roles->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar los roles: " . $e->getMessage());
}
//======================================================================
// FIN DE LA LÓGICA DE LA PÁGINA
//======================================================================
?>
<div class="container-fluid">
    <?php
    // --- SECCIÓN PARA MOSTRAR MENSAJES DE ÉXITO O ERROR ---
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Control de Usuarios</h1>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus me-2"></i>Añadir Nuevo Usuario
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr><td colspan="6" class="text-center">No hay usuarios registrados.</td></tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo htmlspecialchars(ucfirst($usuario['nombre_rol'])); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $usuario['estatus'] == 'activo' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo ucfirst($usuario['estatus']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-user-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal"
                                                data-usuario='<?php echo htmlspecialchars(json_encode($usuario), ENT_QUOTES, 'UTF-8'); ?>'
                                                title="Editar Usuario">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-user-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteUserModal"
                                                data-id="<?php echo $usuario['id']; ?>"
                                                data-nombre="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                                                title="Eliminar Usuario">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Añadir Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../back/user_manager.php?action=create" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_nombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="add_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="add_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="add_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_rol_id" class="form-label">Rol</label>
                        <select class="form-select" id="add_rol_id" name="rol_id" required>
                            <option value="" disabled selected>Selecciona un rol...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars(ucfirst($rol['nombre_rol'])); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../back/user_manager.php?action=update" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="edit_email" name="email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_rol_id" class="form-label">Rol</label>
                        <select class="form-select" id="edit_rol_id" name="rol_id" required>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars(ucfirst($rol['nombre_rol'])); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estatus" class="form-label">Estatus</label>
                        <select class="form-select" id="edit_estatus" name="estatus" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="suspendido">Suspendido</option>
                        </select>
                    </div>
                    <hr>
                    <p class="small text-muted">Deja el campo de contraseña en blanco para no modificarla.</p>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Nueva Contraseña (Opcional)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirmar Eliminación de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar al usuario "<strong id="delete_user_name"></strong>"?</p>
                <p class="text-danger small">Esta acción es irreversible.</p>
            </div>
            <div class="modal-footer">
                <form action="../../back/user_manager.php?action=delete" method="POST">
                    <input type="hidden" id="delete_user_id" name="user_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Script para llenar el modal de EDITAR USUARIO ---
    const editUserModal = document.getElementById('editUserModal');
    if(editUserModal) {
        editUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const usuario = JSON.parse(button.getAttribute('data-usuario'));

            const modal = this;
            modal.querySelector('#edit_user_id').value = usuario.id;
            modal.querySelector('#edit_nombre').value = usuario.nombre;
            modal.querySelector('#edit_email').value = usuario.email;
            modal.querySelector('#edit_rol_id').value = usuario.rol_id;
            modal.querySelector('#edit_estatus').value = usuario.estatus;
            modal.querySelector('#edit_password').value = ''; // Limpiar campo de contraseña
        });
    }

    // --- Script para llenar el modal de ELIMINAR USUARIO ---
    const deleteUserModal = document.getElementById('deleteUserModal');
    if(deleteUserModal) {
        deleteUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');

            const modal = this;
            modal.querySelector('#delete_user_name').textContent = nombre;
            modal.querySelector('#delete_user_id').value = id;
        });
    }
});
</script>