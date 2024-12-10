<?php
include('db.php'); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasena = $_POST['contrasena']; // Recuerda aplicar `password_hash()` aquí en producción.
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $rolId = $_POST['rolId'];

    $query = "INSERT INTO Usuarios (NombreUsuario, Contrasena, Email, Nombre, Apellido, Telefono, RolId) 
              VALUES ('$nombreUsuario', '$contrasena', '$email', '$nombre', '$apellido', '$telefono', '$rolId')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: admin_usuarios.php");
        exit();
    } else {
        echo "Error al crear el usuario: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admincrear.css">
    <title>Crear Usuario</title>
</head>
<body>
    <h2>Crear Usuario</h2>
    <form action="admin_crear_usuario.php" method="POST">
        <label for="nombreUsuario">Nombre de Usuario:</label>
        <input type="text" id="nombreUsuario" name="nombreUsuario" required><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono"><br>

        <label for="rolId">Rol:</label>
        <select id="rolId" name="rolId" required>
            <?php
            $roles = mysqli_query($conn, "SELECT Id, NombreRol FROM Roles WHERE NombreRol != 'Administrador'");
            while ($rol = mysqli_fetch_assoc($roles)) {
                echo "<option value='{$rol['Id']}'>{$rol['NombreRol']}</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Crear Usuario">
    </form>
</body>
</html>
