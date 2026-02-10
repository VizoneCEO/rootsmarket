<?php
header('Content-Type: application/json');
require_once 'conection/db.php';

$action = $_POST['action'] ?? '';

if ($action === 'get_orders_admin') {
    try {
        $status = $_POST['status_envio'] ?? ''; // 'en_preparacion' or 'enviado'

        $sql = "SELECT p.*, u.nombre as nombre_cliente, u.apellido_paterno, u.apellido_materno,
                       r.nombre as nombre_repartidor, r.apellido_paterno as apellido_repartidor
                FROM pedidos p 
                JOIN usuarios u ON p.user_id = u.id 
                LEFT JOIN usuarios r ON p.repartidor_id = r.id
                WHERE 1=1";

        $params = [];
        if (!empty($status)) {
            $sql .= " AND p.estatus_envio = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY p.fecha DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch details for each order
        $stmtDetails = $pdo->prepare("SELECT * FROM detalle_pedidos WHERE pedido_id = ?");

        foreach ($orders as &$order) {
            $stmtDetails->execute([$order['id']]);
            $order['detalles'] = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['status' => 'success', 'data' => $orders]);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error fetching orders: ' . $e->getMessage()]);
    }

} elseif ($action === 'update_order_status') {
    try {
        $orderId = $_POST['order_id'];
        $newStatus = $_POST['new_status']; // 'enviado' or 'entregado'

        if (empty($orderId) || empty($newStatus)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE pedidos SET estatus_envio = ? WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);

        echo json_encode(['status' => 'success']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error updating order: ' . $e->getMessage()]);
    }

} elseif ($action === 'get_drivers') {
    try {
        // Role ID 4 is for 'deliver'
        $stmt = $pdo->prepare("SELECT id, nombre, apellido_paterno, apellido_materno FROM usuarios WHERE rol_id = 4 AND estatus = 'activo'");
        $stmt->execute();
        $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $drivers]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error fetching drivers: ' . $e->getMessage()]);
    }

} elseif ($action === 'assign_driver') {
    try {
        $orderId = $_POST['order_id'];
        $driverId = $_POST['driver_id'];

        if (empty($orderId) || empty($driverId)) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros']);
            exit;
        }

        // Assign driver and set status to 'enviado'
        $stmt = $pdo->prepare("UPDATE pedidos SET repartidor_id = ?, estatus_envio = 'enviado' WHERE id = ?");
        $stmt->execute([$driverId, $orderId]);

        echo json_encode(['status' => 'success']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error assigning driver: ' . $e->getMessage()]);
    }

} elseif ($action === 'cancel_order') {
    try {
        $orderId = $_POST['order_id'];

        if (empty($orderId)) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE pedidos SET estatus = 'cancelado', estatus_envio = 'cancelado' WHERE id = ?");
        $stmt->execute([$orderId]);

        echo json_encode(['status' => 'success']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error cancelling order: ' . $e->getMessage()]);
    }



} elseif ($action === 'complete_delivery') {
    try {
        $orderId = $_POST['order_id'];
        $receivedBy = $_POST['received_by'];

        if (empty($orderId) || empty($receivedBy)) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
            exit;
        }

        $evidencePath = null;
        if (isset($_FILES['evidence_photo']) && $_FILES['evidence_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../front/uploads/evidence/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = 'evidence_' . $orderId . '_' . time() . '.' . pathinfo($_FILES['evidence_photo']['name'], PATHINFO_EXTENSION);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['evidence_photo']['tmp_name'], $targetPath)) {
                $evidencePath = $targetPath;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen a: ' . $targetPath . ' Error: ' . error_get_last()['message']]);
                exit;
            }
        }

        $stmt = $pdo->prepare("UPDATE pedidos SET estatus_envio = 'entregado', recibido_por = ?, evidencia_foto = ? WHERE id = ?");
        $stmt->execute([$receivedBy, $evidencePath, $orderId]);

        echo json_encode(['status' => 'success']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error completing delivery: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>