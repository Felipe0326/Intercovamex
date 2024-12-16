<?php
session_start();
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.html');
    exit;
}

// Verificar roles permitidos
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 5])) {
    echo "Acceso denegado.";
    exit;
}

// Obtener los datos del usuario logueado
function getLoggedInUser($conn, $usuarioId) {
    $query = "SELECT Empleados.NombreUsuario, Empleados.Nombre, Empleados.Apellido, Empleados.Email, 
                     Empleados.Telefono, Roles.NombreRol, Empleados.Puesto, Empleados.Foto
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