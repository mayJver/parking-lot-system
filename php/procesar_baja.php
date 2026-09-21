<?php
session_start();
// Importamos la conexión
require_once 'conexion.php';

// Validamos que los datos lleguen por POST o mediante un parámetro ID en la URL
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['id'])) {
    
    // Obtenemos el ID del coche (ya sea por formulario POST o por enlace GET)
    $id_coche = isset($_POST['id_coche']) ? intval($_POST['id_coche']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);

    if ($id_coche <= 0) {
        header("Location: ../vistas/altas_bajas.php?error=" . urlencode("ID de coche no válido."));
        exit();
    }

    try {
        // 1. Verificar si el coche existe y si realmente está 'en_parking'
        $check = $pdo->prepare("SELECT estado FROM coches WHERE id_coche = :id LIMIT 1");
        $check->execute(['id' => $id_coche]);
        $coche = $check->fetch();

        if (!$coche) {
            header("Location: ../vistas/altas_bajas.php?error=" . urlencode("El coche no existe en el sistema."));
            exit();
        }

        if ($coche['estado'] === 'entregado') {
            header("Location: ../vistas/altas_bajas.php?error=" . urlencode("Este coche ya fue entregado anteriormente."));
            exit();
        }

        // 2. Realizar la baja lógica (actualizar el estado a 'entregado')
        $stmt = $pdo->prepare("UPDATE coches SET estado = 'entregado' WHERE id_coche = :id");
        $stmt->execute(['id' => $id_coche]);

        // Redireccionar con éxito
        header("Location: ../vistas/altas_bajas.php?mensaje=" . urlencode("Coche marcado como entregado (baja exitosa)"));
        exit();

    } catch (PDOException $e) {
        header("Location: ../vistas/altas_bajas.php?error=" . urlencode("Error en la base de datos: " . $e->getMessage()));
        exit();
    }

} else {
    header("Location: ../vistas/altas_bajas.php");
    exit();
}