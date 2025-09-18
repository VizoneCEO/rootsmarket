<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Control de Usuarios</h1>
        <button class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Añadir Nuevo Usuario
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover">
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
                    <tr>
                        <td>1</td>
                        <td>Alberto Flores</td>
                        <td>alberto@ejemplo.com</td>
                        <td><span class="badge bg-primary">Administrador</span></td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>María González</td>
                        <td>maria@ejemplo.com</td>
                        <td><span class="badge bg-secondary">Cliente</span></td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                     <tr>
                        <td>3</td>
                        <td>Juan Pérez</td>
                        <td>juan@ejemplo.com</td>
                        <td><span class="badge bg-info">Deliver</span></td>
                        <td><span class="badge bg-warning text-dark">Inactivo</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div><?php
