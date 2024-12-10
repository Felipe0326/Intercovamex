<?php
include('db.php'); // Conexión a la base de datos

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM Usuarios WHERE Id = $id");
$user = mysqli_fetch_assoc($result);
$isAdministrador = ($user['RolId'] == 1); // Suponiendo que el ID del rol Administrador es 1

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasena = $_POST['contrasena'];
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    // Mantener la contraseña actual si el campo está vacío
    if (empty($contrasena)) {
        $contrasena = $user['Contrasena']; // Mantener la contraseña actual
    } else {
        // Aplica password_hash aquí si usas cifrado en producción
        $contrasena = $contrasena; // Sin cifrado para texto plano (no recomendado para producción)
    }

    // Solo actualizar el rol si el usuario no es Administrador
    if (!$isAdministrador) {
        $rolId = $_POST['rolId'];
    } else {
        $rolId = $user['RolId']; // Mantener el rol actual de Administrador
    }

    // Construir la consulta de actualización
    $query = "UPDATE Usuarios SET 
                NombreUsuario='$nombreUsuario', 
                Contrasena='$contrasena', 
                Email='$email', 
                Nombre='$nombre', 
                Apellido='$apellido', 
                Telefono='$telefono', 
                RolId='$rolId' 
              WHERE Id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: admin_usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el usuario: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admineditar.css">
    <title>Editar Usuario</title>
</head>
<body>
    <div class="form-container">
        <h2>Editar Usuario</h2> <!-- Encabezado en la parte superior del contenedor -->
        <form action="" method="POST">
            <label for="nombreUsuario">Nombre de Usuario:</label>
            <input type="text" id="nombreUsuario" name="nombreUsuario" value="<?php echo $user['NombreUsuario']; ?>" required><br>

            <label for="contrasena">Contraseña (dejar en blanco para mantenerla igual):</label>
            <input type="password" id="contrasena" name="contrasena"><br>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required><br>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $user['Nombre']; ?>" required><br>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo $user['Apellido']; ?>" required><br>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $user['Telefono']; ?>"><br>

            <label for="rolId">Rol:</label>
            <select id="rolId" name="rolId" <?php echo $isAdministrador ? 'disabled' : ''; ?> required>
                <?php
                $roles = mysqli_query($conn, "SELECT Id, NombreRol FROM Roles WHERE NombreRol != 'Administrador'");
                while ($rol = mysqli_fetch_assoc($roles)) {
                    $selected = $rol['Id'] == $user['RolId'] ? 'selected' : '';
                    echo "<option value='{$rol['Id']}' $selected>{$rol['NombreRol']}</option>";
                }
                if ($isAdministrador) {
                    echo "<option value='{$user['RolId']}' selected>Administrador</option>";
                }
                ?>
            </select><br>

            <input type="submit" value="Actualizar Usuario">
        </form>
    </div>
</body>
</html>
