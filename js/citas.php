<?php
include('db.php'); // Conexión a la base de datos
session_start();

if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4])) {
    echo "Acceso denegado.";
    exit;
}

// Obtener el ID del contacto
$id = $_GET['id'];

// Actualizar estado a "Aceptado"
$query = "UPDATE ContactoCliente SET Estado = 'Aceptado' WHERE Id = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    // Obtener el folio del contacto aceptado
    $folioQuery = "SELECT Folio, Dia, Hora, ServicioInteres FROM ContactoCliente WHERE Id = $id";
    $folioResult = mysqli_query($conn, $folioQuery);
    $contacto = mysqli_fetch_assoc($folioResult);
    $folio = $contacto['Folio'];
    $dia = $contacto['Dia'];
    $hora = $contacto['Hora'];
    $servicioInteres = $contacto['ServicioInteres'];

    // Insertar la cita en la tabla Citas
    $sql_cita = "INSERT INTO Citas (Dia, Hora, ContactoCliId, ServicioInteres, Estado, Folio) 
                 VALUES (?, ?, ?, ?, 'Propuesta', ?)";
    $stmt_cita = mysqli_prepare($conn, $sql_cita);
    mysqli_stmt_bind_param($stmt_cita, 'ssiss', $dia, $hora, $id, $servicioInteres, $folio);

    if (mysqli_stmt_execute($stmt_cita)) {
        echo "Contacto con Folio $folio aceptado y cita creada exitosamente.";
        // Redirigir a la lista de contactos
        header("Location: administrar_contactos.php");
        exit();
    } else {
        echo "Error al crear la cita: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt_cita);
} else {
    echo "Error al aceptar el contacto: " . mysqli_error($conn);
}

header("Location: administrar_contactos.php");
exit;
?>,cuando  se inserte la cita ,abra forma de que  NParte ,Modelo,NSerie esos campos no estan en la tabla de citas ,pero que los mande a llamar y se muestren en la tabla ,en la tabla de este codigo <?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar acceso
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4])) {
    echo "Acceso denegado.";
    exit;
}

// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$query = "SELECT Empleados.NombreUsuario, Empleados.Nombre, Empleados.Apellido, Empleados.Email, Empleados.Telefono, 
                 Roles.NombreRol, Empleados.Puesto, Empleados.Foto
          FROM Empleados 
          JOIN Roles ON Empleados.RolId = Roles.Id
          WHERE Empleados.Id = $usuarioId";
$resultUser = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($resultUser);

// Consultar si hay solicitudes de contacto pendientes
$solicitudesPendientesQuery = "SELECT COUNT(*) as pendientes FROM ContactoCliente WHERE Estado = 'Pendiente'";
$resultSolicitudes = mysqli_query($conn, $solicitudesPendientesQuery);
$solicitudesPendientes = mysqli_fetch_assoc($resultSolicitudes)['pendientes'];

// Inicializar variables de búsqueda y filtro
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

// Construir la consulta con COALESCE para mostrar los contactos de ContactoCliente solo si están "Aceptados"
$sql = "
    SELECT Citas.Id, Citas.Folio, COALESCE(Citas.Dia, '') AS Dia, COALESCE(Citas.Hora, '') AS Hora, COALESCE(Citas.Estado, '') AS Estado,
           COALESCE(Citas.Metrica, '00:00:00') AS Metrica, COALESCE(Citas.Estatus, 'Espera') AS Estatus,
           COALESCE(Citas.HoraInicio, NULL) AS HoraInicio,
           COALESCE(Equipos.NParte, '') AS NParte, COALESCE(Equipos.Modelo, '') AS Modelo, COALESCE(Equipos.NSerie, '') AS NSerie,
           COALESCE(Empresa.NombreEmpresa, '') AS Cliente, 
           COALESCE(CONCAT(Empleados.Nombre, ' ', Empleados.Apellido), 'No asignado') AS Empleado,
           COALESCE(CONCAT(Contacto.Nombre, ' ', Contacto.Apellido), 'No asignado') AS Contacto,
           '' AS NParteCliente, '' AS ModeloCliente, '' AS NSerieCliente
    FROM Citas
    LEFT JOIN Equipos ON Citas.EquipoId = Equipos.Id
    LEFT JOIN Empresa ON Citas.EmpresaId = Empresa.Id
    LEFT JOIN Empleados ON Citas.EmpleadoId = Empleados.Id
    LEFT JOIN Contacto ON Citas.ContactoId = Contacto.Id
    WHERE 1=1
";

