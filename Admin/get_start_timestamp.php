<?php
include('db.php'); // Conexión a la base de datos

// Obtener el ID de la cita desde el parámetro de la URL
$citaId = $_GET['citaId'] ?? null;

if ($citaId) {
    // Consulta para obtener el timestamp de inicio de la cita
    $query = "SELECT HoraInicio FROM Citas WHERE Id = $citaId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'startTimestamp' => strtotime($row['HoraInicio']) * 1000]); // Enviar timestamp en milisegundos
    } else {
        echo json_encode(['success' => false, 'error' => 'Cita no encontrada']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID de cita no proporcionado']);
}
?>
