<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/authadmin.php');

$empresaId = $_GET['id'] ?? null;

if (!$empresaId) {
    die("Error: ID de empresa no especificado.");
}

// Consulta para eliminar la empresa y su relación con el usuario final si aplica
$query = "DELETE FROM Empresa WHERE Id = $empresaId";

if (mysqli_query($conn, $query)) {
    // Redirigir con parámetro de éxito si la empresa fue eliminada correctamente
    header("Location: empresa_usuarios.php?deleted=true");
    exit();
} else {
    // Redirigir con parámetro de error si hubo un problema al eliminar
    header("Location: empresa_usuarios.php?error=true");
    exit();
}
?>