// Agregar segunda parte del UNION ALL
$sql .= "
    UNION ALL
    SELECT Citas.Id, Citas.Folio, COALESCE(Citas.Dia, '') AS Dia, COALESCE(Citas.Hora, '') AS Hora, COALESCE(Citas.Estado, '') AS Estado,
           COALESCE(Citas.Metrica, '00:00:00') AS Metrica, COALESCE(Citas.Estatus, 'Espera') AS Estatus,
           COALESCE(Citas.HoraInicio, NULL) AS HoraInicio,
           COALESCE(Equipos.NParte, '') AS NParte, COALESCE(Equipos.Modelo, '') AS Modelo, COALESCE(Equipos.NSerie, '') AS NSerie,
           COALESCE(Empresa.NombreEmpresa, '') AS Cliente, 
           COALESCE(CONCAT(Empleados.Nombre, ' ', Empleados.Apellido), 'No asignado') AS Empleado,
           COALESCE(CONCAT(ContactoCliente.Nombre, ' ', ContactoCliente.Apellido), 'No asignado') AS Contacto,
           COALESCE(ContactoCliente.NParte, '') AS NParteCliente,
           COALESCE(ContactoCliente.Modelo, '') AS ModeloCliente,
           COALESCE(ContactoCliente.NSerie, '') AS NSerieCliente
    FROM Citas
    LEFT JOIN Equipos ON Citas.EquipoId = Equipos.Id
    LEFT JOIN Empresa ON Citas.EmpresaId = Empresa.Id
    LEFT JOIN Empleados ON Citas.EmpleadoId = Empleados.Id
    LEFT JOIN ContactoCliente ON Citas.ContactoCliId = ContactoCliente.Id
    WHERE ContactoCliente.Estado = 'Aceptado'
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
        <div class="logo">
            <img src="uploads/logolargo.png" alt="Logo de Intercovamex">
        </div>
        <div class="user-profile text-center">
            <p class="welcome-text">Bienvenidos</p>
            <img src="uploads/<?php echo $user['Foto']; ?>" alt="Foto de usuario" class="img-thumbnail rounded-circle mb-3" style="width: 80px;">
            <h4><?php echo $user['Nombre'] . ' ' . $user['Apellido']; ?></h4>
            <p><?php echo $user['NombreRol']; ?></p>
            <p class="small"><?php echo $user['Email']; ?></p>
            <p class="small"><?php echo $user['Puesto']; ?></p>
            <a href="logout.php" class="btn btn-danger btn-sm mt-2">Cerrar Sesión</a>
        </div>
        <nav class="mt-4">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="admin_usuarios.php" class="nav-link text-white"><i class="fas fa-users"></i> Gestión de Usuarios</a></li>
                <li class="nav-item"><a href="equipos_usuarios.php" class="nav-link text-white"><i class="fas fa-cogs"></i> Gestión de Equipos</a></li>
                <li class="nav-item"><a href="empresa_usuarios.php" class="nav-link text-white"><i class="fas fa-building"></i> Gestión de Empresas</a></li>
                <li class="nav-item"><a href="empleados_usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie"></i> Gestión de Empleados</a></li>
                <li class="nav-item"><a href="citas_usuarios.php" class="nav-link text-white active"><i class="fas fa-calendar-alt"></i> Gestión de Citas</a></li>
                <li class="nav-item">
                    <a href="administrar_contactos.php" class="nav-link text-white position-relative">
                        <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                        <?php if ($solicitudesPendientes > 0): ?>
                            <span class="badge badge-danger position-absolute" style="top: 0; right: 10px;"><?php echo $solicitudesPendientes; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item"><a href="contacto_usuarios.php" class="nav-link text-white"><i class="fas fa-phone"></i> Contacto</a></li>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
    <?php if ($totalCitas === 0): ?>
        <tr><td colspan="13">No hay citas disponibles.</td></tr>
    <?php else: ?>
        <?php foreach ($datos as $row): ?>
        <tr>
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
            <td><span id="metrica-<?php echo $row['Id']; ?>"><?php echo $row['Metrica']; ?></span></td> <!-- Aquí se actualiza el cronómetro -->
            <td><span id="estatus-<?php echo $row['Id']; ?>"><?php echo $row['Estatus']; ?></span></td> <!-- Estatus controla la métrica -->
            <td>
                <a href="citas_editar.php?id=<?php echo $row['Id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Editar</a>
                <a href="citas_eliminar.php?id=<?php echo $row['Id']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta cita?');" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</a>
                <a href="citas_consultar.php?id=<?php echo $row['Id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Consultar</a>
                <a href="citas_proponer.php?id=<?php echo $row['Id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-calendar-plus"></i> Proponer Nueva Fecha</a>
                <a href="historial_citas.php?id=<?php echo $row['Id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-history"></i> Ver Historial</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </main>
    <script src="js/metrica.js"></script>
</body>
</html>