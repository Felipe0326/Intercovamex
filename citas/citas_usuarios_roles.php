<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.html'); // Redirigir al login si no está logueado
    exit;
}

// Verificar si el usuario tiene el rol adecuado para acceder (1, 3, 4, 5)
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4, 5])) {
    echo "Acceso denegado.";
    exit;
}

// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$query = "SELECT Empleados.NombreUsuario, Empleados.Nombre, Empleados.Apellido, Empleados.Email, Empleados.Telefono, Roles.NombreRol, Empleados.Puesto, Empleados.Foto
          FROM Empleados 
          JOIN Roles ON Empleados.RolId = Roles.Id
          WHERE Empleados.Id = $usuarioId";
$resultUser = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($resultUser);

// Consulta para contar solicitudes de contacto pendientes
$solicitudesPendientesQuery = "SELECT COUNT(*) AS pendientes FROM ContactoCliente WHERE Estado = 'Pendiente'";
$resultSolicitudes = mysqli_query($conn, $solicitudesPendientesQuery);
$solicitudesPendientes = mysqli_fetch_assoc($resultSolicitudes)['pendientes'];

// Inicializar variables de búsqueda y filtro
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

// Construir la consulta
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


// Filtros adicionales
if (!empty($searchTerm)) {
    $sql .= " AND (Empresa.NombreEmpresa LIKE '%$searchTerm%' 
                  OR Empleados.Nombre LIKE '%$searchTerm%' 
                  OR Equipos.NParte LIKE '%$searchTerm%' 
                  OR Equipos.Modelo LIKE '%$searchTerm%'
                  OR Equipos.NSerie LIKE '%$searchTerm%'
                  OR Citas.Dia LIKE '%$searchTerm%'
                  OR Citas.Folio LIKE '%$searchTerm%')";
}
if (!empty($fechaInicio) && !empty($fechaFin)) {
    $sql .= " AND Citas.Dia BETWEEN '$fechaInicio' AND '$fechaFin'";
}

// Ejecutar la consulta
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conn));
}

$datos = [];
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['Estatus'] === 'En espera' && $row['HoraInicio']) {
        $horaInicio = new DateTime($row['HoraInicio']);
        $ahora = new DateTime();
        $intervalo = $horaInicio->diff($ahora);
        $row['Metrica'] = $intervalo->format('%H:%I:%S');
    }
    $datos[] = $row;
}

// Contar las citas totales
$totalCitas = count($datos);

// Calcular el tiempo total de todas las citas
$totalTimeQuery = "
    SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(CAST(Citas.Metrica AS TIME)))) AS TiempoTotal
    FROM Citas
";
$resultTotalTime = mysqli_query($conn, $totalTimeQuery);
$rowTotalTime = mysqli_fetch_assoc($resultTotalTime);
$totalTime = isset($rowTotalTime['TiempoTotal']) ? $rowTotalTime['TiempoTotal'] : '00:00:00';

