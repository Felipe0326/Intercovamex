<?php
include('../db.php'); // Conexión a la base de datos

// Función para obtener equipos con filtros y paginación
function getEquipos($conn, $searchTerm, $statusFilter, $offset, $limit) {
    $sql = "SELECT Equipos.Id, Equipos.NParte, Equipos.Modelo, Equipos.NSerie, Equipos.Marca, 
                   Equipos.Observaciones, Equipos.Estatus, Empresa.NombreEmpresa 
            FROM Equipos 
            JOIN Empresa ON Equipos.EmpresaEId = Empresa.Id
            WHERE (Equipos.NParte LIKE ? OR Equipos.Modelo LIKE ? 
                   OR Equipos.NSerie LIKE ? OR Equipos.Marca LIKE ? 
                   OR Empresa.NombreEmpresa LIKE ?)";

    if ($statusFilter) {
        $sql .= " AND Equipos.Estatus = ?";
    }

    $sql .= " LIMIT ?, ?"; // Manejo de límites y desplazamiento

    $stmt = mysqli_prepare($conn, $sql);
    $searchLike = '%' . $searchTerm . '%';

    if ($statusFilter) {
        mysqli_stmt_bind_param($stmt, 'ssssssii', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $statusFilter, $offset, $limit);
    } else {
        mysqli_stmt_bind_param($stmt, 'sssssii', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $offset, $limit);
    }

    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Configuración de la paginación
$limit = 80; // Registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Contar el total de registros para calcular el número de páginas
$sqlCount = "SELECT COUNT(*) as total FROM Equipos";
$resultCount = mysqli_query($conn, $sqlCount);

if (!$resultCount) {
    die("Error en la consulta de conteo: " . mysqli_error($conn));
}

$rowCount = mysqli_fetch_assoc($resultCount);
$totalRecords = $rowCount['total'];
$totalPages = ceil($totalRecords / $limit);

// Obtener equipos con los límites calculados
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$result = getEquipos($conn, $searchTerm, $statusFilter, $offset, $limit);
?>

