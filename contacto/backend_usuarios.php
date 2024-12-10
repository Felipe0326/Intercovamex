<?php
include('../db.php'); // Conexión a la base de datos

// Configuración de búsqueda y paginación
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 80; // Registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Función para obtener los contactos con filtros y límites
function obtenerContactos($conn, $searchTerm, $offset, $limit) {
    $query = "SELECT Contacto.Id, Contacto.Email, Contacto.Nombre, Contacto.Apellido, Contacto.Telefono, 
                     Contacto.Puesto, Contacto.Estado, Empresa.NombreEmpresa, Contacto.UsuarioFinal, Roles.NombreRol
              FROM Contacto
              LEFT JOIN Empresa ON Contacto.EmpresaId = Empresa.Id
              LEFT JOIN Roles ON Contacto.RolId = Roles.Id
              WHERE Contacto.Email LIKE ? 
                 OR Contacto.Nombre LIKE ? 
                 OR Contacto.Apellido LIKE ? 
                 OR Contacto.Puesto LIKE ? 
                 OR Contacto.Estado LIKE ?
              LIMIT ?, ?"; // Límite y desplazamiento para la paginación

    $stmt = mysqli_prepare($conn, $query);
    $searchLike = '%' . $searchTerm . '%';

    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, 'ssssssi', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $offset, $limit);

    // Ejecutar consulta
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $contactos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $contactos[] = $row;
    }
    return $contactos;
}

// Función para calcular el total de registros y páginas
function obtenerPaginacionContactos($conn, $searchTerm, $limit) {
    $sqlCount = "SELECT COUNT(*) as total 
                 FROM Contacto
                 LEFT JOIN Empresa ON Contacto.EmpresaId = Empresa.Id
                 LEFT JOIN Roles ON Contacto.RolId = Roles.Id
                 WHERE Contacto.Email LIKE ? 
                    OR Contacto.Nombre LIKE ? 
                    OR Contacto.Apellido LIKE ? 
                    OR Contacto.Puesto LIKE ? 
                    OR Contacto.Estado LIKE ?";

    $stmt = mysqli_prepare($conn, $sqlCount);
    $searchLike = '%' . $searchTerm . '%';

    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, 'sssss', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike);
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
[$totalRecords, $totalPages] = obtenerPaginacionContactos($conn, $searchTerm, $limit);

// Obtener los registros de contactos
$contactos = obtenerContactos($conn, $searchTerm, $offset, $limit);
?>
