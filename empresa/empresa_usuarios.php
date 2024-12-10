<?php
include('../db.php'); // Conexión a la base de datos
include('../auth.php');
include('../solicitudes_logic.php');
include('backend_usuarios.php');

// Obtener las solicitudes pendientes y datos del usuario
$solicitudesPendientes = obtenerSolicitudesPendientes($conn);
$user = getLoggedInUser($conn, $_SESSION['usuarioId']);
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Empresas</title>
    <!-- Importación de Bootstrap y CSS personalizado -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/crudusuarios.css"> <!-- Archivo CSS personalizado -->
</head>
<body>

    <!-- Menú lateral -->
    <!-- Botón para desplegar/contraer el menú -->
    <aside class="sidebar  p-3">
    <!-- Botón de colapso/despliegue -->
    <div class="toggle-btn">
        <button id="toggleSidebar" class="btn "><i class="fas fa-bars"></i></button>
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
        <!-- Menú principal -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item"><a href="../equipos/equipos_usuarios.php" class="nav-link text-white"><i class="fas fa-cogs"></i> Gestión de Equipos</a></li>
        <?php endif; ?>

        <!-- Menú desplegable para "Gestión de Empresas" -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item">
            <a href="#" class="nav-link text-white dropdown-toggle" id="menuEmpresas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-building"></i> Gestión de Empresas</a>
            <ul class="dropdown-menu">
                <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
                <li><a href="#" class="dropdown-item" id="crearEmpresa"><i class="fas fa-plus"></i> Añadir Nueva Empresa</a></li>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], [1, 5])): ?>
                <li><a href="#" class="dropdown-item" id="editarEmpresa"><i class="fas fa-edit"></i> Editar Empresas</a></li>
                <?php endif; ?>
                <?php if ($_SESSION['rol'] == 1): ?>
                <li><a href="#" class="dropdown-item" id="eliminarEmpresa"><i class="fas fa-trash"></i> Eliminar Empresas</a></li>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
                <li><a href="#" class="dropdown-item" id="consultarEmpresa"><i class="fas fa-eye"></i> Consultar Empresas</a></li>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
                <li><a href="#" class="dropdown-item" id="añadirContacto"><i class="fas fa-user-plus"></i> Añadir Contacto</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if ($_SESSION['rol'] == 1): ?>
        <li class="nav-item"><a href="../Empleados/empleados_usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie"></i> Gestión de Empleados</a></li>
        <?php endif; ?>

        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item"><a href="../citas/citas_usuarios.php" class="nav-link text-white"><i class="fas fa-calendar-alt"></i> Gestión de Citas</a></li>
        <?php endif; ?>

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

        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item"><a href="../contacto/contacto_usuarios.php" class="nav-link text-white"><i class="fas fa-phone"></i> Contacto</a></li>
        <?php endif; ?>

        <!-- Nuevo ítem para Dashboard -->
        <li class="nav-item">
            <a href="../dashboard/dashboard.php" class="nav-link text-white">
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
                <h2>Gestor de Empresas</h2>
            </header>

            <div class="search-buttons-container">
    <div class="buttons-left">
        <a href="empresa_crear.php" class="btn mr-2"><i class="fas fa-plus"></i> Añadir Empresa</a>
        <a href="contacto_usuarios.php" class="btn"><i class="fas fa-user-plus"></i> Añadir Contacto</a>
    </div>
    <form action="empresa_usuarios.php" method="GET" class="search-form">
        <input type="text" name="search" class="form-control mr-2 rounded-pill" placeholder="Buscar empresas..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit" class="btn rounded-pill"><i class="fas fa-search"></i> Buscar</button>
    </form>
    <div class="counter">
        Total Empresas: <?php echo $totalRecords; ?>
    </div>
</div>

<!-- Tabla de Empresas -->
<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            <th>Seleccionar</th>
            <th>Nombre Empresa</th>
            <th>Razón Social</th>
            <th>Dirección Fiscal</th>
            <th>Estado</th>
            <th>RFC</th>
            <th>Código Postal</th>
            <th>Usuario Final</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($empresas as $empresa): ?>
            <tr data-id="<?php echo $empresa['Id']; ?>">
                <td>
                    <input type="radio" name="selectedRecord" value="<?php echo $empresa['Id']; ?>" class="select-record">
                </td>
                <td><?php echo $empresa['NombreEmpresa']; ?></td>
                <td><?php echo $empresa['RazonS']; ?></td>
                <td><?php echo $empresa['DireccionFiscal']; ?></td>
                <td><?php echo $empresa['Estado']; ?></td>
                <td><?php echo $empresa['Rfc']; ?></td>
                <td><?php echo $empresa['CodigoPostal']; ?></td>
                <td><?php echo $empresa['UsuarioFinalNombre'] ? $empresa['UsuarioFinalNombre'] : 'No Asignado'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

        </div>
    </main>

    <div class="pagination-container">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Botón Anterior -->
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchTerm; ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Números de Páginas -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $searchTerm; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchTerm; ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <p class="results-info">
        Mostrando registros <?php echo $offset + 1; ?> a <?php echo min($offset + $limit, $totalRecords); ?> de <?php echo $totalRecords; ?>
    </p>
</div>

    <script src="../js/aside.js"></script>
    <script src="../js/accionesempresa.js"></script>
    <script src="../js/mostrarmenu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>


