<?php
include('../../db.php'); // Conexión a la base de datos

// Obtener el ID de la cita desde el parámetro de la URL
$citaId = $_GET['citaId'] ?? null;

if ($citaId) {
    // Consulta para obtener el estatus de la cita
    $query = "SELECT Estatus FROM Citas WHERE Id = $citaId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['estatus' => $row['Estatus']]);
    } else {
        echo json_encode(['estatus' => '']);
    }
} else {
    echo json_encode(['estatus' => '']);
}
?>
