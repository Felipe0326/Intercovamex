<?php
include('../../db.php'); // Conexión a la base de datos
function validarYObtenerEmpleado($conn, $usuarioId) {
    $usuarioId = isset($usuarioId) ? intval($usuarioId) : null;
    if (!$usuarioId) {
        return null; // ID no válido
    }

    $query = "SELECT Empleados.NombreUsuario, Empleados.Email, Empleados.Nombre, Empleados.Apellido, Empleados.Telefono, 
                     Roles.NombreRol, Empleados.Puesto, Empleados.Descripcion, Empleados.Foto
              FROM Empleados
              JOIN Roles ON Empleados.RolId = Roles.Id
              WHERE Empleados.Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $usuarioId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}
?>
