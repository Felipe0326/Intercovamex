<?php
include('../db.php'); // Conexión a la base de datos
include('../authadmin.php');
// Verificar y validar el ID de la cita
$citaId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($citaId <= 0) {
    echo "ID de cita no válido.";
    exit();
}

// Consultar la información del contacto asociado antes de eliminar la cita
$contactoSql = "SELECT Contacto.Nombre, Contacto.Apellido 
                FROM Citas 
                JOIN Contacto ON Citas.ContactoId = Contacto.Id 
                WHERE Citas.Id = ?";
$contactoStmt = mysqli_prepare($conn, $contactoSql);
mysqli_stmt_bind_param($contactoStmt, 'i', $citaId);
mysqli_stmt_execute($contactoStmt);
$contactoResult = mysqli_stmt_get_result($contactoStmt);
$contacto = mysqli_fetch_assoc($contactoResult);
mysqli_stmt_close($contactoStmt);

// Mostrar información del contacto si está disponible
if ($contacto) {
    echo "El contacto asociado a esta cita es: " . $contacto['Nombre'] . " " . $contacto['Apellido'] . "<br>";
}

// Preparar y ejecutar la consulta de eliminación
$sql = "DELETE FROM Citas WHERE Id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $citaId);

if (mysqli_stmt_execute($stmt)) {
    // Redirigir si la eliminación fue exitosa
    header("Location: citas_usuarios.php?mensaje=eliminado");
    exit();
} else {
    echo "Error al eliminar la cita: " . mysqli_error($conn);
}

// Cerrar la declaración
mysqli_stmt_close($stmt);
?>