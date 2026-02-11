<?php
session_start();

// Si ya está logueado, redirige a la tienda
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config/conexion.php';

    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($email) || empty($contrasena)) {
        $error = 'Por favor completa todos los campos';
    } elseif ($contrasena !== $confirmar) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error = 'Este email ya está registrado';
            } else {
                // Insertar nuevo usuario (columna 'contrasena')
                $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
                $stmt->execute([$nombre, $email, $contrasena_hash]);

                // Redirigir inmediatamente al login con indicador
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
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
                <style>
                    body { display:flex; justify-content:center; align-items:center; min-height:100vh; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); padding:20px; }
                    .registro-container{background:white;padding:40px;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.2);width:100%;max-width:400px}
                    .registro-container h1{text-align:center;color:#333;margin-bottom:30px}
                    .form-group{margin-bottom:20px}
                    .form-group label{display:block;margin-bottom:5px;color:#555;font-weight:500}
                    .form-group input{width:100%;padding:12px;border:1px solid #ddd;border-radius:5px;font-size:14px;box-sizing:border-box}
                    .btn-registro{width:100%;padding:12px;background:#667eea;color:white;border:none;border-radius:5px;font-size:16px;font-weight:bold;cursor:pointer}
                    .btn-registro:hover{background:#5568d3}
                    .error{color:#e74c3c;margin-top:10px;padding:10px;background:#fadbd8;border-radius:5px;text-align:center}
                    .login-link{text-align:center;margin-top:20px;color:#666}
                    .login-link a{color:#667eea;text-decoration:none;font-weight:bold}
                </style>
            </head>
            <body>
                <div class="registro-container">
                    <h1>🍳 KitchenApp</h1>
                    <h2 style="text-align:center;color:#666;font-size:18px;margin-bottom:20px">Crear Cuenta</h2>
                    <form method="POST">
                        <div class="form-group"><label for="nombre">Nombre Completo:</label><input type="text" id="nombre" name="nombre" required></div>
                        <div class="form-group"><label for="email">Email:</label><input type="email" id="email" name="email" required></div>
                        <div class="form-group"><label for="contrasena">Contraseña:</label><input type="password" id="contrasena" name="contrasena" required></div>
                        <div class="form-group"><label for="confirmar">Confirmar Contraseña:</label><input type="password" id="confirmar" name="confirmar" required></div>
                        <button type="submit" class="btn-registro">Crear Cuenta</button>
                        <?php if ($error): ?>
                            <div class="error"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </form>
                    <div class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></div>
                </div>
            </body>
            </html>
            padding: 20px;
        }

        .registro-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .registro-container h1 {
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

        .btn-registro {
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

        .btn-registro:hover {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <h1>🍳 KitchenApp</h1>
        <h2 style="text-align: center; color: #666; font-size: 18px; margin-bottom: 20px;">Crear Cuenta</h2>

        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>

            <div class="form-group">
                <label for="confirmar">Confirmar Contraseña:</label>
                <input type="password" id="confirmar" name="confirmar" required>
            </div>

            <button type="submit" class="btn-registro">Crear Cuenta</button>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </form>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>
<?php
session_start();

// Si ya está logueado, redirige a la tienda
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$exito = '';

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config/conexion.php';
    
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['contraseña'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';
    
    // Validaciones
    if (empty($nombre) || empty($email) || empty($contraseña)) {
        $error = 'Por favor completa todos los campos';
    } elseif ($contraseña !== $confirmar) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($contraseña) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error = 'Este email ya está registrado';
            } else {
                // Insertar nuevo usuario
                $contraseña_hash = password_hash($contrasena, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
                $stmt->execute([$nombre, $email, $contraseña_hash]);
                
                $exito = 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.';
                // Redirigir a login después de 2 segundos
                header('Refresh: 2; url=login.php');
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
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        <?php
session_start();

// Si ya está logueado, redirige a la tienda
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$exito = '';

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config/conexion.php';
    
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';
    
    // Validaciones
    if (empty($nombre) || empty($email) || empty($contrasena)) {
        $error = 'Por favor completa todos los campos';
    } elseif ($contrasena !== $confirmar) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error = 'Este email ya está registrado';
            } else {
                // Insertar nuevo usuario
                $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
                $stmt->execute([$nombre, $email, $contrasena_hash]);
                
                $exito = 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.';
                
                // Redirigir a login después de 2 segundos
                header('Refresh: 2; url=login.php');
            }
        } catch (PDOException $e) {
            $error = 'Error en el servidor: ' . $e->getMessage();
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
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

```
    .registro-container {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 400px;
    }
    
    .registro-container h1 {
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
    
    .btn-registro {
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
    
    .btn-registro:hover {
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
    
    .success {
        color: #27ae60;
        margin-top: 10px;
        padding: 10px;
        background: #d5f4e6;
        border-radius: 5px;
        text-align: center;
    }
    
    .login-link {
        text-align: center;
        margin-top: 20px;
        color: #666;
    }
    
    .login-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: bold;
    }
</style>
```

</head>
<body>
    <div class="registro-container">
        <h1>🍳 KitchenApp</h1>
        <h2 style="text-align: center; color: #666; font-size: 18px; margin-bottom: 20px;">Crear Cuenta</h2>

```
    <form method="POST">
        <div class="form-group">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        
        <div class="form-group">
            <label for="confirmar">Confirmar Contraseña:</label>
            <input type="password" id="confirmar" name="confirmar" required>
        </div>
        
        <button type="submit" class="btn-registro">Crear Cuenta</button>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($exito): ?>
            <div class="success"><?= htmlspecialchars($exito) ?></div>
        <?php endif; ?>
    </form>
    
    <div class="login-link">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
    </div>
</div>
```

</body>
</html>
