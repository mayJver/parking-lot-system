<?php
session_start();
// Importamos la conexión
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entradas básicas
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (empty($user) || empty($pass)) {
        header("Location: ../index.php?error=" . urlencode("Todos los campos son obligatorios"));
        exit();
    }

    try {
        // Buscamos al usuario en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :username LIMIT 1" );
        $stmt->execute(['username' => $user]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // COMPARACIÓN DIRECTA (Texto plano)
            // Esto compara "12345" de tu formulario con el "12345" que guardaste en la base de datos.
            if ($pass === $usuario['password']) {
                
                // Guardamos los datos clave en la sesión
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['usuario']    = $usuario['username'];
                $_SESSION['rol']        = $usuario['rol'];

                // Login exitoso, nos vamos al dashboard
                header("Location: ../vistas/parking.php");
                exit();
            }
        }

        // Si falló el usuario o la contraseña
        header("Location: ../index.php?error=" . urlencode("Usuario o contraseña incorrectos"));
        exit();

    } catch (PDOException $e) {
        // En producción oculta el mensaje técnico, pero para desarrollo local te sirve ver el error:
        header("Location: ../index.php?error=" . urlencode("Error de conexión: " . $e->getMessage()));
        exit();
    }
} else {
    // Redirección si intentan entrar al archivo directo sin usar POST
    header("Location: ../index.php");
    exit();
}
