<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/auth135.php');

// Obtener el ID del contacto desde el parámetro GET
$id = $_GET['id'];

// Obtener el ID del rol "Generico"
$rolQuery = "SELECT Id FROM Roles WHERE NombreRol = 'Generico'";
$rolResult = mysqli_query($conn, $rolQuery);
$rol = mysqli_fetch_assoc($rolResult);

if (!$rol) {
    echo "Error: No se encontró el rol 'Generico' en la tabla Roles.";
    exit;
}

$rolIdGenerico = $rol['Id'];

// Actualizar estado a "Aceptado" y asignar el rol "Generico"
$query = "UPDATE ContactoCliente SET Estado = 'Aceptado', RolId = ? WHERE Id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $rolIdGenerico, $id);

if (mysqli_stmt_execute($stmt)) {
    echo "Contacto aceptado y rol 'Generico' asignado exitosamente.";
    // Redirigir a la lista de contactos
    header("Location: ../frontend/administrar_contactos.php");
    exit();
} else {
    echo "Error al aceptar el contacto y asignar el rol: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
