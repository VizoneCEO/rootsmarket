<?php
//======================================================================
// INICIO DE LA LÓGICA DE LA PÁGINA
//======================================================================
session_start();

// --- CONEXIÓN A LA BASE DE DATOS (Ruta corregida) ---
require_once(__DIR__ . '/../../back/conection/db.php');

// --- CONSULTA #1: OBTENER TODOS LOS CATÁLOGOS (para la tabla de la pestaña "Catálogos") ---
try {
    // Renombramos la variable para evitar conflictos
    $stmt_todos_catalogos = $pdo->prepare("SELECT id, nombre, descripcion, estatus, imagen_url, icono_url FROM catalogos ORDER BY id DESC");
    $stmt_todos_catalogos->execute();
    $todos_los_catalogos = $stmt_todos_catalogos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar los catálogos: " . $e->getMessage());
}

// --- CONSULTA #2: OBTENER SOLO CATÁLOGOS ACTIVOS (para los formularios) ---
try {
    $stmt_catalogos_activos = $pdo->prepare("SELECT id, nombre FROM catalogos WHERE estatus = 'activo' ORDER BY nombre ASC");
    $stmt_catalogos_activos->execute();
    $catalogos_activos = $stmt_catalogos_activos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar los catálogos activos: " . $e->getMessage());
}

