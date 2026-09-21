<?php
session_start();
// 1. Validar que el usuario tenga sesión activa y que tengamos su ID
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario'])) {
    header("Location: ../vistas/login.php");
    exit();
}

// 2. Importar la conexión a la base de datos
require_once 'conexion.php';

// 3. Procesar los datos que vienen por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_coche = $_POST['id_coche'];
    $tipo = trim($_POST['tipo']);
    $descripcion = trim($_POST['descripcion']);
    
    // Extraemos el ID del usuario/mecánico que tiene la sesión iniciada
    $id_usuario = $_SESSION['id_usuario']; 
    
    // Validar que no nos manden campos vacíos
    if (empty($id_coche) || empty($tipo) || empty($descripcion)) {
        die("Error: Todos los campos son obligatorios.");
    }

    try {
        // 4. CORRECCIÓN: Agregamos 'id_usuario' al INSERT
        $sql = "INSERT INTO mantenimientos (id_coche, id_usuario, tipo, descripcion, fecha) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        
        // Pasamos las 4 variables en el orden correcto
        $stmt->execute([$id_coche, $id_usuario, $tipo, $descripcion]);

        // 5. Redireccionar con éxito
        header("Location: ../vistas/mantenimiento.php?status=success");
        exit();

    } catch (PDOException $e) {
        die("Error al registrar el mantenimiento: " . $e->getMessage());
    }
} else {
    header("Location: ../vistas/mantenimiento.php");
    exit();
}