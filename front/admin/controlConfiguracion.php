<div class="container-fluid">
    <h1 class="h2 mb-4">Gestión de Tienda y Perfil</h1>

    <ul class="nav nav-tabs mb-4" id="configTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="catalog-tab" data-bs-toggle="tab" data-bs-target="#catalog" type="button" role="tab" aria-controls="catalog" aria-selected="true"><i class="fas fa-list-alt me-2"></i>Catálogo de Productos</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="add-product-tab" data-bs-toggle="tab" data-bs-target="#addProduct" type="button" role="tab" aria-controls="addProduct" aria-selected="false"><i class="fas fa-plus me-2"></i>Nuevo Producto</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false"><i class="fas fa-tags me-2"></i>Categorías</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-user-shield me-2"></i>Admin Perfil</button>
        </li>
    </ul>

    <div class="tab-content" id="configTabContent">

        <div class="tab-pane fade show active" id="catalog" role="tabpanel" aria-labelledby="catalog-tab">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Todos los Productos</h5>
                        <form class="row g-2">
                            <div class="col-auto"><input type="text" class="form-control" placeholder="Buscar por nombre..."></div>
                            <div class="col-auto"><select class="form-select"><option selected>Categoría...</option></select></div>
                            <div class="col-auto"><button class="btn btn-primary" type="submit">Buscar</button></div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>ID</th><th>Imagen</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead>
                        <tbody>
                            <tr><td>101</td><td><img src="../front/multimedia/kiwi.png" class="product-img-sm"></td><td>Kiwi Orgánico (Kg)</td><td>Frutas y Verduras</td><td>$99.50</td><td>150</td><td><a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a><a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a></td></tr>
                            <tr><td>102</td><td><img src="../front/multimedia/papaya.png" class="product-img-sm"></td><td>Papaya Maradol (Pieza)</td><td>Frutas y Verduras</td><td>$45.00</td><td>80</td><td><a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a><a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white"><nav><ul class="pagination justify-content-end mb-0"><li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li><li class="page-item active"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">Siguiente</a></li></ul></nav></div>
            </div>
        </div>

        <div class="tab-pane fade" id="addProduct" role="tabpanel" aria-labelledby="add-product-tab">
            <div class="card shadow-sm border-0"><div class="card-header bg-white"><h5 class="mb-0">Detalles del Nuevo Producto</h5></div>
                <div class="card-body">
                    <form><div class="row g-3"><div class="col-md-8"><label for="productName" class="form-label">Nombre del Producto</label><input type="text" class="form-control" id="productName"></div><div class="col-md-4"><label for="productCategory" class="form-label">Categoría</label><select id="productCategory" class="form-select"><option selected>Elige...</option></select></div><div class="col-12"><label for="productDescription" class="form-label">Descripción</label><textarea class="form-control" id="productDescription" rows="3"></textarea></div><div class="col-md-4"><label for="productPrice" class="form-label">Precio ($)</label><input type="number" class="form-control" id="productPrice"></div><div class="col-md-4"><label for="productStock" class="form-label">Cantidad en Stock</label><input type="number" class="form-control" id="productStock"></div><div class="col-md-4"><label for="productImage" class="form-label">Imagen</label><input class="form-control" type="file" id="productImage"></div></div><hr class="my-4"><button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar Producto</button></form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
            <div class="row g-4"><div class="col-md-5"><div class="card shadow-sm border-0 h-100"><div class="card-header bg-white"><h5 class="mb-0">Añadir Nueva Categoría</h5></div><div class="card-body"><form><div class="mb-3"><label for="categoryName" class="form-label">Nombre</label><input type="text" class="form-control" id="categoryName"></div><button type="submit" class="btn btn-success">Añadir</button></form></div></div></div><div class="col-md-7"><div class="card shadow-sm border-0 h-100"><div class="card-header bg-white"><h5 class="mb-0">Categorías Existentes</h5></div><div class="card-body"><ul class="list-group"><li class="list-group-item d-flex justify-content-between align-items-center">Frutas y Verduras<a href="#" class="text-danger"><i class="fas fa-trash"></i></a></li><li class="list-group-item d-flex justify-content-between align-items-center">Despensa<a href="#" class="text-danger"><i class="fas fa-trash"></i></a></li></ul></div></div></div></div>
        </div>

        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card shadow-sm border-0"><div class="card-header bg-white"><h5 class="mb-0">Perfil del Administrador</h5></div>
                <div class="card-body">
                   <form><div class="row"><div class="col-md-6 mb-3"><label for="adminName" class="form-label">Nombre</label><input type="text" class="form-control" id="adminName" value="Admin"></div><div class="col-md-6 mb-3"><label for="adminEmail" class="form-label">Correo</label><input type="email" class="form-control" id="adminEmail" value="admin@roots.com" readonly></div></div><hr><h6 class="mb-3">Cambiar Contraseña</h6><div class="row"><div class="col-md-6 mb-3"><label for="currentPassword" class="form-label">Contraseña Actual</label><input type="password" class="form-control" id="currentPassword"></div><div class="col-md-6 mb-3"><label for="newPassword" class="form-label">Nueva Contraseña</label><input type="password" class="form-control" id="newPassword"></div></div><button type="submit" class="btn btn-primary">Actualizar Perfil</button></form>
                </div>
            </div>
        </div>

    </div>
</div>