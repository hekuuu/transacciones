<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config/conexion.php';

    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if (empty($nombre) || empty($email) || empty($contrasena)) {
        $error = 'Por favor completa todos los campos';
    } elseif ($contrasena !== $confirmar) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error = 'Este email ya está registrado';
            } else {
                $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
                $stmt->execute([$nombre, $email, $contrasena_hash]);
                header('Location: login.php?registered=1');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Error en el servidor';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - KitchenApp</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="registro-container">
        <h1>🍳 KitchenApp</h1>
        <h2>Crear Cuenta</h2>

        <form method="POST">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <label for="confirmar">Confirmar Contraseña:</label>
            <input type="password" id="confirmar" name="confirmar" required>

            <button type="submit">Crear Cuenta</button>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </form>

        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
