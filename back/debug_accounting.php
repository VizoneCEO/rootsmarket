<?php
require_once __DIR__ . '/conection/db.php';

echo "Checking order #2...\n";
$stmt = $pdo->query("SELECT user_id, solicita_factura FROM pedidos WHERE id = 2");
$order = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($order);

if ($order) {
    echo "Checking user ID " . $order['user_id'] . "...\n";
    $stmt = $pdo->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
    $stmt->execute([$order['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($user);

    if (!$user) {
        echo "CRITICAL: User not found! INNER JOIN will fail.\n";
    }
}

echo "\nRunning main query...\n";
$sql = "SELECT p.id, u.nombre 
        FROM pedidos p
        JOIN usuarios u ON p.user_id = u.id
        WHERE p.solicita_factura = 1";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($results);
