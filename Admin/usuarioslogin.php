<?php
// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$query = "SELECT Empleados.NombreUsuario, Empleados.Nombre, Empleados.Apellido, Empleados.Email, Empleados.Telefono, Roles.NombreRol, Empleados.Puesto, Empleados.Foto
          FROM Empleados 
          JOIN Roles ON Empleados.RolId = Roles.Id
          WHERE Empleados.Id = $usuarioId";
$resultUser = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($resultUser);
?>