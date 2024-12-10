<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar acceso para el cliente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    echo "Acceso denegado.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];
    $sucursal = $_POST['sucursal'];
    $usuarioId = $_SESSION['usuarioId'];

    // Insertar nueva cita
    $sql = "INSERT INTO Citas (Dia, Hora, Sucursal, UsuarioId) VALUES ('$dia', '$hora', '$sucursal', '$usuarioId')";
    if (mysqli_query($conn, $sql)) {
        // Establecer un mensaje de éxito en la sesión
        $_SESSION['success_message'] = "Cita agendada correctamente.";
        header("Location: citas_cliente_crear.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Cita</title>
    <link rel="stylesheet" href="styles.css">
    <script src="citasvalidaciones.js"></script>
</head>
<body>
    <h2>Crear Nueva Cita</h2>
    <form method="POST" action="citas_cliente_crear.php" onsubmit="return validarFormulario();">
        <label for="dia">Fecha:</label>
        <input type="date" id="dia" name="dia" required>
        
        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" required>
        
        <label for="sucursal">Sucursal:</label>
        <select id="sucursal" name="sucursal" required>
            <option value="Queretaro">Querétaro</option>
            <option value="Monterrey">Monterrey</option>
            <option value="Cuernavaca">Cuernavaca</option>
        </select>

        <button type="submit">Guardar Cita</button>
    </form>

    <a href="citas_cliente_historial.php">Volver a Historial de Citas</a>
</body>
</html>
