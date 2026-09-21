<?php
session_start();
// 1. Filtro de seguridad: Si no hay sesión, al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// 2. Filtro de Rol: Mecánico B (operativo) no puede registrar reparaciones mayores
if ($_SESSION['rol'] === 'mecanico_b') {
    die("<div style='padding:3rem; text-align:center; font-family:sans-serif; background-color:#f8fafc; min-height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center;'>
            <h2 style='color:#ef4444; font-size:2rem; margin-bottom:1rem;'>❌ Acceso Denegado</h2>
            <p style='color:#334155; font-size:1.1rem; margin-bottom:1.5rem;'>Tu rango (Mecánico Operativo B) no tiene permisos para registrar reparaciones de motor o fallas mayores.</p>
            <a href='parking.php' style='background-color:#1e293b; color:white; padding:0.75rem 1.5rem; text-decoration:none; border-radius:6px; font-weight:bold;'>Volver al Parking</a>
         </div>");
}

// 3. Importamos la conexión para llenar el select de los coches
require_once '../php/conexion.php';

try {
    // Solo traemos los coches que están actualmente dentro
    $stmt = $pdo->query("SELECT id_coche, placa, marca_modelo FROM coches WHERE estado = 'en_parking'");
    $coches = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar los vehículos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<!-- Pasamos el rol de la sesión directamente al atributo de HTML -->
<html lang="es" data-user-role="<?php echo $_SESSION['rol']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reparaciones Mayores - SOP Parking</title>
    <link rel="stylesheet" href="../ccs/estilos.css">
</head>
<body>

    <div id="menu-global"></div>

    <div class="container">
        <h1 class="page-title">⚙️ Registro de Reparación Mayor</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; max-width: 600px; margin-left: auto; margin-right: auto; text-align: center;">
                🎉 ¡Reporte de reparación mayor registrado y guardado en el historial!
            </div>
        <?php endif; ?>

        <div class="card-form">
            <form action="../php/procesar_reparacion.php" method="POST">
                
                <div class="form-group">
                    <label for="id_coche">Vehículo Afectado:</label>
                    <select name="id_coche" id="id_coche" required>
                        <option value="">-- Selecciona el coche dañado --</option>
                        <?php foreach ($coches as $c): ?>
                            <option value="<?php echo $c['id_coche']; ?>">
                                <?php echo htmlspecialchars($c['placa'] . " - " . $c['marca_modelo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="falla">Componente o Falla Detectada:</label>
                    <input type="text" name="falla" id="falla" required placeholder="Ej: Bomba de gasolina, Clutch, Radiador">
                </div>

                <div class="form-group">
                    <label for="detalles">Detalles de la Reparación / Refacciones:</label>
                    <textarea name="detalles" id="detalles" rows="4" required placeholder="Describe las reparaciones técnicas que se le hicieron..."></textarea>
                </div>

                <button type="submit" class="btn-login" style="background-color: var(--color-principal);">Registrar Reparación Mayor</button>
            </form>
        </div>
    </div>

    <script src="../js/menu.js"></script>
</body>
</html>