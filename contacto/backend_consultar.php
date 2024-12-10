<?php
include('../db.php'); // Conexión a la base de datos

// Validar y obtener el ID del contacto
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo "ID de contacto no válido.";
    exit;
}

// Consultar la información del contacto
$query = "SELECT Contacto.*, Empresa.NombreEmpresa, Roles.NombreRol
          FROM Contacto
          LEFT JOIN Empresa ON Contacto.EmpresaId = Empresa.Id
          LEFT JOIN Roles ON Contacto.RolId = Roles.Id
          WHERE Contacto.Id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Verificar si el contacto existe
if (!$user) {
    echo "Contacto no encontrado.";
    exit;
}
?>
