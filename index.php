<?php
session_start();
// Si ya inició sesión, redirigir directo al parking
if (isset($_SESSION['usuario'])) {
    header("Location: /php/parking.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Parking</title>
    <link rel="stylesheet" href="../ccs/estilos.css">
</head>
<body class="login-body">
    <div class="login-container">
        <h2>Control de Estacionamiento</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <p class="error-msg" style="color: #ff4d4d; text-align: center;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </p>
        <?php endif; ?>

        <form action="/php/login_proceso.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required placeholder="Ingresa tu usuario">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
            </div>
            
            <button type="submit" class="btn-login">Ingresar</button>
        </form>
    </div>
</body>
</html>
