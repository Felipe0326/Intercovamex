<?php
include('db.php');
session_start();

$token = $_GET['token'];
$mensaje = "";

// Validar el token
$sql = "SELECT UsuarioId, FechaExpiracion FROM RecuperacionContrasena WHERE Token = ? AND Utilizado = 'No'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$registro = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($registro && strtotime($registro['FechaExpiracion']) > time()) {
    $usuarioId = $registro['UsuarioId'];

    // Procesar el formulario de nueva contraseña
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nuevaContrasena = password_hash($_POST['nueva_contrasena'], PASSWORD_DEFAULT);

        // Actualizar la contraseña
        $sql = "UPDATE Empleados SET Contrasena = ? WHERE Id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $nuevaContrasena, $usuarioId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Marcar el token como utilizado
        $sql = "UPDATE RecuperacionContrasena SET Utilizado = 'Sí' WHERE Token = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $token);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $mensaje = "Tu contraseña ha sido restablecida correctamente.";
    }
} else {
    $mensaje = "El enlace de recuperación es inválido o ha expirado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="cssLoginRegister.css">
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <?php if ($mensaje): ?>
            <p><?php echo $mensaje; ?></p>
        <?php else: ?>
            <form action="" method="POST">
                <label for="nueva_contrasena">Nueva Contraseña:</label>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
                <input type="submit" value="Restablecer Contraseña" class="btn">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
