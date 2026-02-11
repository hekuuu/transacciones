<?php
session_start();
require_once '../config/conexion.php';

// Verificar que el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'mensaje' => 'No autorizado']);
    http_response_code(401);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Insertar el pedido (ahora con usuario_id)
    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
    $stmt->execute([$_SESSION['usuario_id'], $data['total']]);
    $pedidoId = $pdo->lastInsertId();

    // 2. Insertar detalles
    $stmtDetalle = $pdo->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
    foreach ($data['carrito'] as $item) {
        $stmtDetalle->execute([$pedidoId, $item['id'], $item['cantidad'], $item['precio']]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'mensaje' => 'Pedido finalizado y creado con éxito!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'mensaje' => $e->getMessage()]);
}
?>