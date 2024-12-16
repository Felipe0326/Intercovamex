<?php
include('db.php'); // Conexión a la base de datos

// Obtener el ID del usuario a consultar
$id = $_GET['id'];

// Consultar la información del usuario
$query = "SELECT Usuarios.NombreUsuario, Usuarios.Email, Usuarios.Nombre, Usuarios.Apellido, Usuarios.Telefono, Roles.NombreRol 
          FROM Usuarios 
          JOIN Roles ON Usuarios.RolId = Roles.Id 
          WHERE Usuarios.Id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/adminconsultar.css">
    <title>Consultar Usuario</title>
</head>
<body>
    <div class="info-container">
        <h2>Información del Usuario</h2>
        <p><strong>Nombre de Usuario:</strong> <?php echo $user['NombreUsuario']; ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo $user['Email']; ?></p>
        <p><strong>Nombre:</strong> <?php echo $user['Nombre']; ?></p>
        <p><strong>Apellido:</strong> <?php echo $user['Apellido']; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $user['Telefono']; ?></p>
        <p><strong>Rol:</strong> <?php echo $user['NombreRol']; ?></p>
        
        <a href="admin_usuarios.php" class="btn">Volver a la lista de usuarios</a>
    </div>
</body>
</html>

