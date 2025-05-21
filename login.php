<?php
session_start();
include('db.php'); // Conexión a la BD

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar entradas para prevenir inyecciones SQL
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $contra = mysqli_real_escape_string($conn, $_POST['Contra']);

    // Consulta para verificar si el usuario existe
    $sqlUsuario = "SELECT * FROM usuarios WHERE Email = '$email'";
    $resultUsuario = mysqli_query($conn, $sqlUsuario);
    
    if ($resultUsuario && $usuario = mysqli_fetch_assoc($resultUsuario)) {
        // Usar contraseña en texto plano para la verificación
        if ($contra === $usuario['Contra']) {

            // Usar nombres de variables consistentes con auth.php
            $_SESSION['usuarioId'] = $usuario['ID']; 
            $_SESSION['rol'] = $usuario['Rol_ID'];
            session_write_close();

            // Dependiendo el tipo de usuario, ingresa a la vista correspondiente
            switch ($usuario['Rol_ID']) {
                case 1: // Administrador
                case 2: // Cobranza (empleado ICMX)
                    header("Location: /Portal_VFelipe/dashboard/frontend/dashboard.php");
                    exit();
                case 3: // Cliente
                    header("Location: /Portal_VFelipe/citasclientes/frontend/citas_clienteslogin.php");
                    exit();
                default:
                    header("Location: login.html");
                    exit();
            }
        } else {
            error_log("Contraseña incorrecta para el usuario $email");
        }
    } else {
        error_log("No se encontró el usuario $email");
    }
    // Si las credenciales no coinciden, redirigir al login.html con un mensaje de error
    header("Location: login.html?error=invalid");
    exit();
}
?>