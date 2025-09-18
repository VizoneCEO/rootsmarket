<?php
// --- Lógica de Ejemplo para Simular Datos de la Base de Datos ---

// En un caso real, aquí harías tus consultas a la BD.
$productosSinStock = 3;
$proximosAVencer = 8;
$productosConStockBajo = 12;

$productos = [
    ['id' => 101, 'nombre' => 'Kiwi Orgánico (Kg)', 'stock' => 150, 'fecha_caducidad' => '2025-10-25'],
    ['id' => 205, 'nombre' => 'Queso de Cabra', 'stock' => 8, 'fecha_caducidad' => '2025-09-28'],
    ['id' => 310, 'nombre' => 'Salmón Ahumado', 'stock' => 0, 'fecha_caducidad' => '2025-09-22'],
    ['id' => 415, 'nombre' => 'Yogurt Griego Natural', 'stock' => 25, 'fecha_caducidad' => '2025-09-20'],
    ['id' => 112, 'nombre' => 'Aguacate Hass (Pieza)', 'stock' => 5, 'fecha_caducidad' => '2025-09-19'],
    ['id' => 501, 'nombre' => 'Lechuga Romana', 'stock' => 40, 'fecha_caducidad' => '2025-09-17'],
];

function getStatusBadge($stock, $fecha_caducidad) {
    $hoy = new DateTime();
    $fechaCad = new DateTime($fecha_caducidad);
    $diferencia = $hoy->diff($fechaCad)->days;
    $estaVencido = $hoy > $fechaCad;

    if ($estaVencido) return '<span class="badge bg-dark">Vencido</span>';
    if ($stock == 0) return '<span class="badge bg-danger">Sin Stock</span>';
    if ($diferencia <= 7 && !$estaVencido) return '<span class="badge bg-warning text-dark">Por Vencer</span>';
    if ($stock < 10 && $stock > 0) return '<span class="badge bg-info text-dark">Stock Bajo</span>';
    return '<span class="badge bg-success">En Stock</span>';
}
?>
<div class="container-fluid">
    <h1 class="h2 mb-4">Control de Inventario Inteligente</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-times-circle me-2"></i>Productos Sin Stock</h5>
                    <p class="display-4 fw-bold"><?php echo $productosSinStock; ?></p>
                    <p class="card-text">Acción inmediata requerida.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-dark bg-warning shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-exclamation-triangle me-2"></i>Próximos a Vencer (7 días)</h5>
                    <p class="display-4 fw-bold"><?php echo $proximosAVencer; ?></p>
                    <p class="card-text">Revisar para promociones o merma.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
        <div class="card-header bg-white"><h5 class="mb-0">Inventario General</h5></div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th style="width: 40%;">Nombre</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Caducidad</th>
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
                            <td class="text-center"><?php echo date("d/m/Y", strtotime($producto['fecha_caducidad'])); ?></td>
                            <td class="text-center"><?php echo getStatusBadge($producto['stock'], $producto['fecha_caducidad']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#ingresoModal" data-product-id="<?php echo $producto['id']; ?>" data-product-name="<?php echo htmlspecialchars($producto['nombre']); ?>" title="Ingresar Stock">
                                    <i class="fas fa-plus"></i> Ingreso
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#mermaModal" data-product-id="<?php echo $producto['id']; ?>" data-product-name="<?php echo htmlspecialchars($producto['nombre']); ?>" title="Registrar Merma">
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

<div class="modal fade" id="ingresoModal" tabindex="-1" aria-labelledby="ingresoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="ingresoModalLabel">Ingresar Stock</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <form><input type="hidden" id="ingresoProductId"><div class="mb-3"><label class="form-label">Producto</label><input type="text" id="ingresoProductName" class="form-control" readonly></div><div class="mb-3"><label for="ingresoCantidad" class="form-label">Cantidad a Ingresar</label><input type="number" class="form-control" id="ingresoCantidad" required></div><div class="mb-3"><label for="ingresoReferencia" class="form-label">Referencia / Lote (Opcional)</label><input type="text" class="form-control" id="ingresoReferencia" placeholder="Ej: Factura #12345"></div></form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-success"><i class="fas fa-save me-2"></i>Guardar Ingreso</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mermaModal" tabindex="-1" aria-labelledby="mermaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="mermaModalLabel">Registrar Merma</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <form><input type="hidden" id="mermaProductId"><div class="mb-3"><label class="form-label">Producto</label><input type="text" id="mermaProductName" class="form-control" readonly></div><div class="mb-3"><label for="mermaCantidad" class="form-label">Cantidad a Retirar</label><input type="number" class="form-control" id="mermaCantidad" required></div><div class="mb-3"><label for="mermaMotivo" class="form-label">Motivo</label><select class="form-select" id="mermaMotivo" required><option selected disabled value="">Elige un motivo...</option><option>Producto Caducado</option><option>Producto Dañado</option><option>Otro</option></select></div></form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Registrar Merma</button></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var ingresoModal = document.getElementById('ingresoModal');
    ingresoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var productId = button.getAttribute('data-product-id');
        var productName = button.getAttribute('data-product-name');
        ingresoModal.querySelector('.modal-title').textContent = 'Ingresar Stock de: ' + productName;
        ingresoModal.querySelector('#ingresoProductId').value = productId;
        ingresoModal.querySelector('#ingresoProductName').value = productName;
    });

    var mermaModal = document.getElementById('mermaModal');
    mermaModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var productId = button.getAttribute('data-product-id');
        var productName = button.getAttribute('data-product-name');
        mermaModal.querySelector('.modal-title').textContent = 'Registrar Merma de: ' + productName;
        mermaModal.querySelector('#mermaProductId').value = productId;
        mermaModal.querySelector('#mermaProductName').value = productName;
    });
});
</script>