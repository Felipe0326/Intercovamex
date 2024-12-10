<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Acceso exclusivo para administradores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    echo "Acceso denegado.";
    exit;
}

// Obtener el ID del usuario desde la URL
$usuarioId = isset($_GET['id']) ? $_GET['id'] : '';
if (!$usuarioId) {
    echo "Usuario no encontrado.";
    exit;
}

// Consulta para obtener datos del usuario y detalles adicionales según su rol
$sql = "SELECT Usuarios.NombreUsuario, Usuarios.Email, Usuarios.Nombre, Usuarios.Apellido, Usuarios.Telefono, Roles.NombreRol,
               Empleados.Puesto, Empleados.Descripcion, Empleados.Foto,
               Clientes.NombreEmpresa, Clientes.RazonS, Clientes.DireccionFiscal, Clientes.Estado, Clientes.Rfc, Clientes.CodigoPostal, Clientes.UsuarioFinal
        FROM Usuarios
        JOIN Roles ON Usuarios.RolId = Roles.Id
        LEFT JOIN Empleados ON Usuarios.Id = Empleados.UsuarioId
        LEFT JOIN Clientes ON Usuarios.Id = Clientes.UsuarioId
        WHERE Usuarios.Id = $usuarioId";

$result = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($result);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Detalles del Usuario</h2>
    <table>
        <tr>
            <th>Nombre de Usuario</th>
            <th>Email</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Rol</th>
            <?php if ($usuario['NombreRol'] === 'Vendedor' || $usuario['NombreRol'] === 'Servicio') { ?>
                <th>Puesto</th>
                <th>Descripción</th>
                <th>Foto</th>
            <?php } elseif ($usuario['NombreRol'] === 'Cliente') { ?>
                <th>Empresa</th>
                <th>Razón Social</th>
                <th>Dirección Fiscal</th>
                <th>Estado</th>
                <th>RFC</th>
                <th>Código Postal</th>
                <th>Usuario Final</th>
            <?php } ?>
        </tr>
        <tr>
            <td><?php echo $usuario['NombreUsuario']; ?></td>
            <td><?php echo $usuario['Email']; ?></td>
            <td><?php echo $usuario['Nombre']; ?></td>
            <td><?php echo $usuario['Apellido']; ?></td>
            <td><?php echo $usuario['Telefono']; ?></td>
            <td><?php echo $usuario['NombreRol']; ?></td>
            <?php if ($usuario['NombreRol'] === 'Vendedor' || $usuario['NombreRol'] === 'Servicio') { ?>
                <td><?php echo $usuario['Puesto']; ?></td>
                <td><?php echo $usuario['Descripcion']; ?></td>
                <td><img src="uploads/<?php echo $usuario['Foto']; ?>" alt="Foto del Empleado" width="100"></td>
            <?php } elseif ($usuario['NombreRol'] === 'Cliente') { ?>
                <td><?php echo $usuario['NombreEmpresa']; ?></td>
                <td><?php echo $usuario['RazonS']; ?></td>
                <td><?php echo $usuario['DireccionFiscal']; ?></td>
                <td><?php echo $usuario['Estado']; ?></td>
                <td><?php echo $usuario['Rfc']; ?></td>
                <td><?php echo $usuario['CodigoPostal']; ?></td>
                <td><?php echo $usuario['UsuarioFinal']; ?></td>
            <?php } ?>
        </tr>
    </table>

    <a href="admin_usuarios.php">Volver a Gestión de Usuarios</a>
</body>
</html>
