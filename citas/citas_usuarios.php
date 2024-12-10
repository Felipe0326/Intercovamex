<?php
include('../db.php'); // Conexión a la base de datos
include('../auth.php');
include('../solicitudes_logic.php');
include('backend_usuarios.php'); // Archivo backend con la lógica de citas

// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$user = getLoggedInUser($conn, $usuarioId);
$solicitudesPendientes = obtenerSolicitudesPendientes($conn);


// Obtener datos de citas desde el backend
$totalRecords = obtenerTotalCitas($conn, $searchTerm, $fechaInicio, $fechaFin);
$totalPages = ceil($totalRecords / $limit);
$citas = ejecutarConsultaCitas($conn, construirConsultaCitas($searchTerm, $fechaInicio, $fechaFin, $offset, $limit));
$tiempos = calcularTiemposCitas($conn);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/crudusuarios.css">
</head>
<body>
<aside class="sidebar  p-3">
    <!-- Botón de colapso/despliegue -->
    <div class="toggle-btn">
        <button id="toggleSidebar" class="btn"><i class="fas fa-bars"></i></button>
    </div>
    <!-- Logo -->
    <div class="logo">
        <img src="../uploads/logocortoblanco.png" alt="Logo de Intercovamex">
    </div>
    <!-- Perfil del usuario -->
    <div class="user-profile text-center">
    <p class="welcome-text">Intercovamex</p>
    <h4><?php echo $user['Nombre'] . ' ' . $user['Apellido']; ?></h4>
    <a href="../logout.php" class="btn logout-btn mt-2">Cerrar Sesión</a>
</div>

    <!-- Navegación -->
    <nav class="mt-4">
    <ul class="nav flex-column">
        <!-- Gestión de Equipos -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item">
            <a href="../equipos/equipos_usuarios.php" class="nav-link text-white"><i class="fas fa-cogs"></i> Gestión de Equipos</a>
        </li>
        <?php endif; ?>

        <!-- Gestión de Empresas -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item">
            <a href="../empresa/empresa_usuarios.php" class="nav-link text-white"><i class="fas fa-building"></i> Gestión de Empresas</a>
        </li>
        <?php endif; ?>

        <!-- Gestión de Empleados -->
        <?php if ($_SESSION['rol'] == 1): ?>
        <li class="nav-item">
            <a href="../Empleados/empleados_usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie"></i> Gestión de Empleados</a>
        </li>
        <?php endif; ?>

        <!-- Gestión de Citas -->
     
        <li class="nav-item dropdown">
            <a href="#" class="nav-link text-white dropdown-toggle active" id="gestorCitasMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-calendar-alt"></i> Gestión de Citas
            </a>
            <div class="dropdown-menu" aria-labelledby="gestorCitasMenu">
                <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
                    <a href="#" class="dropdown-item" id="crearCita"><i class="fas fa-plus"></i> Crear Nueva Cita</a>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], [1, 5])): ?>
                    <a href="#" class="dropdown-item" id="editarCita"><i class="fas fa-edit"></i> Editar Cita</a>
                <?php endif; ?>
                <?php if ($_SESSION['rol'] == 1): ?>
                    <a href="#" class="dropdown-item" id="eliminarCita"><i class="fas fa-trash"></i> Eliminar Cita</a>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
                    <a href="#" class="dropdown-item" id="consultarCita"><i class="fas fa-eye"></i> Consultar Cita</a>
                    <a href="#" class="dropdown-item" id="proponerCita"><i class="fas fa-calendar-plus"></i> Proponer Nueva Cita</a>
                    <a href="#" class="dropdown-item" id="correoCita"><i class="fas fa-envelope"></i> Correo</a>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
                    <a href="#" class="dropdown-item" id="historialCita"><i class="fas fa-history"></i> Historial de Cita</a>
                <?php endif; ?>
            </div>
        </li>

        <!-- Gestión de Solicitudes -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
        <li class="nav-item">
            <a href="../solicitudes/administrar_contactos.php" class="nav-link text-white position-relative">
                <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                <?php if ($solicitudesPendientes > 0): ?>
                    <span class="badge badge-danger position-absolute"><?php echo $solicitudesPendientes; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php endif; ?>

        <!-- Contacto -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item">
            <a href="../contacto/contacto_usuarios.php" class="nav-link text-white"><i class="fas fa-phone"></i> Contacto</a>
        </li>
        <?php endif; ?>

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="../dashboard/dashboard.php" class="nav-link text-white">
                <i class="fas fa-chart-line"></i> Dashboard
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
                <a href="citas_crear.php" class="btn btn-primary"><i class="fas fa-plus"></i> Crear Nueva Cita</a>
            </div>
            <form action="citas_usuarios.php" method="GET" class="search-form">
                <input type="text" name="search" class="form-control mr-2 rounded-pill" placeholder="Buscar por cliente, empleado, equipo, día o folio" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <label for="fechaInicio">Fecha Inicio:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" class="form-control mr-2" value="<?php echo htmlspecialchars($fechaInicio); ?>">
                <label for="fechaFin">Fecha Fin:</label>
                <input type="date" id="fechaFin" name="fechaFin" class="form-control mr-2" value="<?php echo htmlspecialchars($fechaFin); ?>">
                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-search"></i> Buscar</button>
            </form>
            <div class="counter">
                Total Citas: <?php echo $totalRecords; ?><br>
                Tiempo Promedio: <?php echo $tiempos['promedio']; ?>
            </div>
        </div>
        <table class="table table-striped table-hover table-bordered">
            <thead>
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
                <?php if (count($citas) === 0): ?>
                    <tr><td colspan="13">No hay citas disponibles.</td></tr>
                <?php else: ?>
                    <?php foreach ($citas as $row): ?>
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

<div class="pagination-container">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchTerm; ?>&fechaInicio=<?php echo $fechaInicio; ?>&fechaFin=<?php echo $fechaFin; ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $searchTerm; ?>&fechaInicio=<?php echo $fechaInicio; ?>&fechaFin=<?php echo $fechaFin; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchTerm; ?>&fechaInicio=<?php echo $fechaInicio; ?>&fechaFin=<?php echo $fechaFin; ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <p class="results-info">
        Mostrando registros <?php echo $offset + 1; ?> a <?php echo min($offset + $limit, $totalRecords); ?> de <?php echo $totalRecords; ?>
    </p>
</div>

<script src="../js/metrica.js"></script>
<script src="../js/aside.js"></script>
<script src="../js/mostrarmenu.js"></script>
<script src="../js/accionescitas.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


