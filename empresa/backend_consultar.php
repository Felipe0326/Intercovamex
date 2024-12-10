<?php
include('../db.php'); // Conexión a la base de datos
$empresaId = $_GET['id'] ?? null;

if (!$empresaId) {
    die("Error: ID de la empresa no especificado.");
}

// Consulta para obtener los datos de la empresa y el nombre del usuario final asignado
$queryEmpresa = "SELECT Empresa.*, CONCAT(Contacto.Nombre, ' ', Contacto.Apellido) AS UsuarioFinalNombre 
                 FROM Empresa
                 LEFT JOIN Contacto ON Empresa.Id = Contacto.EmpresaId AND Contacto.UsuarioFinal = 'Sí'
                 WHERE Empresa.Id = $empresaId";
$resultEmpresa = mysqli_query($conn, $queryEmpresa);

if (!$resultEmpresa) {
    die("Error al obtener datos de la empresa: " . mysqli_error($conn));
}

$empresa = mysqli_fetch_assoc($resultEmpresa);

if (!$empresa) {
    die("Empresa no encontrada.");
}
?>
