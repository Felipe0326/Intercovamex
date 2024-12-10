<?php
include('../db.php'); // Conexión a la base de datos

// Obtener el ID de la cita
$citaId = $_GET['citaId'] ?? null;

if ($citaId) {
    // Consulta para obtener la hora de inicio de la cita
    $query = "SELECT HoraInicio FROM Citas WHERE Id = $citaId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $horaInicio = $row['HoraInicio'];

        echo json_encode(['success' => true, 'horaInicio' => $horaInicio]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró la cita']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID de cita no proporcionado']);
}
?>
