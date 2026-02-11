<?php
session_start();
require_once '../config/conexion.php';

// Verificar que el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    http_response_code(401);
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($productos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>