// Calcular el promedio de tiempo
if ($totalCitas > 0) {
    $timeInSecondsQuery = "
        SELECT SUM(TIME_TO_SEC(CAST(Citas.Metrica AS TIME))) / $totalCitas AS PromedioSegundos
        FROM Citas
    ";
    $resultTimeInSeconds = mysqli_query($conn, $timeInSecondsQuery);
    $rowTimeInSeconds = mysqli_fetch_assoc($resultTimeInSeconds);
    $averageTimeInSeconds = isset($rowTimeInSeconds['PromedioSegundos']) ? $rowTimeInSeconds['PromedioSegundos'] : 0;

    $averageTime = gmdate("H:i:s", $averageTimeInSeconds); // Convertir a formato H:M:S
} else {
    $averageTime = '00:00:00'; // Sin citas, el promedio es 0
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/citasusuarios.css">
</head>
<body>
<aside class="sidebar bg-dark text-white p-3">
    <!-- Botón de colapso/despliegue -->
    <div class="toggle-btn">
        <button id="toggleSidebar" class="btn btn-dark"><i class="fas fa-bars"></i></button>
    </div>
    <!-- Logo -->
    <div class="logo">
        <img src="uploads/logocortoblanco.png" alt="Logo de Intercovamex">
    </div>
    <!-- Perfil del usuario -->
    <div class="user-profile text-center">
        <p class="welcome-text">Intercovamex</p>
        <img src="uploads/<?php echo $user['Foto']; ?>" alt="Foto de usuario" class="img-thumbnail rounded-circle mb-3" style="width: 80px;">
        <h4><?php echo $user['Nombre'] . ' ' . $user['Apellido']; ?></h4>
        <p><?php echo $user['NombreRol']; ?></p>
        <p class="small"><?php echo $user['Email']; ?></p>
        <p class="small"><?php echo $user['Puesto']; ?></p>
        <a href="logout.php" class="btn btn-danger btn-sm mt-2">Cerrar Sesión</a>
    </div>
    <!-- Navegación -->
    <nav class="mt-4">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="equipos_usuarios.php" class="nav-link text-white"><i class="fas fa-cogs"></i> Gestión de Equipos</a>
        </li>
        <!-- Menú desplegable para "Gestión de Citas" -->
        <li class="nav-item dropdown">
            <a href="#" class="nav-link text-white dropdown-toggle active" id="gestorCitasMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-calendar-alt"></i> Gestión de Citas
            </a>
            <div class="dropdown-menu" aria-labelledby="gestorCitasMenu">
                <a class="dropdown-item"  id="crearCita"><i class="fas fa-plus"></i> Crear Nueva Cita</a>
                <a class="dropdown-item"  id="editarCita"><i class="fas fa-edit"></i> Editar Cita</a>
                <a class="dropdown-item"  id="eliminarCita"><i class="fas fa-trash"></i> Eliminar Cita</a>
                <a class="dropdown-item"  id="consultarCita"><i class="fas fa-eye"></i> Consultar Cita</a>
                <a class="dropdown-item"  id="proponerCita"><i class="fas fa-calendar-plus"></i> Proponer Nueva Cita</a>
                <a class="dropdown-item"  id="historialCita"><i class="fas fa-history"></i> Historial de Cita</a>
                <a class="dropdown-item"  id="correoCita"><i class="fas fa-envelope"></i> Correo</a>
            </div>
        </li>
        <li class="nav-item">
            <a href="administrar_contactos.php" class="nav-link text-white position-relative">
                <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                <?php if ($solicitudesPendientes > 0): ?>
                    <span class="badge badge-danger position-absolute" style="top: 0; right: 10px;"><?php echo $solicitudesPendientes; ?></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>
</nav>


</aside>


<main class="content p-4">
    <div class="container-fluid">
        <header class="header-title">
            <h2>Gestión de Citas</h2>
        </header>
        <div class="search-buttons-container">
            <div class="buttons-left">
                <!-- Botón visible en la página -->
                <a href="citas_crear.php" class="btn btn-primary"><i class="fas fa-plus"></i> Crear Nueva Cita</a>
            </div>
            <form action="" method="GET" class="search-form">
                <input type="text" name="search" class="form-control mr-2 rounded-pill" placeholder="Buscar por cliente, empleado, equipo, día o folio" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <label for="fechaInicio">Fecha Inicio:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" value="<?php echo htmlspecialchars($fechaInicio); ?>" class="form-control mr-2">
                <label for="fechaFin">Fecha Fin:</label>
                <input type="date" id="fechaFin" name="fechaFin" value="<?php echo htmlspecialchars($fechaFin); ?>" class="form-control mr-2">
                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-search"></i> Buscar</button>
            </form>
            <div class="counter">
                Total Citas: <?php echo $totalCitas; ?><br>
                Tiempo Promedio: <?php echo $averageTime; ?>
            </div>
        </div>
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Seleccionar</th>
                    <th>Folio</th>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Parte</th>
                    <th>Modelo</th>
                    <th>Número de Serie</th>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Empleado</th>
                    <th>Estado</th>
                    <th>Métrica</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($totalCitas === 0): ?>
                    <tr><td colspan="13">No hay citas disponibles.</td></tr>
                <?php else: ?>
                    <?php foreach ($datos as $row): ?>
                    <tr>
                        <td><input type="radio" name="selectedRecord" value="<?php echo $row['Id']; ?>" class="select-record"></td>
                        <td><?php echo $row['Folio']; ?></td>
                        <td><?php echo $row['Dia']; ?></td>
                        <td><?php echo $row['Hora']; ?></td>
                        <td><?php echo $row['NParte']; ?></td>
                        <td><?php echo $row['Modelo']; ?></td>
                        <td><?php echo $row['NSerie']; ?></td>
                        <td><?php echo $row['Cliente']; ?></td>
                        <td><?php echo $row['Contacto']; ?></td>
                        <td><?php echo $row['Empleado']; ?></td>
                        <td><?php echo $row['Estado']; ?></td>
                        <td><span id="metrica-<?php echo $row['Id']; ?>"><?php echo $row['Metrica']; ?></span></td>
                        <td><span id="estatus-<?php echo $row['Id']; ?>"><?php echo $row['Estatus']; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
        <!-- Paginación fija en la parte inferior -->
    <div class="pagination-container">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled">
                    <span class="page-link">Página</span>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">6</a></li>
                <li class="page-item"><a class="page-link" href="#">&#187;</a></li> <!-- Última página -->
            </ul>
        </nav>
        <p class="results-info">Mostrando resultados del 1 al 10 de 201</p>
    </div>

<script src="js/metrica.js"></script>
<script src="js/accionescitas.js"></script>
<script src="js/aside.js"></script>
<script src="js/mostrarmenu.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>