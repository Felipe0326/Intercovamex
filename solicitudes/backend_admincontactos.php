<?php
include('../db.php'); // Conexión a la base de datos
// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$query = "SELECT Empleados.NombreUsuario, Empleados.Nombre, Empleados.Apellido, Empleados.Email, Empleados.Telefono, Roles.NombreRol, Empleados.Puesto, Empleados.Foto
          FROM Empleados 
          JOIN Roles ON Empleados.RolId = Roles.Id
          WHERE Empleados.Id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $usuarioId);
mysqli_stmt_execute($stmt);
$resultUser = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($resultUser);

// Obtener el filtro de estado seleccionado
$estadoFiltro = isset($_GET['estado']) ? $_GET['estado'] : 'Todos';

// Construir la consulta para obtener los contactos con el filtro de estado
$sql = "
    SELECT 
        ContactoCliente.*, 
        Folio.CodigoFolio 
    FROM ContactoCliente
    LEFT JOIN Folio ON ContactoCliente.FolioIdContactoCliente = Folio.Id
";
if ($estadoFiltro !== 'Todos') {
    $sql .= " WHERE ContactoCliente.Estado = ?";
}

// Ejecutar la consulta
$stmt = mysqli_prepare($conn, $sql);
if ($estadoFiltro !== 'Todos') {
    mysqli_stmt_bind_param($stmt, 's', $estadoFiltro);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Contador total de contactos
$totalContactos = mysqli_num_rows($result);
?>
