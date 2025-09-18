<?php
// --- Lógica de Ejemplo para Simular Datos de Ventas ---

// KPIs (Key Performance Indicators)
$ventasHoy = 1850.50;
$ventasMes = 45678.00;
$totalPedidosMes = 152;
$ticketPromedio = ($totalPedidosMes > 0) ? $ventasMes / $totalPedidosMes : 0;

// Datos para la tabla de pedidos recientes
$pedidos = [
    ['id' => 87954, 'cliente' => 'Carlos Mendoza', 'fecha' => '2025-09-18', 'total' => 1250.00, 'estado' => 'Completado'],
    ['id' => 87953, 'cliente' => 'Ana Torres', 'fecha' => '2025-09-18', 'total' => 850.50, 'estado' => 'En Proceso'],
    ['id' => 87952, 'cliente' => 'Luis Fernández', 'fecha' => '2025-09-17', 'total' => 2300.00, 'estado' => 'Completado'],
    ['id' => 87951, 'cliente' => 'Sofía Castillo', 'fecha' => '2025-09-17', 'total' => 450.00, 'estado' => 'Cancelado'],
    ['id' => 87950, 'cliente' => 'Jorge Ríos', 'fecha' => '2025-09-16', 'total' => 180.75, 'estado' => 'Completado'],
];

// Función para las insignias de estado
function getEstadoVentaBadge($estado) {
    switch ($estado) {
        case 'Completado': return '<span class="badge bg-success">Completado</span>';
        case 'En Proceso': return '<span class="badge bg-warning text-dark">En Proceso</span>';
        case 'Cancelado': return '<span class="badge bg-danger">Cancelado</span>';
        default: return '<span class="badge bg-secondary">Desconocido</span>';
    }
}
?>

<div class="container-fluid">
    <h1 class="h2 mb-4">Análisis de Ventas</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted"><i class="fas fa-calendar-day me-2"></i>Ventas de Hoy</h5>
                    <p class="display-5 fw-bold">$<?php echo number_format($ventasHoy, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted"><i class="fas fa-calendar-alt me-2"></i>Ventas del Mes</h5>
                    <p class="display-5 fw-bold">$<?php echo number_format($ventasMes, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted"><i class="fas fa-receipt me-2"></i>Ticket Promedio (Mes)</h5>
                    <p class="display-5 fw-bold">$<?php echo number_format($ticketPromedio, 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rendimiento de Ventas</h5>
            <div class="d-flex">
                <input type="date" class="form-control me-2" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <div class="card-body">
            <p class="text-center text-muted"><em>[Espacio reservado para la gráfica de ventas]</em></p>
            <div style="height: 250px; background-color: #f0f0f0; border-radius: 5px;"></div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Historial de Pedidos</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?php echo $pedido['id']; ?></td>
                            <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                            <td class="text-end fw-bold">$<?php echo number_format($pedido['total'], 2); ?></td>
                            <td class="text-center"><?php echo getEstadoVentaBadge($pedido['estado']); ?></td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>