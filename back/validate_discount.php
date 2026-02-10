<?php
require_once __DIR__ . '/conection/db.php';

$code = $_POST['code'] ?? '';

if (empty($code)) {
    echo json_encode(['status' => 'error', 'message' => 'Código vacío']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM codigos_descuento WHERE codigo = ? AND activo = 1 LIMIT 1");
    $stmt->execute([$code]);
    $discount = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($discount) {
        // Check expiration
        if ($discount['fecha_expiracion'] && strtotime($discount['fecha_expiracion']) < time()) {
            echo json_encode(['status' => 'error', 'message' => 'El código ha expirado']);
        } else {
            echo json_encode([
                'status' => 'success',
                'type' => $discount['tipo'],
                'value' => $discount['valor'],
                'code' => $discount['codigo']
            ]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Código no válido o inactivo']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de servidor']);
}
?>