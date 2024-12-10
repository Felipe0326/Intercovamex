<?php
include('../db.php'); // Conexión a la base de datos

// Configuración de búsqueda, filtro y paginación
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;
$roleFilter = isset($_GET['role']) ? $_GET['role'] : null;
$limit = 80; // Registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Función para obtener empleados con filtros y límites
function obtenerEmpleados($conn, $searchTerm = null, $roleFilter = null, $offset = 0, $limit = 80) {
    $query = "SELECT Empleados.Id AS UsuarioId, Empleados.NombreUsuario, Empleados.Email, Empleados.Nombre, 
                     Empleados.Apellido, Empleados.Telefono, Roles.NombreRol, Empleados.Puesto, 
                     Empleados.Descripcion, Empleados.Foto 
              FROM Empleados
              JOIN Roles ON Empleados.RolId = Roles.Id";

    $conditions = [];
    $params = [];
    $types = '';

    if ($searchTerm) {
        $conditions[] = "(Empleados.NombreUsuario LIKE ? OR 
                          Empleados.Email LIKE ? OR 
                          Empleados.Nombre LIKE ? OR 
                          Empleados.Apellido LIKE ?)";
        $searchTerm = '%' . $searchTerm . '%';
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $types .= 'ssss';
    }

    if ($roleFilter && $roleFilter !== 'Todos') {
        $conditions[] = "Roles.NombreRol = ?";
        $params[] = $roleFilter;
        $types .= 's';
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }

    $query .= " LIMIT ?, ?";
    $params[] = $offset;
    $params[] = $limit;
    $types .= 'ii';

    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters solo si hay parámetros
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Función para calcular el total de empleados para paginación
function obtenerTotalEmpleados($conn, $searchTerm = null, $roleFilter = null) {
    $query = "SELECT COUNT(*) as total 
              FROM Empleados
              JOIN Roles ON Empleados.RolId = Roles.Id";

    $conditions = [];
    $params = [];
    $types = '';

    if ($searchTerm) {
        $conditions[] = "(Empleados.NombreUsuario LIKE ? OR 
                          Empleados.Email LIKE ? OR 
                          Empleados.Nombre LIKE ? OR 
                          Empleados.Apellido LIKE ?)";
        $searchTerm = '%' . $searchTerm . '%';
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $types .= 'ssss';
    }

    if ($roleFilter && $roleFilter !== 'Todos') {
        $conditions[] = "Roles.NombreRol = ?";
        $params[] = $roleFilter;
        $types .= 's';
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }

    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters solo si hay parámetros
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['total'];
    }
    return 0;
}

// Obtener el total de registros y calcular páginas
$totalRecords = obtenerTotalEmpleados($conn, $searchTerm, $roleFilter);
$totalPages = ceil($totalRecords / $limit);

// Obtener los registros de empleados
$empleados = obtenerEmpleados($conn, $searchTerm, $roleFilter, $offset, $limit);
?>
