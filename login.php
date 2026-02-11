<?php
session_start();

// Si ya está logueado, redirige a la tienda
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config/conexion.php';
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'] ?? '';
    
    if (!empty($email) && !empty($contrasena)) {
        try {
            $stmt = $pdo->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Email o contraseña incorrectos';
            }
        } catch (PDOException $e) {
            $error = 'Error en el servidor';
        }
    } else {
        $error = 'Por favor completa todos los campos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Transacciones</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .login-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-login:hover {
            background: #5568d3;
        }
        
        .error {
            color: #e74c3c;
            margin-top: 10px;
            padding: 10px;
            background: #fadbd8;
            border-radius: 5px;
            text-align: center;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🍳 KitchenApp</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            
            <button type="submit" class="btn-login">Iniciar Sesión</button>
            
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </form>
        
        <div class="signup-link">
            ¿No tienes cuenta? <a href="registro_fixed.php">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>