// --- CONSULTA #3: OBTENER PRODUCTOS CON BÚSQUEDA Y PAGINACIÓN ---
try {
    // 1. Capturar parámetros de búsqueda y paginación
    $search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
    $filter_type = isset($_GET['filter']) ? trim($_GET['filter']) : ''; // Nuevo: Captura el filtro
    $page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
    $limit = 10; // Productos por página
    $offset = ($page - 1) * $limit;

    // 2. Construir condiciones WHERE dinámicas
    $conditions = [];
    $params = [];

    if (!empty($search_query)) {
        $conditions[] = "(p.nombre LIKE ? OR p.sku LIKE ? OR p.descripcion_corta LIKE ?)";
        $wildcard = "%$search_query%";
        $params[] = $wildcard;
        $params[] = $wildcard;
        $params[] = $wildcard;
    }

    // Nuevo: Lógica del filtro
    if ($filter_type === 'temporada') {
        $conditions[] = "p.es_temporada = 1";
    } elseif ($filter_type === 'mejores') {
        $conditions[] = "p.es_mejor = 1";
    }

    $where_clause = "";
    if (!empty($conditions)) {
        $where_clause = " WHERE " . implode(" AND ", $conditions);
    }

    // 3. Contar total de resultados (para paginación)
    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM productos p $where_clause");
    $stmt_count->execute($params);
    $total_products = $stmt_count->fetchColumn();
    $total_pages = ceil($total_products / $limit);

    // 4. Consulta principal con LIMIT y OFFSET
    $sql_products = "SELECT 
            p.*,
            c.nombre as nombre_catalogo,
            (SELECT imagen_url FROM producto_imagenes WHERE producto_id = p.id ORDER BY orden ASC LIMIT 1) as imagen_principal,
            p.es_temporada,
            p.es_mejor
        FROM productos p
        JOIN catalogos c ON p.catalogo_id = c.id
        $where_clause
        ORDER BY p.id DESC
        LIMIT $limit OFFSET $offset";

    $stmt_productos = $pdo->prepare($sql_products);
    $stmt_productos->execute($params);
    $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al consultar los productos: " . $e->getMessage());
}
//======================================================================
//======================================================================
// FIN DE LA LÓGICA DE LA PÁGINA
//======================================================================
$activeTab = $_GET['tab'] ?? 'catalogs'; // Default tab
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
    <h1 class="h2 mb-4">Gestión de Tienda y Perfil</h1>

    <ul class="nav nav-tabs mb-4" id="configTab" role="tablist">
        <li class="nav-item" role="presentation"><button
                class="nav-link <?php echo $activeTab === 'catalog' ? 'active' : ''; ?>" id="catalog-tab"
                data-bs-toggle="tab" data-bs-target="#catalog" type="button" role="tab" aria-controls="catalog"
                aria-selected="<?php echo $activeTab === 'catalog' ? 'true' : 'false'; ?>"><i
                    class="fas fa-list-alt me-2"></i>Listado de Productos</button></li>
        <li class="nav-item" role="presentation"><button
                class="nav-link <?php echo $activeTab === 'addProduct' ? 'active' : ''; ?>" id="add-product-tab"
                data-bs-toggle="tab" data-bs-target="#addProduct" type="button" role="tab" aria-controls="addProduct"
                aria-selected="<?php echo $activeTab === 'addProduct' ? 'true' : 'false'; ?>"><i
                    class="fas fa-plus me-2"></i>Nuevo Producto</button></li>
        <li class="nav-item" role="presentation"><button
                class="nav-link <?php echo $activeTab === 'catalogs' ? 'active' : ''; ?>" id="catalogs-tab"
                data-bs-toggle="tab" data-bs-target="#catalogs" type="button" role="tab" aria-controls="catalogs"
                aria-selected="<?php echo $activeTab === 'catalogs' ? 'true' : 'false'; ?>"><i
                    class="fas fa-tags me-2"></i>Catálogos</button></li>
        <li class="nav-item" role="presentation"><button
                class="nav-link <?php echo $activeTab === 'profile' ? 'active' : ''; ?>" id="profile-tab"
                data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                aria-selected="<?php echo $activeTab === 'profile' ? 'true' : 'false'; ?>"><i
                    class="fas fa-user-shield me-2"></i>Admin Perfil</button></li>
    </ul>

    <div class="tab-content" id="configTabContent">

        <div class="tab-pane fade <?php echo $activeTab === 'catalog' ? 'show active' : ''; ?>" id="catalog"
            role="tabpanel" aria-labelledby="catalog-tab">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Todos los Productos</h5>
                    <form class="d-flex" action="" method="GET">
                        <input type="hidden" name="page" value="configuracion">
                        <input type="hidden" name="tab" value="catalog">
                        
                        <select class="form-select me-2" name="filter" style="width: auto;">
                            <option value="">Todos</option>
                            <option value="temporada" <?php echo ($filter_type === 'temporada') ? 'selected' : ''; ?>>De Temporada</option>
                            <option value="mejores" <?php echo ($filter_type === 'mejores') ? 'selected' : ''; ?>>Mejores</option>
                        </select>

                        <input class="form-control me-2" type="search" name="q" placeholder="Buscar..." aria-label="Buscar" value="<?php echo htmlspecialchars($search_query); ?>">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                        <?php if (!empty($search_query) || !empty($filter_type)): ?>
                            <a href="?page=configuracion&tab=catalog" class="btn btn-outline-secondary ms-2" title="Limpiar"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>SKU</th>
                                    <th>Catálogo</th>
                                    <th>Precio</th>
                                    <th>Rating</th>
                                    <th>Temporada</th>
                                    <th>Mejores</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($productos)): ?>
                                        <tr>
                                            <td colspan="11" class="text-center">No hay productos para mostrar.</td>
                                        </tr>
                                <?php else: ?>
                                        <?php foreach ($productos as $producto): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($producto['id']); ?></td>
                                                    <td>
                                                        <?php if (!empty($producto['imagen_principal'])): ?>
                                                                <img src="/<?php echo ltrim(htmlspecialchars($producto['imagen_principal']), '/'); ?>"
                                                                    alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                        <?php else: ?>
                                                                <div
                                                                    style="width: 50px; height: 50px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($producto['sku']); ?></td>
                                                    <td><?php echo htmlspecialchars($producto['nombre_catalogo']); ?></td>
                                                    <td>$<?php echo htmlspecialchars(number_format($producto['precio_venta'], 2)); ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($producto['calificacion'])): ?>
                                                                <span
                                                                    class="text-warning"><?php echo str_repeat('★', $producto['calificacion']) . str_repeat('☆', 5 - $producto['calificacion']); ?></span>
                                                        <?php else: ?>
                                                                <span class="text-muted small">Sin calificar</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input toggle-feature" type="checkbox"
                                                                data-id="<?php echo $producto['id']; ?>" data-field="es_temporada"
                                                                <?php echo $producto['es_temporada'] ? 'checked' : ''; ?>>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input toggle-feature" type="checkbox"
                                                                data-id="<?php echo $producto['id']; ?>" data-field="es_mejor"
                                                                <?php echo $producto['es_mejor'] ? 'checked' : ''; ?>>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?php switch ($producto['estatus']) {
                                                            case 'activo':
                                                                echo 'bg-success';
                                                                break;
                                                            case 'inactivo':
                                                                echo 'bg-secondary';
                                                                break;
                                                            case 'borrador':
                                                                echo 'bg-warning text-dark';
                                                                break;
                                                        } ?>">
                                                            <?php echo ucfirst($producto['estatus']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary edit-product-btn"
                                                            data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                            data-producto='<?php echo htmlspecialchars(json_encode($producto), ENT_QUOTES, 'UTF-8'); ?>'
                                                            title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger delete-product-btn"
                                                            data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                                            data-id="<?php echo $producto['id']; ?>"
                                                            data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                                            title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="d-flex justify-content-center mt-3">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=configuracion&tab=catalog&q=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($filter_type); ?>&p=<?php echo $page - 1; ?>" aria-label="Anterior">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                    <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">&laquo;</span>
                                            </li>
                                    <?php endif; ?>

                                    <?php
                                    $start_page = max(1, $page - 2);
                                    $end_page = min($total_pages, $page + 2);

                                    if ($start_page > 1) {
                                        echo '<li class="page-item"><a class="page-link" href="?page=configuracion&tab=catalog&q=' . urlencode($search_query) . '&filter=' . urlencode($filter_type) . '&p=1">1</a></li>';
                                        if ($start_page > 2)
                                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }

                                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=configuracion&tab=catalog&q=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($filter_type); ?>&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                    <?php endfor; ?>
                                
                                    <?php
                                    if ($end_page < $total_pages) {
                                        if ($end_page < $total_pages - 1)
                                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                        echo '<li class="page-item"><a class="page-link" href="?page=configuracion&tab=catalog&q=' . urlencode($search_query) . '&filter=' . urlencode($filter_type) . '&p=' . $total_pages . '">' . $total_pages . '</a></li>';
                                    }
                                    ?>

                                    <?php if ($page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=configuracion&tab=catalog&q=<?php echo urlencode($search_query); ?>&filter=<?php echo urlencode($filter_type); ?>&p=<?php echo $page + 1; ?>" aria-label="Siguiente">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                    <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">&raquo;</span>
                                            </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggles = document.querySelectorAll('.toggle-feature');
                toggles.forEach(toggle => {
                    toggle.addEventListener('change', function () {
                        const productId = this.dataset.id;
                        const field = this.dataset.field;
                        const isChecked = this.checked;

                        fetch('../../back/product_manager.php?action=toggle_status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                field: field,
                                value: isChecked
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Actualizado correctamente');
                                } else {
                                    alert('Error al actualizar: ' + (data.error || 'Desconocido'));
                                    this.checked = !isChecked; // Revertir cambio visual
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error de conexión');
                                this.checked = !isChecked; // Revertir cambio visual
                            });
                    });
                });
            });
        </script>


