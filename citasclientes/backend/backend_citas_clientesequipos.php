<?php
include('../../db.php'); // Conexión a la base de datos

function getEquiposByEmpresa($conn, $empresaId) {
    $query = "SELECT Id, NParte, Modelo, Marca, NSerie FROM Equipos WHERE EmpresaEId = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return []; // Retorna un array vacío si falla la preparación
    }

    mysqli_stmt_bind_param($stmt, 'i', $empresaId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $equipos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $equipos[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $equipos;
}

function getHistorialByEquipo($conn, $equipoId) {
    $query = "
        SELECT c.Dia, c.Hora, 
               CONCAT('Nparte:', e.NParte, ' | ', 'Modelo:', e.Modelo, ' | ', 'Marca:', e.Marca, ' | ', 'NSerie:', e.NSerie) AS Equipo,
               emp.Nombre AS NombreEmpleado, 
               cli.Nombre AS NombreCliente,
               c.TituloContacto, c.ServicioInteres, f.CodigoFolio, 
               empresa.NombreEmpresa AS Empresa,
               c.Estado AS EstadoCita,      -- Campo Estado de la cita
               c.Estatus AS EstatusCita     -- Campo Estatus de la cita
        FROM Citas c
        LEFT JOIN Equipos e ON c.EquipoId = e.Id
        LEFT JOIN Empleados emp ON c.EmpleadoId = emp.Id
        LEFT JOIN Contacto cli ON c.ContactoId = cli.Id
        LEFT JOIN Empresa empresa ON c.EmpresaId = empresa.Id
        LEFT JOIN Folio f ON c.FolioIdC = f.Id
        WHERE c.EquipoId = ?
        ORDER BY c.Dia DESC, c.Hora DESC";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return []; // Retorna un array vacío si falla la preparación
    }

    mysqli_stmt_bind_param($stmt, 'i', $equipoId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $historial = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $historial[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $historial;
}
?>

