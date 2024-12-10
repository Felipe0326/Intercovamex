<?php
include('../db.php'); // Conexión a la base de datos
$id = $_GET['id'];
$query = "SELECT Equipos.*, Empresa.NombreEmpresa 
          FROM Equipos 
          JOIN Empresa ON Equipos.EmpresaEId = Empresa.Id 
          WHERE Equipos.Id = $id";
$result = mysqli_query($conn, $query);
$equipo = mysqli_fetch_assoc($result);

if (!$equipo) {
    echo "Equipo no encontrado.";
    exit;
}
?>