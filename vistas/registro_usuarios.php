<?php
session_start();
// 1. Filtro de seguridad: Si no hay sesión, al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// 2. Filtro estricto de Rol: Solo el ADMIN real puede agregar usuarios
if ($_SESSION['rol'] !== 'admin') {
    die("<div style='padding:3rem; text-align:center; font-family:sans-serif; background-color:#f8fafc; min-height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center;'>
            <h2 style='color:#ef4444; font-size:2rem; margin-bottom:1rem;'> Acceso Denegado</h2>
            <p style='color:#334155; font-size:1.1rem; margin-bottom:1.5rem;'>Solo los usuarios con rango de Administrador pueden registrar nuevos miembros del personal.</p>
            <a href='parking.php' style='background-color:#1e293b; color:white; padding:0.75rem 1.5rem; text-decoration:none; border-radius:6px; font-weight:bold;'>Volver al Parking</a>
         </div>");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - SOP Parking</title>
    <link rel="stylesheet" href="../ccs/estilos.css">
</head>
<body>
    <div id="menu-global"></div>

    <div class="container">
        <h1 class="page-title">👤 Registro de Nuevo Personal (Mecánicos)</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; max-width: 600px; margin-left: auto; margin-right: auto; text-align: center;">
                🎉 ¡Nuevo usuario registrado correctamente en el sistema!
            </div>
        <?php endif; ?>

        <div class="card-form">
            <form action="../php/procesar_registro.php" method="POST">
                <div class="form-group">
                    <label for="new_username">Nombre de Usuario (Login):</label>
                    <input type="text" id="new_username" name="new_username" required placeholder="Ej: marcos_mecanico">
                </div>

                <div class="form-group">
                    <label for="new_password">Contraseña Temporal:</label>
                    <input type="password" id="new_password" name="new_password" required placeholder="Ingresa una contraseña segura">
                </div>

                <div class="form-group">
                    <label for="rol">Rol en el Taller:</label>
                    <select name="rol" id="rol" required>
                        <option value="mecanico_b">Mecánico B (Operativo - No reparaciones mayores)</option>
                        <option value="mecanico_a">Mecánico A (Administrador de Taller)</option>
                        <option value="admin">Administrador General del Sistema</option>
                    </select>
                </div>

                <button type="submit" class="btn-login">Registrar Usuario</button>
            </form>
        </div>
    </div>

    <script src="../js/menu.js"></script>
</body>
</html>
