<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e9ecef;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: 'Verdana', sans-serif;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .login-header {
            font-size: 1.5rem;
            font-weight: bold;
            color: #495057;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ced4da;
            height: 45px;
        }

        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 5px rgba(23, 162, 184, 0.5);
        }

        .btn-login {
            background-color: #17a2b8;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            padding: 10px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #138496;
        }

        .alert {
            margin-top: 15px;
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 10px;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">Bienvenido al Sistema</div>
        <p class="text-muted mb-4">Por favor, inicia sesión para continuar</p>

        <!-- Mostrar mensajes -->
        <?php 
        if (!empty($_SESSION['confirmacion'])): ?>
            <div class="alert alert-success" id="mensajeConfirmacion">
                <?php echo $_SESSION['confirmacion']; ?>
            </div>
            <?php unset($_SESSION['confirmacion']); ?>
        <?php endif; ?>

        <?php 
        if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger" id="mensajeError">
                <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="acceso.php" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Usuario" name="usuario" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" class="form-control" placeholder="Contraseña" name="clave" id="clave" required>
                <button type="button" class="toggle-btn" onclick="togglePassword()">👁</button>
            </div>
            <button type="submit" class="btn btn-login">Iniciar Sesión</button>
        </form>

        <p class="mt-3">
            <a href="cambiar_contraseña/recuperar.php">¿Olvidaste tu contraseña?</a>
        </p>

        <div class="footer">&copy; 2024 Nova</div>
    </div>

    <script>
        // Ocultar automáticamente el mensaje después de 5 segundos
        setTimeout(function() {
            var mensaje = document.getElementById('mensajeConfirmacion');
            if(mensaje) mensaje.style.display = 'none';

            var error = document.getElementById('mensajeError');
            if(error) error.style.display = 'none';
        }, 5000);

        // Mostrar/Ocultar contraseña
        function togglePassword() {
            const clave = document.getElementById("clave");
            if (clave.type === "password") {
                clave.type = "text";
            } else {
                clave.type = "password";
            }
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
