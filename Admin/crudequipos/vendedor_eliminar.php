<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si el usuario tiene el rol adecuado (Administrador, Vendedor, Servicio)
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4])) {
    echo "Acceso denegado.";
    exit;
}

$clienteId = $_GET['id'];
$query = "DELETE FROM Clientes WHERE Id = $clienteId";

if (mysqli_query($conn, $query)) {
    header("Location: vendedor_usuarios.php");
    exit();
} else {
    echo "Error al eliminar el cliente: " . mysqli_error($conn);
}
?>
