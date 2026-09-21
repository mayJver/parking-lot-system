<?php
session_start();
// 1. Filtro de seguridad: Si no hay sesión, al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// 2. Importamos la conexión a la base de datos
require_once '../php/conexion.php';

try {
    // 3. Traemos solo los coches que están actualmente dentro del estacionamiento
    $stmt = $pdo->prepare("SELECT * FROM coches WHERE estado = 'en_parking' ORDER BY id_coche DESC");
    $stmt->execute();
    $coches = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar el parking: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<!-- Pasamos el rol de la sesión directamente al atributo de HTML -->
<html lang="es" data-user-role="<?php echo $_SESSION['rol']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Actual - Parking</title>
    <link rel="stylesheet" href="../ccs/estilos.css">
</head>
<body>

    <div id="menu-global"></div>

    <div class="container">
        <h1 class="page-title">🚗 Vehículos en el Estacionamiento</h1>
        
        <p style="margin-bottom: 1.5rem;">
            Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong> 
            (Rol: <span style="text-transform: uppercase; font-weight: bold; color: var(--color-acento);"><?php echo $_SESSION['rol']; ?></span>)
        </p>

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'alta_exitosa'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                ¡Vehículo registrado y guardado con éxito!
            </div>
        <?php endif; ?>

        <div class="parking-grid">
            <?php if (count($coches) > 0): ?>
                <?php foreach ($coches as $coche): ?>
                    <div class="coche-card">
                        <?php 
                        $foto = !empty($coche['foto_ruta']) ? '../' . $coche['foto_ruta'] : '../img/default-car.png'; 
                        ?>
                        <a href="detalles_coche.php?id=<?php echo $coche['id_coche']; ?>">
                            <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto coche" class="coche-img" style="cursor: pointer;">
                        </a>
                        
                        <div class="coche-info">
                            <div>
                                <span class="coche-placa"><?php echo htmlspecialchars($coche['placa']); ?></span>
                                <p class="coche-modelo"><?php echo htmlspecialchars($coche['marca_modelo']); ?></p>
                            </div>
                            
                            <div class="acciones" style="display: flex; flex-direction: column; gap: 8px;">
                                <a href="detalles_coche.php?id=<?php echo $coche['id_coche']; ?>" class="btn-action" style="background-color: var(--color-principal); color: white; text-decoration: none;">
                                    🔍 Ver Historial Clínico
                                </a>

                                <a href="../php/procesar_baja.php?id=<?php echo $coche['id_coche']; ?>" 
                                class="btn-action btn-baja" 
                                onclick="return confirm('¿Seguro que deseas dar de baja este vehículo?');">
                                Dar de Baja (Salida)
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--color-tarjeta); border-radius: 8px;">
                    <p style="font-size: 1.2rem; color: #64748b;">No hay vehículos dentro del parking en este momento.</p>
                    <a href="altas_bajas.php" style="display: inline-block; margin-top: 1rem; color: var(--color-acento); font-weight: bold;">+ Registrar primer coche</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="../js/menu.js"></script>
</body>
</html>
