<?php
include('db.php'); // Conexión a la base de datos

$equipoId = $_GET['equipoId'];
$sql = "SELECT ClienteId FROM Equipos WHERE Id = $equipoId";
$result = mysqli_query($conn, $sql);

$cliente = mysqli_fetch_assoc($result);
echo json_encode(['clienteId' => $cliente['ClienteId']]);
?>
