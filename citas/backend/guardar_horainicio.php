<?php
include('../../db.php'); // Conexión a la base de datos

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);
$citaId = $data['citaId'] ?? null;
$horaInicio = $data['horaInicio'] ?? null;

if ($citaId && $horaInicio) {
    // Consulta para actualizar la HoraInicio
    $query = "UPDATE Citas SET HoraInicio = '$horaInicio' WHERE Id = $citaId AND HoraInicio IS NULL";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos insuficientes']);
}
?>
