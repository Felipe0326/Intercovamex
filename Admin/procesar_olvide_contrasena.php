<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Verificar si el correo existe
    $sql = "SELECT Id FROM Empleados WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $empleado = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($empleado) {
        $usuarioId = $empleado['Id'];
        $token = bin2hex(random_bytes(16));
        $fechaExpiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insertar el token en la tabla RecuperacionContrasena
        $sql = "INSERT INTO RecuperacionContrasena (UsuarioId, Token, FechaExpiracion) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iss', $usuarioId, $token, $fechaExpiracion);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Enviar el enlace de recuperación por correo
        $enlaceRecuperacion = "http://tu-dominio.com/restablecer_contrasena.php?token=$token";
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña: $enlaceRecuperacion";
        mail($email, "Recuperación de Contraseña", $mensaje, "From: intercovamexpruebas@gmail.com");

        echo "Se ha enviado un enlace de recuperación a tu correo.";
    } else {
        echo "No se encontró una cuenta con ese correo.";
    }
}
?>
