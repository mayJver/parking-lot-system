<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
require_once '../php/conexion.php';

// Jalamos los coches activos para el select del formulario
$stmt = $pdo->query("SELECT id_coche, placa, marca_modelo FROM coches WHERE estado = 'en_parking'");
$coches = $stmt->fetchAll();
?>
<!DOCTYPE html>
<!-- Pasamos el rol de la sesión directamente al atributo de HTML -->
<html lang="es" data-user-role="<?php echo $_SESSION['rol']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento - SOP Parking</title>
    <link rel="stylesheet" href="../ccs/estilos.css">
</head>
<body>
    <div id="menu-global"></div>

    <div class="container">
        <h1 class="page-title">🔧 Registro de Mantenimiento Preventivo / Correctivo</h1>

        <div class="card-form">
            <form action="../php/procesar_mantenimiento.php" method="POST">
                <div class="form-group">
                    <label for="id_coche">Seleccionar Vehículo:</label>
                    <select name="id_coche" id="id_coche" required>
                        <option value="">-- Selecciona un coche por placa --</option>
                        <?php foreach ($coches as $c): ?>
                            <option value="<?php echo $c['id_coche']; ?>">
                                <?php echo htmlspecialchars($c['placa'] . " - " . $c['marca_modelo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de Mantenimiento:</label>
                    <select name="tipo" id="tipo" required>
                        <option value="aceite">Cambio de Aceite</option>
                        <option value="llantas">Cambio de Llantas</option>
                        <option value="preventivo">Mantenimiento Preventivo</option>
                        <option value="otro">Otro Servicio / Reparación</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción del Trabajo:</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required placeholder="Detalla qué se le hizo al coche..."></textarea>
                </div>

                <button type="submit" class="btn-login">Registrar Mantenimiento</button>
            </form>
        </div>
    </div>

    <script src="../js/menu.js"></script>
</body>
</html>