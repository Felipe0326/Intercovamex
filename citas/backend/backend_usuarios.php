<?php
include('../../db.php'); // Conexión a la base de datos

// Configuración de búsqueda, filtro y paginación
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
$limit = 80; // Registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Construye la consulta SQL para obtener citas con filtros y límites
function construirConsultaCitas($searchTerm, $fechaInicio, $fechaFin, $offset, $limit) {
    $sql = "
        SELECT Citas.Id, Folio.CodigoFolio AS Folio, COALESCE(Citas.Dia, '') AS Dia, COALESCE(Citas.Hora, '') AS Hora, COALESCE(Citas.Estado, '') AS Estado,
               COALESCE(Citas.Metrica, '00:00:00') AS Metrica, COALESCE(Citas.Estatus, 'Espera') AS Estatus,
               COALESCE(Citas.HoraInicio, NULL) AS HoraInicio,
               COALESCE(Equipos.NParte, '') AS NParte, COALESCE(Equipos.Modelo, '') AS Modelo, COALESCE(Equipos.NSerie, '') AS NSerie,
               COALESCE(Empresa.NombreEmpresa, '') AS Cliente, 
               COALESCE(CONCAT(Empleados.Nombre, ' ', Empleados.Apellido), 'No asignado') AS Empleado,
               COALESCE(CONCAT(Contacto.Nombre, ' ', Contacto.Apellido), 'No asignado') AS Contacto
        FROM Citas
        LEFT JOIN Folio ON Citas.FolioIdC = Folio.Id
        LEFT JOIN Equipos ON Citas.EquipoId = Equipos.Id
        LEFT JOIN Empresa ON Citas.EmpresaId = Empresa.Id
        LEFT JOIN Empleados ON Citas.EmpleadoId = Empleados.Id
        LEFT JOIN Contacto ON Citas.ContactoId = Contacto.Id
        WHERE 1=1
    ";
    if (!empty($searchTerm)) {
        $sql .= " AND (Empresa.NombreEmpresa LIKE '%$searchTerm%' 
                      OR Empleados.Nombre LIKE '%$searchTerm%' 
                      OR Equipos.NParte LIKE '%$searchTerm%' 
                      OR Equipos.Modelo LIKE '%$searchTerm%'
                      OR Equipos.NSerie LIKE '%$searchTerm%'
                      OR Citas.Dia LIKE '%$searchTerm%'
                      OR Folio.CodigoFolio LIKE '%$searchTerm%')";
    }
    if (!empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND Citas.Dia BETWEEN '$fechaInicio' AND '$fechaFin'";
    }

    // Agregar paginación
    $sql .= " LIMIT $offset, $limit";

    return $sql;
}

// Ejecuta la consulta SQL y devuelve los resultados
function ejecutarConsultaCitas($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Error en la consulta SQL: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Calcula el tiempo total y el promedio de métricas de citas
function calcularTiemposCitas($conn) {
    $totalTimeQuery = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(CAST(Citas.Metrica AS TIME)))) AS TiempoTotal FROM Citas WHERE Citas.Metrica IS NOT NULL";
    $resultTotalTime = $conn->query($totalTimeQuery);
    $totalTime = $resultTotalTime->fetch_assoc()['TiempoTotal'] ?? '00:00:00';

    $totalCitasQuery = "SELECT COUNT(*) AS total FROM Citas";
    $totalCitasResult = $conn->query($totalCitasQuery);
    $totalCitas = $totalCitasResult->fetch_assoc()['total'] ?? 0;

    $averageTime = '00:00:00';
    if ($totalCitas > 0) {
        $averageTimeQuery = "SELECT SUM(TIME_TO_SEC(CAST(Citas.Metrica AS TIME))) / $totalCitas AS PromedioSegundos FROM Citas WHERE Citas.Metrica IS NOT NULL";
        $averageResult = $conn->query($averageTimeQuery);
        $averageSeconds = $averageResult->fetch_assoc()['PromedioSegundos'] ?? 0;
        $averageTime = gmdate("H:i:s", $averageSeconds);
    }

    return ['total' => $totalTime, 'promedio' => $averageTime];
}

// Función para contar el total de citas para la paginación
function obtenerTotalCitas($conn, $searchTerm, $fechaInicio, $fechaFin) {
    $query = "
        SELECT COUNT(*) as total
        FROM Citas
        LEFT JOIN Empresa ON Citas.EmpresaId = Empresa.Id
        LEFT JOIN Equipos ON Citas.EquipoId = Equipos.Id
        LEFT JOIN Folio ON Citas.FolioIdC = Folio.Id
        LEFT JOIN Empleados ON Citas.EmpleadoId = Empleados.Id
        WHERE 1=1
    ";

    if (!empty($searchTerm)) {
        $query .= " AND (Empresa.NombreEmpresa LIKE '%$searchTerm%' 
                        OR Empleados.Nombre LIKE '%$searchTerm%' 
                        OR Equipos.NParte LIKE '%$searchTerm%' 
                        OR Equipos.Modelo LIKE '%$searchTerm%' 
                        OR Equipos.NSerie LIKE '%$searchTerm%' 
                        OR Citas.Dia LIKE '%$searchTerm%' 
                        OR Folio.CodigoFolio LIKE '%$searchTerm%')";
    }

    if (!empty($fechaInicio) && !empty($fechaFin)) {
        $query .= " AND Citas.Dia BETWEEN '$fechaInicio' AND '$fechaFin'";
    }

    $result = $conn->query($query);

    if (!$result) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Obtener el total de registros y calcular páginas
$totalRecords = obtenerTotalCitas($conn, $searchTerm, $fechaInicio, $fechaFin);
$totalPages = ceil($totalRecords / $limit);

// Obtener los registros de citas
$sql = construirConsultaCitas($searchTerm, $fechaInicio, $fechaFin, $offset, $limit);
$citas = ejecutarConsultaCitas($conn, $sql);

// Calcular tiempos totales y promedio
$tiempos = calcularTiemposCitas($conn);
?>
