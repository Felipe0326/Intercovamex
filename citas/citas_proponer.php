<?php
include('../db.php'); // Conexión a la base de datos
include('../auth.php');
include('backend_proponer.php'); // Backend para procesar la propuesta de nueva fecha
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/citasproponer.css">
    <title>Proponer Nueva Fecha de Cita</title>
</head>
<body>
    <div class="form-container">
        <h2>Proponer Nueva Fecha</h2>

        <form action="citas_proponer.php" method="POST">
            <!-- Campo oculto para el ID de la cita -->
            <input type="hidden" name="citaId" value="<?php echo isset($_GET['id']) ? (int)$_GET['id'] : 0; ?>">

            <div class="form-group">
                <label for="dia">Nueva Fecha:</label>
                <input type="date" id="dia" name="dia" required>
            </div>

            <div class="form-group">
                <label for="hora">Nueva Hora:</label>
                <input type="time" id="hora" name="hora" required>
            </div>

            <input type="submit" value="Proponer Nueva Fecha" class="btn-submit">
        </form>

        <div class="btn-back-container">
            <a href="citas_usuarios.php" class="btn-back">Volver a la lista de citas</a>
        </div>
    </div>
</body>
</html>

