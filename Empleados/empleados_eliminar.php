<?php
include('../db.php'); // Conexión a la base de datos
include('../authadmin.php');


// Obtener el ID del usuario del empleado
$usuarioId = $_GET['id'];

// Obtener el nombre de archivo de la foto del empleado para eliminarla del servidor
$queryFoto = "SELECT Foto FROM Empleados WHERE Id = $usuarioId";
$resultFoto = mysqli_query($conn, $queryFoto);
$empleado = mysqli_fetch_assoc($resultFoto);

if ($empleado && !empty($empleado['Foto'])) {
    $filePath = 'uploads/' . $empleado['Foto'];
    if (file_exists($filePath)) {
        unlink($filePath); // Eliminar el archivo de foto del servidor
    }
}

// Eliminar el registro del empleado de la tabla Empleados
$queryEmpleado = "DELETE FROM Empleados WHERE Id = $usuarioId";
if (mysqli_query($conn, $queryEmpleado)) {
    header("Location: empleados_usuarios.php");
    exit();
} else {
    echo "Error al eliminar los datos del empleado: " . mysqli_error($conn);
}
?>
