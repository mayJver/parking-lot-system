<?php
session_start();
// Validar que la sesión exista y que el rol NO sea mecanico_b
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] === 'mecanico_b') {
    header("Location: ../vistas/login.php");
    exit();
}

// 2. Importar la conexión a la base de datos
require_once 'conexion.php';

// 3. Procesar los datos que vienen por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_coche = $_POST['id_coche'];
    $falla = trim($_POST['falla']);
    $detalles = trim($_POST['detalles']);
    
    // Extraemos el ID del mecánico logueado (que tiene permisos de Mecánico A o Admin)
    $id_usuario = $_SESSION['id_usuario'];

    // Validar que no nos manden campos vacíos
    if (empty($id_coche) || empty($falla) || empty($detalles)) {
        die("Error: Todos los campos del reporte de reparación son obligatorios.");
    }

    try {
        // 4. Insertar en la tabla 'reparaciones'
        // Usamos NOW() de SQL para registrar la fecha y hora exacta de la reparación
        $sql = "INSERT INTO reparaciones (id_coche, id_usuario, falla, detalles, fecha) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        
        // Ejecutamos pasando los 4 parámetros en orden
        $stmt->execute([$id_coche, $id_usuario, $falla, $detalles]);

        // 5. Redireccionar con éxito de vuelta al formulario de reparaciones
        header("Location: ../vistas/reparacion.php?status=success");
        exit();

    } catch (PDOException $e) {
        // Si sale un error de base de datos, te lo mostrará detallado en pantalla
        die("Error al registrar la reparación mayor: " . $e->getMessage());
    }
} else {
    // Si intentan entrar directo por URL sin enviar el formulario, los rebotamos
    header("Location: ../vistas/reparacion.php");
    exit();
}