<?php
session_start();
require_once __DIR__ . '/conection/db.php';

// Check if user is logged in
// Check if user is logged in, EXCEPT for create_order
$action = $_POST['action'] ?? '';

if (!isset($_SESSION['user_id']) && $action !== 'create_order') {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
    exit();
}

$userId = $_SESSION['user_id'] ?? null;

if ($action === 'update_personal') {
    // Collect data
    $nombre = $_POST['nombre'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($nombre) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Nombre y correo son obligatorios']);
        exit();
    }

    try {
        // Prepare SQL
        $sql = "UPDATE usuarios SET 
                nombre = ?, 
                apellido_paterno = ?, 
                apellido_materno = ?, 
                telefono = ?, 
                direccion = ?, 
                ciudad = ?, 
                estado = ?, 
                email = ?";

        $params = [
            $nombre,
            $apellido_paterno,
            $apellido_materno,
            $telefono,
            $direccion,
            $ciudad,
            $estado,
            $email
        ];

        // Only update password if it's not empty and not the placeholder "******"
        if (!empty($password) && $password !== '******') {
            $sql .= ", password = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['status' => 'success', 'message' => 'Datos actualizados correctamente']);

    } catch (PDOException $e) {
        // Check for duplicate email error
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está en uso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
        }
    }
} elseif ($action === 'update_billing') {
    // Collect data
    $razon_social = $_POST['razon_social'] ?? '';
    $rfc = $_POST['rfc'] ?? '';
    $regimen_fiscal = $_POST['regimen_fiscal'] ?? '';
    $cp_fiscal = $_POST['cp_fiscal'] ?? '';
    $uso_cfdi = $_POST['uso_cfdi'] ?? '';

    try {
        $sql = "UPDATE usuarios SET 
                razon_social = ?, 
                rfc = ?, 
                regimen_fiscal = ?, 
                cp_fiscal = ?, 
                uso_cfdi = ?
                WHERE id = ?";

        $params = [
            $razon_social,
            $rfc,
            $regimen_fiscal,
            $cp_fiscal,
            $uso_cfdi,
            $userId
        ];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['status' => 'success', 'message' => 'Datos de facturación actualizados correctamente']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} elseif ($action === 'get_addresses') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM direcciones_envio WHERE user_id = ? ORDER BY es_principal DESC, id DESC");
        $stmt->execute([$userId]);
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $addresses]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'add_address') {
    $alias = $_POST['alias'] ?? 'Mi Dirección';
    $nombre_contacto = $_POST['nombre_contacto'] ?? '';
    $telefono_contacto = $_POST['telefono_contacto'] ?? '';
    $calle_numero = $_POST['calle_numero'] ?? '';
    $codigo_postal = $_POST['codigo_postal'] ?? '';
    $colonia = $_POST['colonia'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $es_principal = isset($_POST['es_principal']) && $_POST['es_principal'] === 'on' ? 1 : 0;

    if (empty($calle_numero) || empty($codigo_postal)) {
        echo json_encode(['status' => 'error', 'message' => 'Calle y Código Postal son obligatorios']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        if ($es_principal) {
            // Unset other defaults
            $stmt = $pdo->prepare("UPDATE direcciones_envio SET es_principal = 0 WHERE user_id = ?");
            $stmt->execute([$userId]);
        }

        // If it's the first address, make it default
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM direcciones_envio WHERE user_id = ?");
        $countStmt->execute([$userId]);
        if ($countStmt->fetchColumn() == 0) {
            $es_principal = 1;
        }

        $sql = "INSERT INTO direcciones_envio (user_id, alias, nombre_contacto, telefono_contacto, calle_numero, codigo_postal, colonia, ciudad, estado, es_principal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $alias, $nombre_contacto, $telefono_contacto, $calle_numero, $codigo_postal, $colonia, $ciudad, $estado, $es_principal]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Dirección agregada correctamente']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'delete_address') {
    $addressId = $_POST['address_id'] ?? 0;
    try {
        $stmt = $pdo->prepare("DELETE FROM direcciones_envio WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $userId]);
        echo json_encode(['status' => 'success', 'message' => 'Dirección eliminada']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'set_default_address') {
    $addressId = $_POST['address_id'] ?? 0;
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE direcciones_envio SET es_principal = 0 WHERE user_id = ?");
        $stmt->execute([$userId]);

        $stmt = $pdo->prepare("UPDATE direcciones_envio SET es_principal = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $userId]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Dirección predeterminada actualizada']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }


} elseif ($action === 'create_order') {

    // --- GUEST LOGIC ---
    if (!$userId) {
        $email = trim($_POST['contact_email'] ?? '');
        $full_name = trim($_POST['contact_nombre'] ?? 'Invitado');
        $telefono = trim($_POST['contact_telefono'] ?? '');

        if (empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'El correo es obligatorio para comprar']);
            exit;
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $userId = $existingUser['id'];
            // DO NOT login existing user automatically
        } else {
            // Create new user (Role 5 = Cliente)
            $tempPass = bin2hex(random_bytes(8));
            $hash = password_hash($tempPass, PASSWORD_DEFAULT);

            $parts = explode(' ', $full_name, 2);
            $firstName = $parts[0];
            $lastName = $parts[1] ?? '';

            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido_paterno, email, password, telefono, rol_id, fecha_registro, estatus) VALUES (?, ?, ?, ?, ?, 5, NOW(), 'activo')");
            $stmt->execute([$firstName, $lastName, $email, $hash, $telefono]);
            $userId = $pdo->lastInsertId();

            // Auto login new user
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_role'] = 'cliente';
            $_SESSION['user_name'] = $firstName;
        }
    }
    // --- END GUEST LOGIC ---

    $cart = json_decode($_POST['cart'] ?? '[]', true);
    $shipping = json_decode($_POST['shipping'] ?? '{}', true);
    $total = $_POST['total'] ?? 0;

    if (empty($cart)) {
        echo json_encode(['status' => 'error', 'message' => 'El carrito está vacío']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Determine Status and Payment Method
        $paymentMethod = $_POST['payment_method'] ?? 'card'; // Default to card if missing
        $status = ($paymentMethod === 'cash') ? 'pendiente_pago' : 'pagado';

        // Map frontend values to database values if needed (e.g., 'cash' -> 'Efectivo')
        $dbPaymentMethod = ($paymentMethod === 'cash') ? 'Efectivo' : 'Tarjeta';

        // 2. Create Order
        $stmt = $pdo->prepare("INSERT INTO pedidos (user_id, total, estatus, estatus_envio, direccion_envio, contacto_info, descuento_codigo, descuento_monto, fecha_entrega, hora_entrega, costo_envio, metodo_pago) VALUES (?, ?, ?, 'en_preparacion', ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $total,
            $status,
            json_encode($shipping),
            json_encode([
                'nombre' => $_POST['contact_nombre'] ?? '',
                'telefono' => $_POST['contact_telefono'] ?? '',
                'email' => $_POST['contact_email'] ?? ''
            ]),
            $_POST['discount_code'] ?? null,
            $_POST['discount_amount'] ?? 0.00,
            $_POST['delivery_date'] ?? null,
            $_POST['delivery_time'] ?? null,
            $_POST['shipping_cost'] ?? 0.00,
            $dbPaymentMethod
        ]);
        $orderId = $pdo->lastInsertId();

        // 2. Create Order Details
        $stmtDetail = $pdo->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, nombre_producto, cantidad, precio_unitario, imagen_url) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($cart as $item) {
            $stmtDetail->execute([
                $orderId,
                $item['id'] ?? null,
                $item['name'],
                $item['quantity'],
                $item['price'],
                $item['image'] ?? null
            ]);
        }

        // 3. Add Reusable Bags if applicable
        $bagQuantity = isset($_POST['bag_quantity']) ? intval($_POST['bag_quantity']) : 0;
        if ($bagQuantity > 0) {
            $bagPrice = 13.00;
            // Product ID 471 created for "Bolsa Reutilizable"
            $stmtDetail->execute([
                $orderId,
                471,
                'Bolsa Reutilizable',
                $bagQuantity,
                $bagPrice,
                'front/multimedia/productos.png' // Placeholder image
            ]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Pedido creado correctamente', 'order_id' => $orderId]);

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al crear pedido: ' . $e->getMessage()]);
    }

} elseif ($action === 'get_orders') {
    try {
        // Fetch orders
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE user_id = ? ORDER BY fecha DESC");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch details for each order (simple hydration)
        foreach ($orders as &$order) {
            $stmtDetails = $pdo->prepare("SELECT * FROM detalle_pedidos WHERE pedido_id = ?");
            $stmtDetails->execute([$order['id']]);
            $order['detalles'] = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['status' => 'success', 'data' => $orders]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'request_invoice') {
    $orderId = $_POST['order_id'] ?? 0;

    try {
        // Verify order belongs to user
        $stmt = $pdo->prepare("SELECT id FROM pedidos WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);

        if ($stmt->rowCount() > 0) {
            $update = $pdo->prepare("UPDATE pedidos SET solicita_factura = 1 WHERE id = ?");
            $update->execute([$orderId]);
            echo json_encode(['status' => 'success', 'message' => 'Solicitud de factura enviada']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Pedido no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'set_session_address') {
    $addressId = $_POST['address_id'] ?? 0;

    // Verify user owns this address to prevent IDOR
    try {
        $stmt = $pdo->prepare("SELECT id, alias, calle_numero FROM direcciones_envio WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $userId]);
        $address = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($address) {
            $_SESSION['selected_address_id'] = $address['id'];
            $_SESSION['selected_address_label'] = $address['alias'] ? $address['alias'] : $address['calle_numero'];
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Dirección no válida']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
