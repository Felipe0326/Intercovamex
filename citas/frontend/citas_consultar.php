<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/auth.php');
include('../backend/backend_consultar.php'); // Backend para consultar la cita
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/citasconsultar.css">
    <title>Consultar Cita</title>
</head>
<body>
    <div class="form-container">
        <h2>Detalles de la Cita</h2>
        <div class="info-grid">
            <p><strong>ID:</strong> <?php echo $cita['Id']; ?></p>
            <p><strong>Folio:</strong> <?php echo $cita['Folio']; ?></p>
            <p><strong>Día:</strong> <?php echo $cita['Dia']; ?></p>
            <p><strong>Hora:</strong> <?php echo $cita['Hora']; ?></p>
            <p><strong>Estado:</strong> <?php echo $cita['Estado']; ?></p>
            <p><strong>Estatus:</strong> <?php echo $cita['Estatus']; ?></p>
            <p><strong>Métrica:</strong> <?php echo isset($cita['Metrica']) ? $cita['Metrica'] : 'No disponible'; ?></p>
            <p><strong>Equipo:</strong> <?php echo isset($cita['NParte']) ? $cita['NParte'] : 'No asignado'; ?></p>
            <p><strong>Cliente:</strong> <?php echo $cita['Cliente']; ?></p>
            <p><strong>Contacto:</strong> <?php echo isset($cita['ContactoNombre']) ? $cita['ContactoNombre'] . ' ' . $cita['ContactoApellido'] : 'No asignado'; ?></p>
            <p><strong>Empleado:</strong> <?php echo isset($cita['Empleado']) ? $cita['Empleado'] : 'No asignado'; ?></p>
        </div>
        <div class="btn-back-container">
            <a href="citas_usuarios.php?consulted=true" class="btn-back">Volver a la lista de citas</a>
            </div>

    </div>
</body>
</html>

