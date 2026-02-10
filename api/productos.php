<?php
require_once '../config/conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($productos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>