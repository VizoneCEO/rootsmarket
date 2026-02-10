<?php
session_start();
require_once __DIR__ . '/conection/db.php';

// Ensure admin or vendor (if allowed)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['administrador'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM codigos_descuento ORDER BY created_at DESC");
        $codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $codes]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'create') {
    $code = $_POST['code'] ?? '';
    $type = $_POST['type'] ?? 'porcentaje';
    $value = $_POST['value'] ?? 0;
    $expiration = !empty($_POST['expiration']) ? $_POST['expiration'] : null;

    if (empty($code) || empty($value)) {
        echo json_encode(['status' => 'error', 'message' => 'Código y valor son obligatorios']);
        exit;
    }

    try {
        $sql = "INSERT INTO codigos_descuento (codigo, tipo, valor, fecha_expiracion) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code, $type, $value, $expiration]);
        echo json_encode(['status' => 'success', 'message' => 'Código creado']);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'El código ya existe']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

} elseif ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $code = $_POST['code'] ?? '';
    $type = $_POST['type'] ?? 'porcentaje';
    $value = $_POST['value'] ?? 0;
    $expiration = !empty($_POST['expiration']) ? $_POST['expiration'] : null;

    try {
        $sql = "UPDATE codigos_descuento SET codigo = ?, tipo = ?, valor = ?, fecha_expiracion = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code, $type, $value, $expiration, $id]);
        echo json_encode(['status' => 'success', 'message' => 'Código actualizado']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'delete') {
    $id = $_POST['id'] ?? 0;
    try {
        $stmt = $pdo->prepare("DELETE FROM codigos_descuento WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'success', 'message' => 'Código eliminado']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'toggle_status') {
    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? 0; // 0 or 1
    try {
        $stmt = $pdo->prepare("UPDATE codigos_descuento SET activo = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        echo json_encode(['status' => 'success', 'message' => 'Estado actualizado']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>