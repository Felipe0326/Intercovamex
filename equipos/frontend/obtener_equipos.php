<?php
include('db.php'); // Conexión a la base de datos

$clienteId = $_GET['clienteId'];
$sql = "SELECT Id, NParte FROM Equipos WHERE ClienteId = $clienteId";
$result = mysqli_query($conn, $sql);

$equipos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $equipos[] = $row;
}

echo json_encode($equipos);
?>
