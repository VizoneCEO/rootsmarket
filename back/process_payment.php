<?php
session_start();
header('Content-Type: application/json');

// --- CONFIGURACIÓN ---
$CLIP_API_URL = 'https://api.payclip.com/payments';
// Token Basic proporcionado
$CLIP_AUTH_TOKEN = 'Basic OTcwZmNiNWMtNTFmYy00NWRjLWE3ZTktYjE2YmE1MDFlZjU1OmI4NzUyZmQ2LWJlOTYtNGJmNi04M2ExLTY0OTQ1ZTFjYjA3Yw==';

// --- VALIDACIONES PREVIAS ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    exit;
}

$token = $input['token'] ?? null;
$amount = $input['amount'] ?? 0;
$email = $input['email'] ?? 'cliente@rootsmarket.com';
$phone = $input['phone'] ?? '5555555555';
$reference = $input['reference'] ?? 'Orden Roots';

if (!$token || $amount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Token de tarjeta o monto inválido']);
    exit;
}

// --- ARMAR PAYLOAD PARA CLIP ---
$data = [
    "amount" => (float) $amount,
    "currency" => "MXN",
    "description" => $reference,
    "payment_method" => [
        "token" => $token
    ],
    // Aseguramos que customer vaya lleno, aunque sea con defaults si faltan datos
    "customer" => [
        "email" => $email,
        "phone" => $phone
    ]
];

// --- LLAMADA A LA API DE CLIP ---
$ch = curl_init($CLIP_API_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $CLIP_AUTH_TOKEN,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// --- PROCESAR RESPUESTA ---
if ($curlError) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión con Clip: ' . $curlError]);
    exit;
}

$result = json_decode($response, true);

// Verificar si la respuesta es exitosa (Clip devuelve status 'approved')
if ($httpCode === 200 || $httpCode === 201) {
    if (isset($result['status']) && $result['status'] === 'approved') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pago aprobado',
            'data' => $result
        ]);
    } else {
        // El pago pasó pero no fue aprobado (ej. rechazado por banco)
        $msg = 'Pago no aprobado';
        if (isset($result['status_detail']['message'])) {
            $msg .= ': ' . $result['status_detail']['message'];
        }
        echo json_encode(['status' => 'error', 'message' => $msg, 'details' => $result]);
    }
} else {
    // Error en la petición (400, 401, 500, etc.)
    $msg = 'Error al procesar pago';
    if (isset($result['message'])) {
        $msg .= ': ' . $result['message']; // Mensaje de error general de Clip
    }
    // A veces Clip manda errores detallados en 'details' o similar, pero usamos message primero
    echo json_encode(['status' => 'error', 'message' => $msg, 'code' => $httpCode]);
}
