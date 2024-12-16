<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/authadmin.php');

$id = $_GET['id'];
$query = "DELETE FROM Equipos WHERE Id = $id";

// Si el equipo fue eliminado correctamente
if (mysqli_query($conn, $query)) {
    header("Location: equipos_usuarios.php?deleted=true"); // Redirige con éxito
    exit();
} else {
    header("Location: equipos_usuarios.php?error=true"); // Redirige con error
    exit();
}

?>
