<?php
session_start();

// Reportar errores por si algo falla en el servidor
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>KitchenApp - Utensilios de Cocina</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <div class="header-left">
            <h1>Kitchenware</h1>
        </div>
        <div class="header-right">
            <span class="usuario-info">Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
            <div id="cart-icon">🛒 <span id="cart-count">0</span></div>
            <a href="api/logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </header>

    <main id="app">
        <section id="catalogo">
            <h2>Nuestros Utensilios</h2>
            <div id="lista-productos" class="grid"></div>
        </section>

        <section id="checkout" class="hidden">
            <h2>Resumen del Pedido</h2>
            <div id="detalle-checkout"></div>
            <div id="info-final">
                <p><strong>Fecha y Hora:</strong> <span id="fecha-actual"></span></p>
                <p><strong>Total a Pagar:</strong> $<span id="total-checkout"></span></p>
            </div>
            <button id="btn-solicitar" class="btn-primary">Solicitar pedido</button>
            <button onclick="toggleCheckout()" class="btn-secondary">Volver a la tienda</button>
        </section>
    </main>

    <div id="cart-modal" class="modal">
        <div class="modal-content">
            <h3>Tu Carrito</h3>
            <div id="items-carrito"></div>
            <p>Total: $<span id="total-carrito">0</span></p>
            <button id="btn-finalizar" class="btn-primary">Finalizar compra</button>
            <button onclick="closeModal()" class="btn-secondary">Cerrar</button>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>