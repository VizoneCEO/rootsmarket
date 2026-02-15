<?php
// --- SEGURIDAD: VERIFICAR SESIÓN DE ADMIN ---
// Se especifica que managers también pueden ver stock si es necesario, pero por defecto admin.
$allowed_roles = ['administrador', 'manager'];
require_once dirname(__DIR__, 2) . '/back/check_admin_session.php';

// --- CONEXIÓN A LA BASE DE DATOS ---
// Ajustar ruta si es necesario, asumiendo estructura estándar
require_once dirname(__DIR__, 2) . '/back/conection/db.php';


// --- CONSULTA DE DATOS ---
try {
    // 1. Obtener Catálogos para el filtro
    $stmt_cats = $pdo->query("SELECT id, nombre FROM catalogos WHERE estatus = 'activo' ORDER BY nombre ASC");
    $catalogos = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);

    // 2. Preparar Filtros
    $search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
    $filter_type = isset($_GET['filter']) ? trim($_GET['filter']) : '';

    $conditions = [];
    $params = [];

    // Filtro de Búsqueda
    if (!empty($search_query)) {
        $conditions[] = "(nombre LIKE ? OR sku LIKE ?)";
        $params[] = "%$search_query%";
        $params[] = "%$search_query%";
    }

    // Filtro por Tipo/Categoría
    if ($filter_type === 'temporada') {
        $conditions[] = "es_temporada = 1";
    } elseif ($filter_type === 'mejores') {
        $conditions[] = "es_mejor = 1";
    } elseif ($filter_type === 'promocion') {
        $conditions[] = "es_promocion = 1";
    } elseif (strpos($filter_type, 'cat_') === 0) {
        $cat_id = (int) substr($filter_type, 4);
        $conditions[] = "catalogo_id = $cat_id";
    }

    $where_clause = "";
    if (!empty($conditions)) {
        $where_clause = " WHERE " . implode(" AND ", $conditions);
    }

    // 3. Obtener Productos Filtrados
    // Nota: Mantenemos el conteo global de KPIs separado o filtramos?
    // Generalmente los KPIs de "Sin Stock" deben ser globales para alertar, 
    // pero la tabla muestra lo filtrado.

    // KPIs Globales (Sin filtros)
    $stmt_kpi = $pdo->query("SELECT stock, stock_minimo FROM productos");
    $all_products = $stmt_kpi->fetchAll(PDO::FETCH_ASSOC);

    $productosSinStock = 0;
    $productosConStockBajo = 0;
    foreach ($all_products as $p) {
        if ($p['stock'] == 0)
            $productosSinStock++;
        elseif ($p['stock'] <= $p['stock_minimo'])
            $productosConStockBajo++;
    }

    // Productos para la Tabla (Filtrados)
    $sql_products = "SELECT id, nombre, stock, stock_minimo FROM productos $where_clause ORDER BY nombre ASC";
    $stmt = $pdo->prepare($sql_products);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error BD: " . $e->getMessage();
    exit;
}

