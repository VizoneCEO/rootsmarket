<?php
ob_start(); // Start output buffering immediately
ini_set('display_errors', 0); // Disable auto-printing errors
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/conection/db.php';

// Clean buffer before sending headers
ob_clean();
header('Content-Type: application/json');

// Handle Fatal Errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        // Only clean if we haven't sent headers yet (though we probably have)
        if (!headers_sent()) {
            // header('Content-Type: application/json'); // Try to ensure content type
        }
        // Force clean output for error
        while (ob_get_level())
            ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => 'Fatal Error: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']]);
        exit;
    }
});

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['administrador', 'contador'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'get_requests') {
    try {
        // Fetch orders where invoice is requested or already uploaded (to show history)
        // We prioritize those pending (solicita_factura=1 AND (invoice_pdf IS NULL OR invoice_xml IS NULL))
        // Or just show all where requests were made.

        $sql = "SELECT p.*, u.nombre as cliente_nombre, u.email as cliente_email, u.rfc, u.razon_social 
                FROM pedidos p
                JOIN usuarios u ON p.user_id = u.id
                WHERE p.solicita_factura = 1 OR (p.invoice_pdf IS NOT NULL AND p.invoice_xml IS NOT NULL)
                ORDER BY p.fecha DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $orders]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} elseif ($action === 'upload_invoice') {
    $orderId = $_POST['order_id'] ?? 0;

    if (!isset($_FILES['file_pdf']) || !isset($_FILES['file_xml'])) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan archivos (PDF y XML son requeridos)']);
        exit();
    }

    // Validate Order
    $stmt = $pdo->prepare("SELECT id FROM pedidos WHERE id = ?");
    $stmt->execute([$orderId]);
    if ($stmt->rowCount() == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Pedido no encontrado']);
        exit();
    }

    $uploadDir = __DIR__ . '/../invoices/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $pdfName = 'invoice_' . $orderId . '_' . time() . '.pdf';
    $xmlName = 'invoice_' . $orderId . '_' . time() . '.xml';

    $pdfPath = $uploadDir . $pdfName;
    $xmlPath = $uploadDir . $xmlName;

    // Validation
    $pdfType = mime_content_type($_FILES['file_pdf']['tmp_name']);
    // $xmlType = mime_content_type($_FILES['file_xml']['tmp_name']); // XML mime type varies

    if ($pdfType !== 'application/pdf') {
        echo json_encode(['status' => 'error', 'message' => 'El archivo PDF no es válido']);
        exit();
    }

    if (
        move_uploaded_file($_FILES['file_pdf']['tmp_name'], $pdfPath) &&
        move_uploaded_file($_FILES['file_xml']['tmp_name'], $xmlPath)
    ) {

        // Update DB
        // Save relative path for frontend access
        $relPdf = 'invoices/' . $pdfName;
        $relXml = 'invoices/' . $xmlName;

        try {
            $update = $pdo->prepare("UPDATE pedidos SET invoice_pdf = ?, invoice_xml = ? WHERE id = ?");
            $update->execute([$relPdf, $relXml, $orderId]);

            echo json_encode(['status' => 'success', 'message' => 'Factura subida correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error BD: ' . $e->getMessage()]);
        }

    } else {
        $errorInfo = '';
        if (!is_writable($uploadDir))
            $errorInfo .= " Directory not writable: $uploadDir";
        if (!is_dir($uploadDir))
            $errorInfo .= " Not a directory: $uploadDir";
        $uploadErrors = [
            $_FILES['file_pdf']['error'],
            $_FILES['file_xml']['error']
        ];
        echo json_encode(['status' => 'error', 'message' => 'Error al mover los archivos. Info: ' . $errorInfo . ' Upload Errors: ' . implode(',', $uploadErrors)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
