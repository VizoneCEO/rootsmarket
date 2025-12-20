<?php
session_start();
require_once __DIR__ . '/conection/db.php';

header('Content-Type: application/json');

// 1. Verificar sesión de administrador
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

// 2. Leer entrada JSON
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$product_id = $input['product_id'] ?? null;
$cantidad = $input['cantidad'] ?? 0;

if (!$product_id || $cantidad <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Obtener stock actual (opcional, para validaciones extra)
    // $stmt = $pdo->prepare("SELECT stock FROM productos WHERE id = ?"); ...

    if ($action === 'ingreso') {
        $sql = "UPDATE productos SET stock = stock + ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cantidad, $product_id]);
    } elseif ($action === 'merma') {
        $sql = "UPDATE productos SET stock = GREATEST(0, stock - ?) WHERE id = ?"; // Evitar stock negativo
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cantidad, $product_id]);
    } else {
        throw new Exception("Acción no válida");
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>