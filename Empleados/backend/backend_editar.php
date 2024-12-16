<?php
include('../../db.php'); // Conexión a la base de datos
function obtenerEmpleado($conn, $usuarioId) {
    $query = "SELECT Empleados.NombreUsuario, Empleados.Email, Empleados.Nombre, Empleados.Apellido, Empleados.Telefono, 
                     Roles.Id AS RolId, Empleados.Puesto, Empleados.Descripcion, Empleados.Foto
              FROM Empleados
              JOIN Roles ON Empleados.RolId = Roles.Id
              WHERE Empleados.Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $usuarioId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function obtenerRoles($conn) {
    $query = "SELECT Id, NombreRol FROM Roles WHERE NombreRol IN ('Vendedor', 'Servicio')";
    $result = mysqli_query($conn, $query);
    $roles = [];
    while ($rol = mysqli_fetch_assoc($result)) {
        $roles[] = $rol;
    }
    return $roles;
}

function actualizarEmpleado($conn, $empleado) {
    $usuarioId = $_GET['id'];
    $nombreUsuario = $_POST['nombreUsuario'];
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $rolId = $_POST['rolId'];
    $puesto = $_POST['puesto'];
    $descripcion = $_POST['descripcion'];
    $nuevaFoto = $_FILES['foto']['name'];
    $fotoActual = $empleado['Foto'];

    $foto = $fotoActual;
    if ($nuevaFoto) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($nuevaFoto);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            die("Solo se permiten archivos JPG y PNG.");
        }
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            if ($fotoActual && file_exists($target_dir . $fotoActual)) {
                unlink($target_dir . $fotoActual);
            }
            $foto = $nuevaFoto;
        } else {
            die("Error al subir la nueva foto.");
        }
    }

    $queryEmpleado = "UPDATE Empleados SET 
                        NombreUsuario = ?, 
                        Email = ?, 
                        Nombre = ?, 
                        Apellido = ?, 
                        Telefono = ?, 
                        RolId = ?, 
                        Puesto = ?, 
                        Descripcion = ?, 
                        Foto = ? 
                      WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $queryEmpleado);
    mysqli_stmt_bind_param($stmt, 'sssssisisi', $nombreUsuario, $email, $nombre, $apellido, $telefono, $rolId, $puesto, $descripcion, $foto, $usuarioId);
    if (mysqli_stmt_execute($stmt)) {
        // Redirigir con parámetro 'edited' si la actualización fue exitosa
        header("Location: empleados_usuarios.php?edited=true");
        exit();
    } else {
        // Redirigir con parámetro 'error' si hubo un problema al actualizar
        header("Location: empleados_usuarios.php?error=true");
        exit();
    }
}
?>
