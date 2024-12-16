<?php
include('../../db.php'); // Conexión a la base de datos

// Configuración de búsqueda y paginación
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 80; // Registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Función para obtener empresas con filtros y paginación
function obtenerEmpresas($conn, $searchTerm, $offset, $limit) {
    $query = "SELECT Empresa.Id, Empresa.NombreEmpresa, Empresa.RazonS, Empresa.DireccionFiscal, Empresa.Estado, 
                     Empresa.Rfc, Empresa.CodigoPostal, 
                     CONCAT(Contacto.Nombre, ' ', Contacto.Apellido) AS UsuarioFinalNombre 
              FROM Empresa
              LEFT JOIN Contacto ON Empresa.Id = Contacto.EmpresaId AND Contacto.UsuarioFinal = 'Sí'
              WHERE (Empresa.NombreEmpresa LIKE ? 
                  OR Empresa.RazonS LIKE ?
                  OR Empresa.DireccionFiscal LIKE ?
                  OR Empresa.Estado LIKE ?
                  OR Empresa.Rfc LIKE ?
                  OR Empresa.CodigoPostal LIKE ?
                  OR CONCAT(Contacto.Nombre, ' ', Contacto.Apellido) LIKE ?)
              LIMIT ?, ?"; // Manejo de límites y desplazamiento

    $stmt = mysqli_prepare($conn, $query);
    $searchLike = '%' . $searchTerm . '%';

    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, 'sssssssii', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $offset, $limit);

    // Ejecutar consulta
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $empresas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $empresas[] = $row;
    }
    return $empresas;
}

// Función para obtener el total de registros y calcular páginas
function obtenerPaginacionEmpresas($conn, $searchTerm, $limit) {
    $sqlCount = "SELECT COUNT(*) as total 
                 FROM Empresa 
                 LEFT JOIN Contacto ON Empresa.Id = Contacto.EmpresaId AND Contacto.UsuarioFinal = 'Sí'
                 WHERE (Empresa.NombreEmpresa LIKE ? 
                     OR Empresa.RazonS LIKE ?
                     OR Empresa.DireccionFiscal LIKE ?
                     OR Empresa.Estado LIKE ?
                     OR Empresa.Rfc LIKE ?
                     OR Empresa.CodigoPostal LIKE ?
                     OR CONCAT(Contacto.Nombre, ' ', Contacto.Apellido) LIKE ?)";

    $stmt = mysqli_prepare($conn, $sqlCount);
    $searchLike = '%' . $searchTerm . '%';
    mysqli_stmt_bind_param($stmt, 'sssssss', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $searchLike);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $totalRecords = $row['total'];
        $totalPages = ceil($totalRecords / $limit);
        return [$totalRecords, $totalPages];
    }
    return [0, 0];
}

// Obtener el total de registros y páginas
[$totalRecords, $totalPages] = obtenerPaginacionEmpresas($conn, $searchTerm, $limit);

// Obtener los registros de empresas
$empresas = obtenerEmpresas($conn, $searchTerm, $offset, $limit);
?>

