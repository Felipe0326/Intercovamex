<?php
include('db.php');
session_start();

// Verificar rol de administrador, servicio o vendedor
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4])) {
    echo "Acceso denegado.";
    exit;
}

$query = "SELECT * FROM ContactoCliente WHERE Estado = 'Pendiente'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Contactos de Clientes</title>
</head>
<body>
    <h2>Contactos Pendientes de Aceptación</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Servicio de Interés</th>
            <th>NParte</th>
            <th>Modelo</th>
            <th>NSerie</th>
            <th>Marca</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['Nombre']; ?></td>
            <td><?php echo $row['Apellido']; ?></td>
            <td><?php echo $row['Telefono']; ?></td>
            <td><?php echo $row['Correo']; ?></td>
            <td><?php echo $row['ServicioInteres']; ?></td>
            <td><?php echo $row['NParte']; ?></td>
            <td><?php echo $row['Modelo']; ?></td>
            <td><?php echo $row['NSerie']; ?></td>
            <td><?php echo $row['Marca']; ?></td>
            <td><?php echo $row['Descripcion']; ?></td>
            <td>
                <a href="aprobar_contacto.php?id=<?php echo $row['Id']; ?>">Aceptar</a>
                <a href="rechazar_contacto.php?id=<?php echo $row['Id']; ?>">Rechazar</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