<div class="tab-pane fade <?php echo $activeTab === 'addProduct' ? 'show active' : ''; ?>" id="addProduct"
    role="tabpanel" aria-labelledby="add-product-tab">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Detalles del Nuevo Producto</h5>
        </div>
        <div class="card-body">
            <form action="../../back/product_manager.php?action=create" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="active_tab" value="catalog"> <!-- Return to list after add -->
                <h6>Información Básica</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-8"><label for="nombre_prod" class="form-label">Nombre del
                            Producto</label><input type="text" class="form-control" id="nombre_prod" name="nombre"
                            required></div>
                    <div class="col-md-4"><label for="sku_prod" class="form-label">SKU</label><input type="text"
                            class="form-control" id="sku_prod" name="sku" required></div>
                    <div class="col-md-12"><label for="desc_corta_prod" class="form-label">Descripción
                            Corta</label><input type="text" class="form-control" id="desc_corta_prod"
                            name="descripcion_corta"></div>
                    <div class="col-12"><label for="desc_larga_prod" class="form-label">Descripción
                            Larga</label><textarea class="form-control" id="desc_larga_prod" name="descripcion_larga"
                            rows="4"></textarea></div>
                </div>
                <h6>Precios y Catálogo</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><label for="precio_compra_prod" class="form-label">Precio de Compra
                            ($)</label><input type="number" step="0.01" class="form-control" id="precio_compra_prod"
                            name="precio_compra"></div>
                    <div class="col-md-3"><label for="precio_venta_prod" class="form-label">Precio de Venta
                            ($)</label><input type="number" step="0.01" class="form-control" id="precio_venta_prod"
                            name="precio_venta" required></div>
                    <div class="col-md-3"><label for="precio_oferta_prod" class="form-label">Precio de Oferta
                            ($)</label><input type="number" step="0.01" class="form-control" id="precio_oferta_prod"
                            name="precio_oferta"></div>
                    <div class="col-md-3">
                        <label for="catalogo_id_prod" class="form-label">Catálogo</label>
                        <select id="catalogo_id_prod" name="catalogo_id" class="form-select" required>
                            <option value="" disabled selected>Elige un catálogo...</option>
                            <?php foreach ($catalogos_activos as $catalogo): ?>
                                    <option value="<?php echo $catalogo['id']; ?>">
                                        <?php echo htmlspecialchars($catalogo['nombre']); ?>
                                    </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <h6>Propiedades y Atributos</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><label for="origen_prod" class="form-label">Origen</label><input type="text"
                            class="form-control" id="origen_prod" name="origen" placeholder="Ej: Michoacán, México">
                    </div>
                    <div class="col-md-8 d-flex align-items-center pt-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox"
                                id="es_organico_prod" name="es_organico" value="1"><label class="form-check-label"
                                for="es_organico_prod">Orgánico</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox"
                                id="es_vegano_prod" name="es_vegano" value="1"><label class="form-check-label"
                                for="es_vegano_prod">Vegano</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox"
                                id="es_vegetariano_prod" name="es_vegetariano" value="1"><label class="form-check-label"
                                for="es_vegetariano_prod">Vegetariano</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox"
                                id="es_sin_gluten_prod" name="es_sin_gluten" value="1"><label class="form-check-label"
                                for="es_sin_gluten_prod">Sin Gluten</label></div>
                    </div>
                </div>
                <h6>Información Nutricional</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><label class="form-label">Porción</label><input type="text"
                            class="form-control" name="porcion_info" placeholder="Ej: 100g"></div>
                    <div class="col-md-3"><label class="form-label">Calorías</label><input type="number"
                            class="form-control" name="calorias"></div>
                    <div class="col-md-3"><label class="form-label">Proteínas (g)</label><input type="number"
                            step="0.01" class="form-control" name="proteinas_g"></div>
                    <div class="col-md-3"><label class="form-label">Carbohidratos (g)</label><input type="number"
                            step="0.01" class="form-control" name="carbohidratos_g"></div>
                    <div class="col-md-3"><label class="form-label">Grasas (g)</label><input type="number" step="0.01"
                            class="form-control" name="grasas_g"></div>
                    <div class="col-md-3"><label class="form-label">Azúcares (g)</label><input type="number" step="0.01"
                            class="form-control" name="azucares_g"></div>
                    <div class="col-md-3"><label class="form-label">Fibra (g)</label><input type="number" step="0.01"
                            class="form-control" name="fibra_g"></div>
                    <div class="col-md-3"><label class="form-label">Sodio (mg)</label><input type="number" step="0.01"
                            class="form-control" name="sodio_mg"></div>
                </div>
                <h6>Imágenes y Gestión</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6"><label for="imagenes_prod" class="form-label">Galería de Imágenes
                            (Ctrl+Click para varias)</label><input class="form-control" type="file" id="imagenes_prod"
                            name="imagenes[]" multiple accept="image/*"></div>
                    <div class="col-md-3">
                        <label for="calificacion_prod" class="form-label">Calificación (Admin)</label>
                        <select id="calificacion_prod" name="calificacion" class="form-select">
                            <option value="" selected>Sin calificar</option>
                            <option value="1">1 Estrella</option>
                            <option value="2">2 Estrellas</option>
                            <option value="3">3 Estrellas</option>
                            <option value="4">4 Estrellas</option>
                            <option value="5">5 Estrellas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="estatus_prod" class="form-label">Estatus</label>
                        <select id="estatus_prod" name="estatus" class="form-select">
                            <option value="borrador" selected>Borrador</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <hr class="my-4">
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar
                    Producto</button>
            </form>
        </div>
    </div>
