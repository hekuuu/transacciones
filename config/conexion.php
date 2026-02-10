<?php
$host = 'localhost';
$db   = 'tienda_cocina'; 
$user = 'root';
$pass = ''; 

try {
    // Conexión limpia a MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: No se pudo conectar a la base de datos." . $e->getMessage());
}
?>
/*hola*/