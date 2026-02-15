<?php
require_once __DIR__ . '/conection/db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

try {
    if ($action === 'dashboard_summary') {
        // 1. Total Users (exclude admins/staff if desired, but general count for now)
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $totalUsers = $stmt->fetch()['total'];

        // 2. Monthly Sales (Paid orders in current month)
        $stmt = $pdo->query("SELECT SUM(total) as total FROM pedidos 
                             WHERE estatus = 'pagado' 
                             AND MONTH(fecha) = MONTH(CURRENT_DATE()) 
                             AND YEAR(fecha) = YEAR(CURRENT_DATE())");
        $monthlySales = $stmt->fetch()['total'] ?? 0;

        // 3. Monthly Profit
        // Profit = (Unit Price - Buy Price) * Quantity
        // Filter by paid orders in current month
        $sqlProfitMonth = "SELECT SUM((dp.precio_unitario - pr.precio_compra) * dp.cantidad) as profit
                           FROM detalle_pedidos dp 
                           JOIN productos pr ON dp.producto_id = pr.id 
                           JOIN pedidos p ON dp.pedido_id = p.id 
                           WHERE p.estatus = 'pagado'
                           AND MONTH(p.fecha) = MONTH(CURRENT_DATE()) 
                           AND YEAR(p.fecha) = YEAR(CURRENT_DATE())";
        $stmt = $pdo->query($sqlProfitMonth);
        $monthlyProfit = $stmt->fetch()['profit'] ?? 0;

        // 4. Total Profit (All time)
        $sqlProfitTotal = "SELECT SUM((dp.precio_unitario - pr.precio_compra) * dp.cantidad) as profit
                           FROM detalle_pedidos dp 
                           JOIN productos pr ON dp.producto_id = pr.id 
                           JOIN pedidos p ON dp.pedido_id = p.id 
                           WHERE p.estatus = 'pagado'";
        $stmt = $pdo->query($sqlProfitTotal);
        $totalProfit = $stmt->fetch()['profit'] ?? 0;

        echo json_encode([
            'status' => 'success',
            'data' => [
                'users' => $totalUsers,
                'monthly_sales' => $monthlySales,
                'monthly_profit' => $monthlyProfit,
                'total_profit' => $totalProfit
            ]
        ]);

    } elseif ($action === 'recurrence') {
        // Customer Recurrence: User Name | Orders Count
        $sql = "SELECT u.nombre, u.apellido_paterno, COUNT(p.id) as total_pedidos
                FROM pedidos p
                JOIN usuarios u ON p.user_id = u.id
                GROUP BY p.user_id
                ORDER BY total_pedidos DESC
                LIMIT 10";
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);

    } elseif ($action === 'top_products') {
        // Best Selling: Product Name | Category | Volume | Revenue
        $sql = "SELECT pr.nombre, c.nombre as categoria, SUM(dp.cantidad) as total_vendido, SUM(dp.cantidad * dp.precio_unitario) as total_ingresos
                FROM detalle_pedidos dp
                JOIN productos pr ON dp.producto_id = pr.id
                LEFT JOIN catalogos c ON pr.catalogo_id = c.id
                GROUP BY dp.producto_id
                ORDER BY total_vendido DESC
                LIMIT 10";
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);

    } elseif ($action === 'zones') {
        // Purchase Zones: Colonia | Count
        // Fetch all addresses from pedidos table
        $sql = "SELECT direccion_envio FROM pedidos";
        $stmt = $pdo->query($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $zones = [];

        foreach ($orders as $order) {
            $json = $order['direccion_envio'];
            // Handle logical or decoding errors gracefully
            if (empty($json))
                continue;

            $addr = json_decode($json, true);
            if (!$addr)
                continue;

            // Normalize colonia name (trim, lowercase, title case)
            $colonia = isset($addr['colonia']) ? trim($addr['colonia']) : 'Desconocida';
            $colonia = mb_convert_case($colonia, MB_CASE_TITLE, "UTF-8");

            if (!isset($zones[$colonia])) {
                $zones[$colonia] = 0;
            }
            $zones[$colonia]++;
        }

        // Sort by count DESC
        arsort($zones);

        // Format for frontend
        $result = [];
        foreach ($zones as $name => $count) {
            $result[] = ['colonia' => $name, 'total' => $count];
            if (count($result) >= 10)
                break; // Limit to top 10
        }

        echo json_encode(['status' => 'success', 'data' => $result]);

    } elseif ($action === 'discount_stats') {
        // Breakdown: Code Name | Times Used | Total Amount
        $sql = "SELECT descuento_codigo, COUNT(id) as usos, SUM(descuento_monto) as total_monto 
                FROM pedidos 
                WHERE descuento_codigo IS NOT NULL AND descuento_codigo != ''
                GROUP BY descuento_codigo
                ORDER BY usos DESC";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $result]);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>