function getStatusBadge($stock, $minStock)
{
    if ($stock == 0)
        return '<span class="badge bg-danger">Sin Stock</span>';
    if ($stock <= $minStock)
        return '<span class="badge bg-info text-dark">Stock Bajo</span>';
    return '<span class="badge bg-success">En Stock</span>';
}
?>
<div class="container-fluid">
    <h1 class="h2 mb-4">Control de Inventario</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-danger shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-times-circle me-2"></i>Productos Sin Stock</h5>
                    <p class="display-4 fw-bold"><?php echo $productosSinStock; ?></p>
                    <p class="card-text">Acción inmediata requerida.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-dark bg-info shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-arrow-down me-2"></i>Productos con Stock Bajo</h5>
                    <p class="display-4 fw-bold"><?php echo $productosConStockBajo; ?></p>
                    <p class="card-text">Considerar hacer nuevo pedido.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Inventario General</h5>
            <form class="d-flex" action="" method="GET">
                <input type="hidden" name="page" value="stock">

                <select class="form-select me-2" name="filter" style="width: auto;">
                    <option value="">Todos</option>
                    <option value="temporada" <?php echo ($filter_type === 'temporada') ? 'selected' : ''; ?>>De Temporada
                    </option>
                    <option value="mejores" <?php echo ($filter_type === 'mejores') ? 'selected' : ''; ?>>Mejores</option>
                    <option value="promocion" <?php echo ($filter_type === 'promocion') ? 'selected' : ''; ?>>En Promoción
                    </option>
                    <option disabled>--- Por Categoría ---</option>
                    <?php foreach ($catalogos as $cat): ?>
                        <option value="cat_<?php echo $cat['id']; ?>" <?php echo ($filter_type === 'cat_' . $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input class="form-control me-2" type="search" name="q" placeholder="Buscar..." aria-label="Buscar"
                    value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                <?php if (!empty($search_query) || !empty($filter_type)): ?>
                    <a href="?page=stock" class="btn btn-outline-secondary ms-2" title="Limpiar"><i
                            class="fas fa-times"></i></a>
                <?php endif; ?>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th style="width: 50%;">Nombre</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td class="text-center fw-bold"><?php echo $producto['stock']; ?></td>
                                <td class="text-center">
                                    <?php echo getStatusBadge($producto['stock'], $producto['stock_minimo']); ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#ingresoModal" data-product-id="<?php echo $producto['id']; ?>"
                                        data-product-name="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                        title="Ingresar Stock">
                                        <i class="fas fa-plus"></i> Ingreso
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#mermaModal" data-product-id="<?php echo $producto['id']; ?>"
                                        data-product-name="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                        title="Registrar Merma">
                                        <i class="fas fa-minus"></i> Merma
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ingreso -->
<div class="modal fade" id="ingresoModal" tabindex="-1" aria-labelledby="ingresoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ingresoModalLabel">Ingresar Stock</h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formIngreso">
                    <input type="hidden" id="ingresoProductId">
                    <div class="mb-3"><label class="form-label">Producto</label><input type="text"
                            id="ingresoProductName" class="form-control" readonly></div>
                    <div class="mb-3"><label for="ingresoCantidad" class="form-label">Cantidad a Ingresar</label><input
                            type="number" class="form-control" id="ingresoCantidad" min="1" required></div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-success"
                    onclick="guardarIngreso()"><i class="fas fa-save me-2"></i>Guardar Ingreso</button></div>
        </div>
    </div>
</div>

<!-- Modal Merma -->
<div class="modal fade" id="mermaModal" tabindex="-1" aria-labelledby="mermaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mermaModalLabel">Registrar Merma</h5><button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formMerma">
                    <input type="hidden" id="mermaProductId">
                    <div class="mb-3"><label class="form-label">Producto</label><input type="text" id="mermaProductName"
                            class="form-control" readonly></div>
                    <div class="mb-3"><label for="mermaCantidad" class="form-label">Cantidad a Retirar</label><input
                            type="number" class="form-control" id="mermaCantidad" min="1" required></div>
                    <div class="mb-3"><label for="mermaMotivo" class="form-label">Motivo</label><select
                            class="form-select" id="mermaMotivo" required>
                            <option selected disabled value="">Elige un motivo...</option>
                            <option>Producto Caducado</option>
                            <option>Producto Dañado</option>
                            <option>Otro</option>
                        </select></div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-danger"
                    onclick="guardarMerma()"><i class="fas fa-trash-alt me-2"></i>Registrar Merma</button></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ingresoModal = document.getElementById('ingresoModal');
        ingresoModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            ingresoModal.querySelector('#ingresoProductId').value = button.getAttribute('data-product-id');
            ingresoModal.querySelector('#ingresoProductName').value = button.getAttribute('data-product-name');
            ingresoModal.querySelector('#ingresoCantidad').value = '';
        });

        var mermaModal = document.getElementById('mermaModal');
        mermaModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            mermaModal.querySelector('#mermaProductId').value = button.getAttribute('data-product-id');
            mermaModal.querySelector('#mermaProductName').value = button.getAttribute('data-product-name');
            mermaModal.querySelector('#mermaCantidad').value = '';
        });
    });

    function guardarIngreso() {
        const id = document.getElementById('ingresoProductId').value;
        const cantidad = document.getElementById('ingresoCantidad').value;

        if (!id || !cantidad || cantidad <= 0) {
            alert("Por favor ingrese una cantidad válida");
            return;
        }

        fetch('../../back/stock_manager.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'ingreso', product_id: id, cantidad: cantidad })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Error: " + (data.error || "Desconocido"));
                }
            })
            .catch(err => alert("Error de red"));
    }

    function guardarMerma() {
        const id = document.getElementById('mermaProductId').value;
        const cantidad = document.getElementById('mermaCantidad').value;

        if (!id || !cantidad || cantidad <= 0) {
            alert("Por favor ingrese una cantidad válida");
            return;
        }

        fetch('../../back/stock_manager.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'merma', product_id: id, cantidad: cantidad })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Error: " + (data.error || "Desconocido"));
                }
            })
            .catch(err => alert("Error de red"));
    }
</script>