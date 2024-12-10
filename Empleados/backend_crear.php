<?php
include('../db.php'); // Conexión a la base de datos
function procesarFormularioEmpleado($conn) {
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Cifrado de contraseña
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $rolId = $_POST['rolId'];
    $puesto = $_POST['puesto'];
    $descripcion = $_POST['descripcion'];

    // Validar y cargar la foto
    $foto = $_FILES['foto']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($foto);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
        die("Solo se permiten archivos JPG y PNG.");
    }

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        die("Error al subir la foto.");
    }

    // Insertar el empleado en la base de datos
    $queryEmpleado = "INSERT INTO Empleados (NombreUsuario, Contrasena, Email, Nombre, Apellido, Telefono, RolId, Puesto, Descripcion, Foto) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $queryEmpleado);
    mysqli_stmt_bind_param($stmt, 'ssssssisss', $nombreUsuario, $contrasena, $email, $nombre, $apellido, $telefono, $rolId, $puesto, $descripcion, $foto);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: empleados_usuarios.php");
        exit();
    } else {
        die("Error al añadir empleado: " . mysqli_error($conn));
    }
}
?>
