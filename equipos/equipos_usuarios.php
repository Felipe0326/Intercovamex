<?php
include('../db.php'); // Conexión a la base de datos
include('../auth.php'); // Verifica la sesión y roles
include('../solicitudes_logic.php');
include('backend_usuarios.php');

// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$user = getLoggedInUser($conn, $usuarioId);

// Consultar el número de solicitudes pendientes
$solicitudesPendientes = obtenerSolicitudesPendientes($conn);

// Obtener equipos con filtros
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$result = getEquipos($conn, $searchTerm, $statusFilter, $offset, $limit);
$totalEquipos = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Equipos</title>
    <!-- Importación de Bootstrap y CSS personalizado -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/crudusuarios.css"> <!-- Archivo CSS personalizado -->
</head>
<body>
  <!-- Menú lateral -->
  <aside class="sidebar p-3">
        <!-- Botón de colapso/despliegue -->
        <div class="toggle-btn">
            <button id="toggleSidebar" class="btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="logo">
            <img src="../uploads/logocortoblanco.png" alt="Logo de Intercovamex">
        </div>
        <div class="user-profile text-center">
    <p class="welcome-text">Intercovamex</p>
    <h4><?php echo $user['Nombre'] . ' ' . $user['Apellido']; ?></h4>
    <a href="../logout.php" class="btn logout-btn mt-2">Cerrar Sesión</a>
</div>

        <nav class="mt-4">
    <ul class="nav flex-column">
        <!-- Gestión de Equipos -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item">
            <a href="#../equipos/equipos_usuarios.php" class="nav-link dropdown-toggle" id="menuEquipos">
                <i class="fas fa-cogs"></i> Gestión de Equipos
            </a>
            <ul class="dropdown-menu">
                <!-- Crear y Editar: Solo para Administrador (1) y Coordinador (5) -->
                <?php if (in_array($_SESSION['rol'], [1, 5])): ?>
                    <li><a href="#" class="dropdown-item crear" id="crearEquipo"><i class="fas fa-plus"></i> Añadir Nuevo Equipo</a></li>
                    <li><a href="#" class="dropdown-item editar" id="editarEquipo"><i class="fas fa-edit"></i> Editar Equipos</a></li>
                <?php endif; ?>
                <!-- Eliminar: Solo para Administrador (1) -->
                <?php if ($_SESSION['rol'] == 1): ?>
                    <li><a href="#" class="dropdown-item eliminar" id="eliminarEquipo"><i class="fas fa-trash"></i> Eliminar Equipos</a></li>
                <?php endif; ?>
                <!-- Consultar: Para roles 1, 3, 4, 5 -->
                <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
                    <li><a href="#" class="dropdown-item consultar" id="consultarEquipo"><i class="fas fa-eye"></i> Consultar Equipos</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <!-- Gestión de Empresas (Acceso para roles 1, 3, 4, 5) -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item"><a href="../empresa/empresa_usuarios.php" class="nav-link empresa"><i class="fas fa-building"></i> Gestión de Empresas</a></li>
        <?php endif; ?>

        <!-- Gestión de Empleados (Solo Administrador) -->
        <?php if ($_SESSION['rol'] == 1): ?>
        <li class="nav-item"><a href="../Empleados/empleados_usuarios.php" class="nav-link empleados"><i class="fas fa-user-tie"></i> Gestión de Empleados</a></li>
        <?php endif; ?>

        <!-- Gestión de Citas (Acceso para roles 1, 3, 4, 5) -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item"><a href="../citas/citas_usuarios.php" class="nav-link citas"><i class="fas fa-calendar-alt"></i> Gestión de Citas</a></li>
        <?php endif; ?>

        <!-- Gestión de Solicitudes Clientes (Solo para roles 1, 3, 5) -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
        <li class="nav-item">
            <a href="../solicitudes/administrar_contactos.php" class="nav-link solicitudes">
                <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                <?php if ($solicitudesPendientes > 0): ?>
                    <span class="badge position-absolute"><?php echo $solicitudesPendientes; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php endif; ?>

        <!-- Contacto (Acceso para roles 1, 3, 4, 5) -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item"><a href="../contacto/contacto_usuarios.php" class="nav-link contacto"><i class="fas fa-phone"></i> Contacto</a></li>
        <?php endif; ?>

        <!-- Dashboard (Disponible para todos los roles) -->
        <li class="nav-item">
            <a href="../dashboard/dashboard.php" class="nav-link">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>
        
    </ul>
</nav>


    </aside>

    <!-- Contenido principal -->
<main class="content p-4">
    <div class="container-fluid">
        <header class="header-title">
            <h2>Gestión de Equipos</h2>
        </header>

        <!-- Contenedor para búsqueda y filtro de estatus -->
        <div class="search-buttons-container">
            <div class="buttons-left">
                <?php if (in_array($_SESSION['rol'], [1, 5])): ?>
                <a href="equipos_crear.php" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Añadir Nuevo Equipo</a>
                <?php endif; ?>
            </div>
            <form action="equipos_usuarios.php" method="GET" class="search-form">
                <input type="text" name="search" class="form-control mr-2 rounded-pill" placeholder="Buscar equipos..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <label for="status" class="mr-2">Filtrar por estatus:</label>
                <select id="status" name="status" class="form-control mr-2 rounded-pill">
                    <option value="">Todos</option>
                    <option value="Activo" <?php if ($statusFilter == 'Activo') echo 'selected'; ?>>Activo</option>
                    <option value="Pendiente" <?php if ($statusFilter == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="Finalizado" <?php if ($statusFilter == 'Finalizado') echo 'selected'; ?>>Finalizado</option>
                </select>
                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-search"></i> Buscar</button>
            </form>

            <!-- Contador de equipos -->
            <div class="counter">
                Total Equipos: <?php echo $totalEquipos; ?>
            </div>
        </div>

        <!-- Contenedor de la tabla con espacio para la paginación -->
        <div class="table-wrapper">
            <!-- Tabla de Equipos -->
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Parte</th>
                        <th>Modelo</th>
                        <th>Número de Serie</th>
                        <th>Marca</th>
                        <th>Empresa</th>
                        <th>Observaciones</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr data-id="<?php echo $row['Id']; ?>">
                            <td>
                                <input type="radio" name="selectedRecord" value="<?php echo $row['Id']; ?>" class="select-record">
                            </td>
                            <td><?php echo $row['NParte']; ?></td>
                            <td><?php echo $row['Modelo']; ?></td>
                            <td><?php echo $row['NSerie']; ?></td>
                            <td><?php echo $row['Marca']; ?></td>
                            <td><?php echo $row['NombreEmpresa']; ?></td>
                            <td><?php echo $row['Observaciones']; ?></td>
                            <td><?php echo $row['Estatus']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Paginación -->
<div class="pagination-container">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Botón Anterior -->
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchTerm; ?>&status=<?php echo $statusFilter; ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Números de Páginas -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $searchTerm; ?>&status=<?php echo $statusFilter; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchTerm; ?>&status=<?php echo $statusFilter; ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <p class="results-info">
        Mostrando registros <?php echo $offset + 1; ?> a <?php echo min($offset + $limit, $totalRecords); ?> de <?php echo $totalRecords; ?>
    </p>

    <!-- Copyright -->
    <div class="Copyright">
        Copyright © 2025 INTERCOVAMEX S.A. DE C.V. ~ Powered by INTERCOVAMEX
    </div>
</div>
    <script src="../js/accionesequipos.js"></script>
    <script src="../js/aside.js"></script>
    <script src="../js/mostrarmenu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>




