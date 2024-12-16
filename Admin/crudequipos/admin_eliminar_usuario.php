<?php
include('db.php'); // Conexión a la base de datos

$id = $_GET['id'];
$query = "DELETE FROM Usuarios WHERE Id = $id";

if (mysqli_query($conn, $query)) {
    header("Location: admin_usuarios.php");
    exit();
} else {
    echo "Error al eliminar el usuario: " . mysqli_error($conn);
}
?>
