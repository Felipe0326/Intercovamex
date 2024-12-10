<?php
include('../db.php'); // Conexión a la base de datos
include('../authadmin.php');

$empresaId = $_GET['id'];

// Consulta para eliminar la empresa y su relación con el usuario final si aplica
$query = "DELETE FROM Empresa WHERE Id = $empresaId";

if (mysqli_query($conn, $query)) {
    header("Location: empresa_usuarios.php");
    exit();
} else {
    echo "Error al eliminar la empresa: " . mysqli_error($conn);
}
?>
