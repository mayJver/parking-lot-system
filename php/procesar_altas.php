<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Recibir y sanitizar todos los datos de texto del formulario
    $placa = strtoupper(trim($_POST['placa'])); 
    $marca_modelo = trim($_POST['marca_modelo']);
    $nombre_vehiculo = trim($_POST['nombre_vehiculo']);
    $combustible = $_POST['combustible'];
    $ejes = intval($_POST['ejes']);
    $anio = intval($_POST['anio']);
    $tipo_vehiculo = trim($_POST['tipo_vehiculo']);
    
    // 2. Procesar la imagen primero
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
        $nombre_archivo = $_FILES['foto']['name'];
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        
        if (in_array($extension, $permitidos)) {
            // Creamos el nombre único para el archivo físico
            $nuevo_nombre = "coche_" . $placa . "_" . time() . "." . $extension;
            $ruta_destino = "../uploads/" . $nuevo_nombre; // Destino real en el disco
            
            // Movemos el archivo temporal a nuestra carpeta uploads
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
                
                // Ruta limpia que guardaremos en la BD para las vistas
                $ruta_db = "uploads/" . $nuevo_nombre;
                
                try {
                    // 3. AHORA SÍ: Un único INSERT con absolutamente todos los datos solicitados por el profe
                    $sql = "INSERT INTO coches (placa, marca_modelo, nombre_vehiculo, combustible, ejes, anio, tipo_vehiculo, foto_ruta, estado) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'en_parking')";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $placa, 
                        $marca_modelo, 
                        $nombre_vehiculo, 
                        $combustible, 
                        $ejes, 
                        $anio, 
                        $tipo_vehiculo, 
                        $ruta_db
                    ]);
                    
                    // Redirección con éxito al dashboard
                    header("Location: ../vistas/parking.php?mensaje=alta_exitosa");
                    exit();

                } catch (PDOException $e) {
                    die("Error al guardar en la base de datos: " . $e->getMessage());
                }

            } else {
                echo "Error al mover el archivo a la carpeta de destino. ¿Existe la carpeta uploads?";
            }
        } else {
            echo "Formato de imagen no permitido.";
        }
    } else {
        echo "Debes subir una foto del vehículo.";
    }
}
?>