</div>

<div class="tab-pane fade <?php echo $activeTab === 'catalogs' ? 'show active' : ''; ?>" id="catalogs" role="tabpanel"
    aria-labelledby="catalogs-tab">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Catálogos</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCatalogModal"><i
                    class="fas fa-plus me-1"></i> Nuevo Catálogo</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Imagen</th>
                            <th>Icono</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($todos_los_catalogos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No hay catálogos para mostrar. ¡Agrega uno
                                        nuevo!
                                    </td>
                                </tr>
                        <?php else: ?>
                                <?php foreach ($todos_los_catalogos as $catalogo): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($catalogo['id']); ?></td>
                                            <td>
                                                <?php if (!empty($catalogo['imagen_url'])): ?>
                                                        <img src="../../<?php echo ltrim(htmlspecialchars($catalogo['imagen_url']), '/'); ?>"
                                                            alt="<?php echo htmlspecialchars($catalogo['nombre']); ?>"
                                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                                <?php else: ?>
                                                        <div
                                                            style="width: 60px; height: 60px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($catalogo['icono_url'])): ?>
                                                        <img src="../../<?php echo ltrim(htmlspecialchars($catalogo['icono_url']), '/'); ?>"
                                                            alt="Icono"
                                                            style="width: 40px; height: 40px; object-fit: contain; background-color: #f5f5f5; border-radius: 50%;">
                                                <?php else: ?>
                                                        <div
                                                            style="width: 40px; height: 40px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                            <i class="fas fa-ban text-muted" style="font-size: 0.8rem;"></i>
                                                        </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($catalogo['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($catalogo['descripcion'], 0, 50)) . (strlen($catalogo['descripcion']) > 50 ? '...' : ''); ?>
                                            </td>
                                            <td><span
                                                    class="badge <?php echo $catalogo['estatus'] == 'activo' ? 'bg-success' : 'bg-danger'; ?>"><?php echo ucfirst($catalogo['estatus']); ?></span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary edit-catalog-btn" data-bs-toggle="modal"
                                                    data-bs-target="#editCatalogModal" data-id="<?php echo $catalogo['id']; ?>"
                                                    data-nombre="<?php echo htmlspecialchars($catalogo['nombre']); ?>"
                                                    data-descripcion="<?php echo htmlspecialchars($catalogo['descripcion']); ?>"
                                                    data-imagen-url="<?php echo htmlspecialchars($catalogo['imagen_url'] ?? ''); ?>"
                                                    data-icono-url="<?php echo htmlspecialchars($catalogo['icono_url'] ?? ''); ?>"
                                                    data-estatus="<?php echo $catalogo['estatus']; ?>" title="Editar"><i
                                                        class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger delete-catalog-btn" data-bs-toggle="modal"
                                                    data-bs-target="#deleteCatalogModal" data-id="<?php echo $catalogo['id']; ?>"
                                                    data-nombre="<?php echo htmlspecialchars($catalogo['nombre']); ?>"
                                                    title="Eliminar"><i class="fas fa-trash"></i></button>
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

<div class="tab-pane fade <?php echo $activeTab === 'profile' ? 'show active' : ''; ?>" id="profile" role="tabpanel"
    aria-labelledby="profile-tab">
    <p>Aquí irá el formulario para que el administrador edite su perfil.</p>
</div>
</div>
</div>

<div class="modal fade" id="addCatalogModal" tabindex="-1" aria-labelledby="addCatalogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCatalogModalLabel">Agregar Nuevo Catálogo</h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../back/catalog_manager.php?action=create" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="active_tab" value="catalogs">
                <div class="modal-body">
                    <div class="mb-3"><label for="nombre_cat_add" class="form-label">Nombre del Catálogo:</label><input
                            type="text" class="form-control" id="nombre_cat_add" name="nombre" required></div>
                    <div class="mb-3"><label for="descripcion_cat_add" class="form-label">Descripción:</label><textarea
                            class="form-control" id="descripcion_cat_add" name="descripcion" rows="3"></textarea></div>
                    <div class="mb-3"><label for="imagen_cat_add" class="form-label">Imagen (Opcional):</label><input
                            type="file" class="form-control" id="imagen_cat_add" name="imagen" accept="image/*"></div>
                    <div class="mb-3"><label for="icono_cat_add" class="form-label">Icono Móvil
                            (Opcional):</label><input type="file" class="form-control" id="icono_cat_add" name="icono"
                            accept="image/*"></div>
                    <div class="mb-3"><label for="estatus_cat_add" class="form-label">Estatus:</label><select
                            class="form-select" id="estatus_cat_add" name="estatus">
                            <option value="activo" selected>Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar
                        Catálogo</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editCatalogModal" tabindex="-1" aria-labelledby="editCatalogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCatalogModalLabel">Editar Catálogo</h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../back/catalog_manager.php?action=update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="active_tab" value="catalogs">
                <div class="modal-body">
                    <input type="hidden" id="edit_catalog_id" name="catalog_id">
                    <div class="mb-3"><label for="edit_nombre_cat" class="form-label">Nombre del Catálogo:</label><input
                            type="text" class="form-control" id="edit_nombre_cat" name="nombre" required></div>
                    <div class="mb-3"><label for="edit_descripcion_cat" class="form-label">Descripción:</label><textarea
                            class="form-control" id="edit_descripcion_cat" name="descripcion" rows="3"></textarea></div>
                    <div class="mb-3"><label for="edit_imagen_cat" class="form-label">Cambiar Imagen
                            (Opcional):</label>
                        <div id="current_image_preview" class="mb-2"></div>
                        <input type="file" class="form-control" id="edit_imagen_cat" name="imagen" accept="image/*">
                    </div>
                    <div class="mb-3"><label for="edit_icono_cat" class="form-label">Cambiar Icono Móvil
                            (Opcional):</label>
                        <div id="current_icon_preview" class="mb-2"></div>
                        <input type="file" class="form-control" id="edit_icono_cat" name="icono" accept="image/*">
                    </div>
                    <div class="mb-3"><label for="edit_estatus_cat" class="form-label">Estatus:</label><select
                            class="form-select" id="edit_estatus_cat" name="estatus">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button><button type="submit"
                        class="btn btn-warning">Actualizar Cambios</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteCatalogModal" tabindex="-1" aria-labelledby="deleteCatalogModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCatalogModalLabel">Confirmar Eliminación</h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el catálogo "<strong id="delete_catalog_name"></strong>"?</p>
                <p class="text-danger small">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <form action="../../back/catalog_manager.php?action=delete" method="POST">
                    <input type="hidden" name="active_tab" value="catalogs">
                    <input type="hidden" id="delete_catalog_id" name="catalog_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button><button
                        type="submit" class="btn btn-danger">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <form id="editProductForm" action="../../back/product_manager.php?action=update" method="POST"
            enctype="multipart/form-data">
            <!-- active_tab hidden input will be injected via JS or captured from template if included there, but best to be explicit here since this form is NOT the one in addProduct tab -->
            <input type="hidden" name="active_tab" value="catalog">
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

            </div>
        </form>
    </div>
</div>
</div>
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirmar Eliminación de Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el producto "<strong id="delete_product_name"></strong>"?</p>
                <p class="text-danger small">Esta acción es irreversible.</p>
            </div>
            <div class="modal-footer">
                <form action="../../back/product_manager.php?action=delete" method="POST">
                    <input type="hidden" name="active_tab" value="catalog">
                    <input type="hidden" id="delete_product_id" name="product_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- SCRIPTS PARA MODALES DE CATÁLOGOS ---
        const editCatalogModal = document.getElementById('editCatalogModal');
        if (editCatalogModal) {
            editCatalogModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');
                const descripcion = button.getAttribute('data-descripcion');
                const estatus = button.getAttribute('data-estatus');
                const imagenUrl = button.getAttribute('data-imagen-url');
                const iconoUrl = button.getAttribute('data-icono-url');

                const modalBody = editCatalogModal.querySelector('.modal-body');
                modalBody.querySelector('#edit_catalog_id').value = id;
                modalBody.querySelector('#edit_nombre_cat').value = nombre;
                modalBody.querySelector('#edit_descripcion_cat').value = descripcion;
                modalBody.querySelector('#edit_estatus_cat').value = estatus;

                // Preview Imagen
                const imgPreview = modalBody.querySelector('#current_image_preview');
                if (imagenUrl) {
                    imgPreview.innerHTML = '<small class="d-block mb-1">Actual:</small><img src="../../' + imagenUrl.replace(/^\//, '') + '" style="max-height: 100px; border-radius: 5px;">';
                } else {
                    imgPreview.innerHTML = '';
                }

                // Preview Icono
                const iconPreview = modalBody.querySelector('#current_icon_preview');
                if (iconoUrl) {
                    iconPreview.innerHTML = '<small class="d-block mb-1">Actual:</small><img src="../../' + iconoUrl.replace(/^\//, '') + '" style="max-height: 50px; background: #eee; border-radius: 50%; padding: 5px;">';
                } else {
                    iconPreview.innerHTML = '';
                }
            });
        }

        const deleteCatalogModal = document.getElementById('deleteCatalogModal');
        if (deleteCatalogModal) {
            deleteCatalogModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');
                deleteCatalogModal.querySelector('#delete_catalog_name').textContent = nombre;
                deleteCatalogModal.querySelector('#delete_catalog_id').value = id;
            });
        }

        // --- SCRIPTS PARA MODALES DE PRODUCTOS ---
        const editProductModal = document.getElementById('editProductModal');
        if (editProductModal) {
            const modalBody = editProductModal.querySelector('.modal-body');

            // Copiamos la plantilla del formulario de "Nuevo Producto"
            const formTemplate = document.querySelector('#addProduct form').innerHTML;

            editProductModal.addEventListener('show.bs.modal', function (event) {
                // Inyectamos la plantilla en el cuerpo del modal cada vez que se abre
                modalBody.innerHTML = '<input type="hidden" name="product_id">' + formTemplate;

                const button = event.relatedTarget;
                const producto = JSON.parse(button.getAttribute('data-producto'));

                // Llenar todos los campos del formulario inyectado
                const form = modalBody; // Ahora modalBody es el formulario
                form.querySelector('[name="product_id"]').value = producto.id;
                form.querySelector('[name="nombre"]').value = producto.nombre || '';
                form.querySelector('[name="sku"]').value = producto.sku || '';
                form.querySelector('[name="descripcion_corta"]').value = producto.descripcion_corta || '';
                form.querySelector('[name="descripcion_larga"]').value = producto.descripcion_larga || '';
                form.querySelector('[name="precio_compra"]').value = producto.precio_compra || '';
                form.querySelector('[name="precio_venta"]').value = producto.precio_venta || '';
                form.querySelector('[name="precio_oferta"]').value = producto.precio_oferta || '';
                form.querySelector('[name="catalogo_id"]').value = producto.catalogo_id || '';
                form.querySelector('[name="origen"]').value = producto.origen || '';
                form.querySelector('[name="es_organico"]').checked = producto.es_organico == 1;
                form.querySelector('[name="es_vegano"]').checked = producto.es_vegano == 1;
                form.querySelector('[name="es_vegetariano"]').checked = producto.es_vegetariano == 1;
                form.querySelector('[name="es_sin_gluten"]').checked = producto.es_sin_gluten == 1;
                form.querySelector('[name="porcion_info"]').value = producto.porcion_info || '';
                form.querySelector('[name="calorias"]').value = producto.calorias || '';
                form.querySelector('[name="proteinas_g"]').value = producto.proteinas_g || '';
                form.querySelector('[name="carbohidratos_g"]').value = producto.carbohidratos_g || '';
                form.querySelector('[name="grasas_g"]').value = producto.grasas_g || '';
                form.querySelector('[name="azucares_g"]').value = producto.azucares_g || '';
                form.querySelector('[name="fibra_g"]').value = producto.fibra_g || '';
                form.querySelector('[name="sodio_mg"]').value = producto.sodio_mg || '';
                form.querySelector('[name="calificacion"]').value = producto.calificacion || '';
                form.querySelector('[name="estatus"]').value = producto.estatus || 'borrador';
            });
        }

        const deleteProductModal = document.getElementById('deleteProductModal');
        if (deleteProductModal) {
            deleteProductModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');

                deleteProductModal.querySelector('#delete_product_name').textContent = nombre;
                deleteProductModal.querySelector('#delete_product_id').value = id;
            });
        }
    });
</script>