<?php
session_start();
// Filtro de seguridad
if (!isset($_SESSION['usuario'])) {
    header("Location: ../vistas/login.php");
    exit();
}
?>
<!DOCTYPE html>
<!-- Pasamos el rol de la sesión directamente al atributo de HTML -->
<html lang="es" data-user-role="<?php echo $_SESSION['rol']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Altas y Bajas - SOP Parking</title>
    <link rel="stylesheet" href="../ccs/estilos.css">
</head>
<body>

    <div id="menu-global"></div>

    <div class="container">
        <h1 class="page-title">➕ Registrar Entrada de Vehículo (Alta)</h1>

        <div class="card-form">
            <form action="../php/procesar_altas.php" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="placa">Número de Placa:</label>
                    <input type="text" id="placa" name="placa" required placeholder="Ej: ABC-123-A" style="text-transform: uppercase;">
                </div>
                
                <div class="form-group">
                    <label for="marca_modelo">Marca y Modelo:</label>
                    <input type="text" id="marca_modelo" name="marca_modelo" required placeholder="Ej: Nissan Tsuru">
                </div>
                
                <div class="form-group">
                    <label for="foto">Foto del Vehículo:</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="nombre_vehiculo">Nombre del dueño:</label>
                    <input type="text" id="nombre_vehiculo" name="nombre_vehiculo" required placeholder="Ej: Juan Ramones">
                </div>

                <div class="form-group" style="display: flex; gap: 1rem;">
                    <div style="flex: 1;">
                        <label for="combustible">Combustible:</label>
                        <select name="combustible" id="combustible" required>
                            <option value="gasolina">Gasolina</option>
                            <option value="diesel">Diesel</option>
                            <option value="hibrido">Híbrido</option>
                            <option value="electrico">Eléctrico</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label for="ejes">Número de Ejes:</label>
                        <input type="number" id="ejes" name="ejes" min="2" max="10" value="2" required>
                    </div>
                </div>

                <div class="form-group" style="display: flex; gap: 1rem;">
                    <div style="flex: 1;">
                        <label for="anio">Año:</label>
                        <input type="number" id="anio" name="anio" min="1950" max="2027" placeholder="Ej: 2015" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="tipo_vehiculo">Tipo:</label>
                        <input type="text" id="tipo_vehiculo" name="tipo_vehiculo" placeholder="Ej: Sedán, SUV, Carga" required>
                    </div>
                </div>

                <button type="submit" class="btn-login" style="margin-top: 1rem;">Ingresar al Parking</button>
            </form>
        </div>
    </div>

    <script src="../js/menu.js"></script>
</body>
</html>