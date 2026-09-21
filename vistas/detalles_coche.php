<?php
session_start();
// 1. Filtro de seguridad
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
require_once '../php/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Vehículo no especificado.");
}

$id_coche = intval($_GET['id']);

try {
    // 2. Obtener los datos del coche actual
    $stmt = $pdo->prepare("SELECT * FROM coches WHERE id_coche = ?");
    $stmt->execute([$id_coche]);
    $coche = $stmt->fetch();

    if (!$coche) {
        die("Error: El vehículo no existe.");
    }

    // ---  LÓGICA DE NAVEGACIÓN CON FLECHITAS ---
    // Buscamos el ID del coche anterior que esté actualmente en el parking
    $stmt_prev = $pdo->prepare("SELECT id_coche FROM coches WHERE id_coche < ? AND estado = 'en_parking' ORDER BY id_coche DESC LIMIT 1");
    $stmt_prev->execute([$id_coche]);
    $prev_coche = $stmt_prev->fetch();

    // Botón Derecho (Siguiente): Nos mueve hacia los coches más NUEVOS (IDs mayores)
    // En el Parking visual, el más nuevo está al principio (izquierda).
    $stmt_next = $pdo->prepare("SELECT id_coche FROM coches WHERE id_coche > ? AND estado = 'en_parking' ORDER BY id_coche ASC LIMIT 1");
    $stmt_next->execute([$id_coche]);
    $next_coche = $stmt_next->fetch();

    // 3. Obtener el historial de mantenimientos
    $stmt_mant = $pdo->prepare("
        SELECT m.*, u.username 
        FROM mantenimientos m 
        JOIN usuarios u ON m.id_usuario = u.id_usuario 
        WHERE m.id_coche = ? 
        ORDER BY m.fecha DESC
    ");
    $stmt_mant->execute([$id_coche]);
    $mantenimientos = $stmt_mant->fetchAll();

    // 4. Obtener el historial de reparaciones
    $stmt_rep = $pdo->prepare("
        SELECT r.*, u.username 
        FROM reparaciones r 
        JOIN usuarios u ON r.id_usuario = u.id_usuario 
        WHERE r.id_coche = ? 
        ORDER BY r.fecha DESC
    ");
    $stmt_rep->execute([$id_coche]);
    $reparaciones = $stmt_rep->fetchAll();

} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<!-- Pasamos el rol de la sesión directamente al atributo de HTML -->
<html lang="es" data-user-role="<?php echo $_SESSION['rol']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Técnica - <?php echo htmlspecialchars($coche['placa']); ?></title>
    <link rel="stylesheet" href="../ccs/estilos.css">
    <style>
        /* Estilos específicos para la barra de navegación superior */
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: #ffffff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .btn-nav {
            background-color: var(--color-principal, #1e293b);
            color: white;
            padding: 0.6rem 1.2rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: opacity 0.2s;
        }
        .btn-nav:hover {
            opacity: 0.9;
        }
        .btn-disabled {
            background-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            pointer-events: none;
        }
        /* Grid para ordenar los datos técnicos solicitados */
        .ficha-tecnica {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
            background-color: #f8fafc;
            padding: 1.25rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .ficha-item {
            display: flex;
            flex-direction: column;
        }
        .ficha-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }
        .ficha-valor {
            font-size: 1.1rem;
            color: #1e293b;
            font-weight: bold;
            margin-top: 0.2rem;
        }
    </style>
</head>
<body>
    <div id="menu-global"></div>

    <div class="container">
        
        <div class="nav-container">
            <?php if ($prev_coche): ?>
                <a href="detalles_coche.php?id=<?php echo $prev_coche['id_coche']; ?>" class="btn-nav">⬅️ Anterior</a>
            <?php else: ?>
                <span class="btn-nav btn-disabled">⬅️ Anterior</span>
            <?php endif; ?>


            <?php if ($next_coche): ?>
                <a href="detalles_coche.php?id=<?php echo $next_coche['id_coche']; ?>" class="btn-nav">Siguiente ➡️</a>
            <?php else: ?>
                <span class="btn-nav btn-disabled">Siguiente ➡️</span>
            <?php endif; ?>
        </div>

        <h1 class="page-title">📋 Hoja Clínica del Vehículo</h1>

        <div class="coche-card" style="flex-direction: column; max-width: 800px; margin: 0 auto 2.5rem auto; padding: 2rem;">
            <div style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: center; justify-content: center;">
                <?php $foto = !empty($coche['foto_ruta']) ? '../' . $coche['foto_ruta'] : '../img/default-car.png'; ?>
                <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto" style="width: 100%; max-width: 320px; height: 220px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                
                <div style="flex: 1; min-width: 280px;">
                    <span class="coche-placa" style="font-size: 1.4rem; padding: 0.4rem 1rem; letter-spacing: 1px;"><?php echo htmlspecialchars($coche['placa']); ?></span>
                    
                    <h2 style="margin-top: 1rem; color: var(--color-principal); font-size: 1.8rem;"><?php echo htmlspecialchars($coche['nombre_vehiculo']); ?></h2>
                    <p style="color: #64748b; font-size: 1rem; margin-top: 0.2rem;"><strong>Marca/Modelo:</strong> <?php echo htmlspecialchars($coche['marca_modelo']); ?></p>
                    
                    <div class="ficha-tecnica">
                        <div class="ficha-item">
                            <span class="ficha-label">⛽ Combustible</span>
                            <span class="ficha-valor" style="text-transform: capitalize;"><?php echo htmlspecialchars($coche['combustible']); ?></span>
                        </div>
                        <div class="ficha-item">
                            <span class="ficha-label">⚙️ Número de Ejes</span>
                            <span class="ficha-valor"><?php echo $coche['ejes']; ?> ejes</span>
                        </div>
                        <div class="ficha-item">
                            <span class="ficha-label">📅 Año</span>
                            <span class="ficha-valor"><?php echo $coche['anio']; ?></span>
                        </div>
                        <div class="ficha-item">
                            <span class="ficha-label">🏷️ Tipo</span>
                            <span class="ficha-valor" style="text-transform: capitalize;"><?php echo htmlspecialchars($coche['tipo_vehiculo']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2 style="margin-bottom: 1.2rem; color: var(--color-principal); border-bottom: 2px solid var(--borde); padding-bottom: 0.5rem;">🔧 Mantenimientos Preventivos y Correctivos</h2>
        <?php if (count($mantenimientos) > 0): ?>
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2.5rem;">
                <?php foreach ($mantenimientos as $m): ?>
                    <div style="background: white; padding: 1.25rem; border-radius: 8px; border-left: 5px solid var(--color-acento); box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <strong style="text-transform: uppercase; color: var(--color-acento);"><?php echo htmlspecialchars($m['tipo']); ?></strong>
                            <span style="font-size: 0.85rem; color: #64748b;"><?php echo $m['fecha']; ?></span>
                        </div>
                        <p style="color: var(--texto-oscuro);"><?php echo htmlspecialchars($m['descripcion']); ?></p>
                        <small style="color: #64748b; display: block; margin-top: 0.5rem;">Atendido por: <strong><?php echo htmlspecialchars($m['username']); ?></strong></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: #64748b; margin-bottom: 2.5rem;">Este vehículo no tiene registros de mantenimiento.</p>
        <?php endif; ?>

        <h2 style="margin-bottom: 1.2rem; color: var(--color-principal); border-bottom: 2px solid var(--borde); padding-bottom: 0.5rem;">⚙️ Reparaciones Mayores</h2>
        <?php if (count($reparaciones) > 0): ?>
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                <?php foreach ($reparaciones as $r): ?>
                    <div style="background: white; padding: 1.25rem; border-radius: 8px; border-left: 5px solid var(--color-peligro, #ef4444); box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <strong style="text-transform: uppercase; color: var(--color-peligro, #ef4444);">Falla: <?php echo htmlspecialchars($r['falla']); ?></strong>
                            <span style="font-size: 0.85rem; color: #64748b;"><?php echo $r['fecha']; ?></span>
                        </div>
                        <p style="color: var(--texto-oscuro);"><?php echo htmlspecialchars($r['detalles']); ?></p>
                        <small style="color: #64748b; display: block; margin-top: 0.5rem;">Reparado por: <strong><?php echo htmlspecialchars($r['username']); ?></strong></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: #64748b; margin-bottom: 2rem;">Este vehículo no tiene registros de reparaciones mayores.</p>
        <?php endif; ?>

    </div>

    <script src="../js/menu.js"></script>
</body>
</html>
