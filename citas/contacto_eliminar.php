<?php
include('db.php');
include('sesion_administrador.php');

$id = $_GET['id'];

// Eliminar el contacto
$query = "DELETE FROM Contacto WHERE Id = $id";

if (mysqli_query($conn, $query)) {
    header("Location: contacto_usuarios.php");
    exit();
} else {
    echo "Error al eliminar el contacto: " . mysqli_error($conn);
}
?>
