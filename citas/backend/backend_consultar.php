<?php
include('../../db.php'); // Conexión a la base de datos

// Obtener el ID de la cita a consultar
$citaId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($citaId <= 0) {
    echo "ID de cita no válido.";
    exit;
}

// Consultar la cita, incluyendo el contacto asociado y los nuevos campos
$sql = "SELECT Citas.*, Equipos.NParte, Empresa.NombreEmpresa AS Cliente, 
               CONCAT(Empleados.Nombre, ' ', Empleados.Apellido) AS Empleado, 
               Contacto.Nombre AS ContactoNombre, Contacto.Apellido AS ContactoApellido
        FROM Citas
        LEFT JOIN Equipos ON Citas.EquipoId = Equipos.Id
        LEFT JOIN Empresa ON Citas.EmpresaId = Empresa.Id
        LEFT JOIN Empleados ON Citas.EmpleadoId = Empleados.Id
        LEFT JOIN Contacto ON Citas.ContactoId = Contacto.Id
        WHERE Citas.Id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $citaId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cita = mysqli_fetch_assoc($result);

if (!$cita) {
    echo "Cita no encontrada.";
    exit;
}
?>
