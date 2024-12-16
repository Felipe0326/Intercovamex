<?php
include('../../db.php');

// Obtener los datos enviados por GET
$citaId = $_GET['citaId'] ?? null;

if ($citaId) {
    // Consulta para obtener la HoraInicio y el Estatus
    $query = "SELECT HoraInicio, Estatus FROM Citas WHERE Id = $citaId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Cita no encontrada']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'CitaId no proporcionada']);
}
?>
