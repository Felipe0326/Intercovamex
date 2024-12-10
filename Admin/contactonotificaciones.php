<?php
include('db.php');
session_start();

// Verificar si el usuario es administrador, vendedor o servicio
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4])) {
    echo "Acceso denegado.";
    exit;
}

// Contar notificaciones pendientes
$notificacionQuery = "SELECT COUNT(*) AS totalPendientes FROM ContactoCliente WHERE Estado = 'Pendiente'";
$notificacionResult = mysqli_query($conn, $notificacionQuery);
$notificacionData = mysqli_fetch_assoc($notificacionResult);
$pendientes = $notificacionData['totalPendientes'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Notificaciones</title>
</head>
<body>
    <h2>Panel de Notificaciones</h2>

    <?php if ($pendientes > 0) : ?>
        <p>Tienes <strong><?php echo $pendientes; ?></strong> solicitudes pendientes.</p>
        <a href="administrar_contactos.php">Ver solicitudes pendientes</a>
    <?php else : ?>
        <p>No hay solicitudes pendientes.</p>
    <?php endif; ?>
</body>
</html>
