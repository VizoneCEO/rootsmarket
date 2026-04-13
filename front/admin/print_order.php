<?php
require_once '../../back/conection/db.php';

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de pedido inválido.");
}

$order_id = (int) $_GET['id'];

try {
    // 1. Obtener datos del pedido
    $stmt = $pdo->prepare("
        SELECT p.*, 
               u.nombre as nombre_cliente, u.apellido_paterno, u.email, u.telefono,
               u.id as user_id
        FROM pedidos p
        JOIN usuarios u ON p.user_id = u.id
        WHERE p.id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Pedido no encontrado.");
    }

    // 2. Obtener detalles del pedido
    $stmt_det = $pdo->prepare("
        SELECT dp.*, pr.nombre as nombre_producto
        FROM detalle_pedidos dp
        JOIN productos pr ON dp.producto_id = pr.id
        WHERE dp.pedido_id = ?
    ");
    $stmt_det->execute([$order_id]);
    $items = $stmt_det->fetchAll(PDO::FETCH_ASSOC);

    // Parsear dirección
    $address = json_decode($order['direccion_envio'], true) ?? [];

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}

// Generar QR URL (apuntando al detalle del pedido o tracking)
// En un caso real, esto sería una URL pública de tracking. Por ahora usamos el ID.
$qr_data = "ROOTS-ORDER-" . $order_id . "-USER-" . $order['user_id'];
$qr_url = "https://quickchart.io/qr?text=" . urlencode($qr_data) . "&size=150";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pedido #
        <?php echo $order_id; ?>
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            /* Estilo ticket/recibo */
            background-color: #f9f9f9;
            padding: 20px;
        }

        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .logo-img {
            max-width: 150px;
            margin-bottom: 20px;
            /* Filtro verde Roots */
            filter: brightness(0) saturate(100%) invert(57%) sepia(78%) saturate(466%) hue-rotate(85deg) brightness(93%) contrast(95%);
        }

        .order-header {
            border-bottom: 2px dashed #ccc;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .table-items th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .qr-code {
            border: 1px solid #eee;
            padding: 5px;
        }

        @media print {
            body {
                background: white;
            }

            .ticket-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="fixed-top p-3 no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
        <button onclick="window.close()" class="btn btn-secondary"><i class="fas fa-times"></i> Cerrar</button>
    </div>

    <div class="ticket-container">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center order-header">
            <div>
                <img src="../multimedia/logo.svg" alt="Roots Market" class="logo-img">
                <br>
                <strong>Roots Market - Pasión por lo Natural</strong><br>
                <small>www.rootsmarket.com</small>
            </div>
            <div class="text-end">
                <h2 class="mb-0">Pedido #
                    <?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?>
                </h2>
                <div class="text-muted">
    <?php echo date('d/m/Y H:i', strtotime($order['fecha'])); ?>
</div>
<div class="text-muted">
    Entrega: <?php echo date('d/m/Y', strtotime($order['fecha_entrega'])); ?> <?php echo htmlspecialchars($order['hora_entrega']); ?>
</div>
            </div>
        </div>

        <!-- Payment Info Row -->
        <div class="row mb-4 border-bottom pb-3">
            <div class="col-6">
                <strong>Método de Pago:</strong>
                <span class="text-uppercase"><?php echo htmlspecialchars($order['metodo_pago'] ?? 'Tarjeta'); ?></span>
            </div>
            <div class="col-6 text-end">
                <strong>Estado:</strong>
                <span class="badge bg-dark text-white text-uppercase"
                    style="font-size: 0.9em;"><?php echo htmlspecialchars($order['estatus']); ?></span>
            </div>
        </div>

        <!-- Info Cliente & QR -->
        <div class="row mb-4">
            <div class="col-8">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <h5 class="fw-bold mb-2">Destinatario</h5>
                        <p class="mb-1 text-uppercase fw-bold">
                            <?php echo htmlspecialchars($order['nombre_cliente'] . ' ' . $order['apellido_paterno']); ?>
                        </p>
                        <p class="mb-1">
                            <?php echo htmlspecialchars($address['calle_numero'] ?? ''); ?><br>
                            <?php echo htmlspecialchars(($address['colonia'] ?? '') . ', ' . ($address['ciudad'] ?? '')); ?><br>
                            CP:
                            <?php echo htmlspecialchars($address['codigo_postal'] ?? ''); ?>
                        </p>
                        <p class="mb-0 small text-muted">Tel:
                            <?php echo htmlspecialchars($order['telefono']); ?>
                        </p>
                        <?php if (!empty($address['referencias'])): ?>
                            <div class="alert alert-light mt-2 py-1 px-2 border small">
                                <strong>Ref:</strong>
                                <?php echo htmlspecialchars($address['referencias']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="d-flex flex-column align-items-end">
                    <img src="<?php echo $qr_url; ?>" alt="QR Code" class="qr-code" width="120">
                    <small class="text-muted mt-1 text-center" style="font-size: 0.7rem; width: 120px;">
                        Escanea para seguimiento
                    </small>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="table table-borderless table-items mb-4">
            <thead>
                <tr class="text-uppercase small">
                    <th style="width: 50%;">Producto</th>
                    <th class="text-center">Cant</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div class="fw-bold">
                                <?php echo htmlspecialchars($item['nombre_producto']); ?>
                            </div>
                            <small class="text-muted">
                                ID: <?php echo $item['producto_id']; ?>
                            </small>
                        </td>
                        <td class="text-center">
                            <?php echo $item['cantidad']; ?>
                        </td>
                        <td class="text-end">$
                            <?php echo number_format($item['precio_unitario'], 2); ?>
                        </td>
                        <td class="text-end fw-bold">$
                            <?php echo number_format($item['cantidad'] * $item['precio_unitario'], 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot style="border-top: 2px solid #000;">
                <tr>
                    <td colspan="3" class="text-end pt-3">Subtotal:</td>
                    <td class="text-end pt-3">$
                        <?php echo number_format($order['total'], 2); ?>
                    </td>
                </tr>
                <tr class="fs-4 fw-bold">
                    <td colspan="3" class="text-end">TOTAL:</td>
                    <td class="text-end">$
                        <?php echo number_format($order['total'], 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="text-center mt-5 pt-4 border-top">
            <p class="mb-1 fw-bold">¡Gracias por tu compra!</p>
            <p class="small text-muted">¿Dudas con tu pedido? Contáctanos al 442-250-3383</p>
        </div>

    </div>
</body>

</html>