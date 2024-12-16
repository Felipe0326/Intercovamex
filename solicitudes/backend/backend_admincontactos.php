<?php
include('../../db.php'); // Conexión a la base de datos
// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$query = "SELECT Empleados.NombreUsuario, Empleados.Nombre, Empleados.Apellido, Empleados.Email, Empleados.Telefono, Roles.NombreRol, Empleados.Puesto, Empleados.Foto
          FROM Empleados 
          JOIN Roles ON Empleados.RolId = Roles.Id
          WHERE Empleados.Id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $usuarioId);
mysqli_stmt_execute($stmt);
$resultUser = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($resultUser);

// Obtener el filtro de estado seleccionado
$estadoFiltro = isset($_GET['estado']) ? $_GET['estado'] : 'Todos';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Configuración de la paginación
$limit = 80; // Registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Construir la consulta para obtener los contactos con el filtro de estado
$sql = "
    SELECT 
        ContactoCliente.*, 
        Folio.CodigoFolio 
    FROM ContactoCliente
    LEFT JOIN Folio ON ContactoCliente.FolioIdContactoCliente = Folio.Id
    WHERE (ContactoCliente.Nombre LIKE ? OR ContactoCliente.Apellido LIKE ? OR ContactoCliente.Telefono LIKE ? 
    OR ContactoCliente.Correo LIKE ? OR ContactoCliente.ServicioInteres LIKE ?)
";

if ($estadoFiltro !== 'Todos') {
    $sql .= " AND ContactoCliente.Estado = ?";
}

$sql .= " LIMIT ?, ?"; // Agregar paginación

// Ejecutar la consulta con los parámetros
$stmt = mysqli_prepare($conn, $sql);
$searchLike = '%' . $searchTerm . '%';

// Si el filtro de estado es distinto de 'Todos', debes vincular 6 parámetros, si no, 5
if ($estadoFiltro !== 'Todos') {
    mysqli_stmt_bind_param($stmt, 'ssssssi', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $estadoFiltro, $offset, $limit);
} else {
    mysqli_stmt_bind_param($stmt, 'ssssssi', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $offset, $limit);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Contador total de contactos
$sqlCount = "SELECT COUNT(*) as total FROM ContactoCliente LEFT JOIN Folio ON ContactoCliente.FolioIdContactoCliente = Folio.Id
WHERE (ContactoCliente.Nombre LIKE ? OR ContactoCliente.Apellido LIKE ? OR ContactoCliente.Telefono LIKE ? 
    OR ContactoCliente.Correo LIKE ? OR ContactoCliente.ServicioInteres LIKE ?)";
if ($estadoFiltro !== 'Todos') {
    $sqlCount .= " AND ContactoCliente.Estado = ?";
}
$stmtCount = mysqli_prepare($conn, $sqlCount);
if ($estadoFiltro !== 'Todos') {
    mysqli_stmt_bind_param($stmtCount, 'sssss', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike, $estadoFiltro);
} else {
    mysqli_stmt_bind_param($stmtCount, 'sssss', $searchLike, $searchLike, $searchLike, $searchLike, $searchLike);
}
mysqli_stmt_execute($stmtCount);
$resultCount = mysqli_stmt_get_result($stmtCount);
$rowCount = mysqli_fetch_assoc($resultCount);
$totalContactos = $rowCount['total'];

// Calcular el total de páginas
$totalPages = ceil($totalContactos / $limit);
?>
