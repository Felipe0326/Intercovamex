<?php
include('../../db.php');
include('../../sesion_administrador.php');  // Verifica que el usuario tenga permisos de administrador

$id = $_GET['id'];

// Verificar que el ID sea válido
if (!isset($id) || !is_numeric($id)) {
    echo "ID de contacto no válido.";
    exit;
}

// Eliminar el contacto
$query = "DELETE FROM Contacto WHERE Id = $id";

if (mysqli_query($conn, $query)) {
    // Redirigir con el parámetro 'deleted' para indicar éxito
    header("Location: ../frontend/contacto_usuarios.php?deleted=true");
    exit();
} else {
    // Redirigir con el parámetro 'error' si hay un problema
    header("Location: ../frontend/contacto_usuarios.php?error=true");
    exit();
}
?>
