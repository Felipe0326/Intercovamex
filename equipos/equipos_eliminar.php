<?php
include('../db.php'); // Conexión a la base de datos
include('../authadmin.php');

$id = $_GET['id'];
$query = "DELETE FROM Equipos WHERE Id = $id";

if (mysqli_query($conn, $query)) {
    header("Location: equipos_usuarios.php");
    exit();
} else {
    echo "Error al eliminar el equipo: " . mysqli_error($conn);
}
?>
