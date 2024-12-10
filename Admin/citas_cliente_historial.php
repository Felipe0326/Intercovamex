<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar acceso para el cliente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    echo "Acceso denegado.";
    exit;
}

// Mostrar mensaje de éxito si existe
if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message'>{$_SESSION['success_message']}</div>";
    unset($_SESSION['success_message']); // Limpiar el mensaje después de mostrarlo
}

// Obtener historial de citas
$usuarioId = $_SESSION['usuarioId'];
$queryHistorial = "SELECT * FROM Citas WHERE UsuarioId = $usuarioId ORDER BY Dia DESC, Hora DESC";
$historial = mysqli_query($conn, $queryHistorial);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Citas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Historial de Citas</h2>
    <table>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Sucursal</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($historial)) { ?>
        <tr>
            <td><?php echo $row['Dia']; ?></td>
            <td><?php echo $row['Hora']; ?></td>
            <td><?php echo $row['Sucursal']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="citas_cliente_crear.php">Programar Nueva Cita</a>
</body>
</html>
