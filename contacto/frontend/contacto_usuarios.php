<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/auth.php');
include('../../solicitudes_logic.php');
include('../backend/backend_usuarios.php');
// Obtener las solicitudes pendientes y datos del usuario
$solicitudesPendientes = obtenerSolicitudesPendientes($conn);
$user = getLoggedInUser($conn, $_SESSION['usuarioId']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Contactos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="stylesheet" href="../../css/crudusuarios.css"> <!-- Archivo CSS personalizado -->
</head>
<body>
    <!-- Menú lateral -->
    <aside class="sidebar  p-3">
        <div class="toggle-btn">
            <button id="toggleSidebar" class="btn"><i class="fas fa-bars"></i></button>
        </div>
        <div class="logo">
            <img src="../../uploads/logocortoblanco.png" alt="Logo de Intercovamex">
        </div>
        <div class="user-profile text-center">
    <p class="welcome-text">Intercovamex</p>
    <h4><?php echo $user['Nombre'] . ' ' . $user['Apellido']; ?></h4>
    <a href="../../logout.php" class="btn logout-btn mt-2">Cerrar Sesión</a>
</div>

        <nav class="mt-4">
    <ul class="nav flex-column">
    <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
      <!-- Dashboard -->
      <li class="nav-item">
            <a href="../../dashboard/frontend/dashboard.php" class="nav-link text-white">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>
        <?php endif; ?>
        <!-- Gestión de Equipos -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
            <li class="nav-item">
                <a href="../../equipos/frontend/equipos_usuarios.php" class="nav-link text-white"><i class="fas fa-cogs"></i> Gestión de Equipos</a>
            </li>
        <?php endif; ?>

        <!-- Gestión de Empresas -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
            <li class="nav-item">
                <a href="../../empresa/frontend/empresa_usuarios.php" class="nav-link text-white"><i class="fas fa-building"></i> Gestión de Empresas</a>
            </li>
        <?php endif; ?>

        <!-- Gestión de Empleados -->
        <?php if ($_SESSION['rol'] == 1): ?>
            <li class="nav-item">
                <a href="../../Empleados/frontend/empleados_usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie"></i> Gestión de Empleados</a>
            </li>
        <?php endif; ?>

        <!-- Gestión de Citas -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
            <li class="nav-item">
                <a href="../../citas/frontend/citas_usuarios.php" class="nav-link text-white"><i class="fas fa-calendar-alt"></i> Gestión de Citas</a>
            </li>
        <?php endif; ?>

        <!-- Gestión de Solicitudes Clientes -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
            <li class="nav-item">
                <a href="../../solicitudes/frontend/administrar_contactos.php" class="nav-link text-white position-relative">
                    <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                    <?php if ($solicitudesPendientes > 0): ?>
                        <span class="badge badge-danger position-absolute" style="top: 0; right: 10px;"><?php echo $solicitudesPendientes; ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endif; ?>

        <!-- Gestión de Contacto -->
        <li class="nav-item">
            <a href="../../contacto/frontend/contacto_usuarios.php" class="nav-link text-white dropdown-toggle" id="menuContacto">
                <i class="fas fa-phone"></i> Contacto
            </a>
            <ul class="dropdown-menu">
                <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
                    <li><a href="#" class="dropdown-item" id="crearContacto"><i class="fas fa-plus"></i> Crear Contacto</a></li>
                    <li><a href="#" class="dropdown-item" id="editarContacto"><i class="fas fa-edit"></i> Editar Contacto</a></li>
                <?php endif; ?>
                <?php if ($_SESSION['rol'] == 1): ?>
                    <li><a href="#" class="dropdown-item" id="eliminarContacto"><i class="fas fa-trash"></i> Eliminar Contacto</a></li>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
                    <li><a href="#" class="dropdown-item" id="consultarContacto"><i class="fas fa-eye"></i> Consultar Contacto</a></li>
                <?php endif; ?>
            </ul>
        </li>
    </ul>
</nav>

    </aside>

    <main class="content p-4">
    <div class="container-fluid">
        <header class="header-title">
            <h2>Gestión de Contactos</h2>
        </header>

        <!-- Contenedor para búsqueda, filtro de estatus y paginación en la misma línea -->
        <div class="search-buttons-container d-flex justify-content-between align-items-center w-100 mb-4">
            <!-- Botón Añadir Nuevo Contacto -->
            <div class="buttons-left">
                <?php if (in_array($_SESSION['rol'], [1, 5])): ?>
                    <a href="contacto_crear.php" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Añadir Nuevo Contacto</a>
                <?php endif; ?>
            </div>

            <!-- Formulario de búsqueda -->
            <form action="contacto_usuarios.php" method="GET" class="search-form d-flex align-items-center mx-auto">
                <input type="text" name="search" class="form-control mr-2 rounded-pill" placeholder="Buscar contacto..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-search"></i> Buscar</button>
            </form>

            <!-- Contador de Contactos -->
            <div class="counter text-right ml-3">
                Total Contactos: <strong><?php echo $totalRecords; ?></strong>
            </div>
        </div>

        <!-- Tabla de Contactos -->
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Puesto</th>
                    <th>Estado</th>
                    <th>Empresa</th>
                    <th>Usuario Final</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contactos as $contacto): ?>
                    <tr>
                        <td>
                            <input type="radio" name="selectedRecord" value="<?php echo $contacto['Id']; ?>" class="select-record">
                        </td>
                        <td><?php echo htmlspecialchars($contacto['Email']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['Apellido']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['Telefono']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['Puesto']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['Estado']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['NombreEmpresa'] ?? 'Sin asignar'); ?></td>
                        <td><?php echo htmlspecialchars($contacto['UsuarioFinal'] === 'Sí' ? 'Sí' : 'No'); ?></td>
                        <td><?php echo htmlspecialchars($contacto['NombreRol']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
     <!-- Paginación -->
     <div class="pagination-container">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchTerm; ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $searchTerm; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
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
            <!-- Copyright -->
<div class="Copyright">
        Copyright © 2025 INTERCOVAMEX S.A. DE C.V. ~ Powered by INTERCOVAMEX
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        
    const urlParams = new URLSearchParams(window.location.search);

    // Si hay un parámetro 'added' que indica que la acción fue realizada correctamente (crear)
    if (urlParams.has('added') && urlParams.get('added') === 'true') {
        Swal.fire({
            icon: 'success',
            title: '¡Acción realizada!',
            text: 'El contacto ha sido creado correctamente.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Si hay un parámetro 'edited' que indica que el contacto fue editado correctamente
    if (urlParams.has('edited') && urlParams.get('edited') === 'true') {
        Swal.fire({
            icon: 'success',
            title: '¡Contacto editado!',
            text: 'El contacto ha sido editado correctamente.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Si hay un parámetro 'deleted' que indica que el contacto fue eliminado correctamente
    if (urlParams.has('deleted') && urlParams.get('deleted') === 'true') {
        Swal.fire({
            icon: 'success',
            title: '¡Contacto eliminado!',
            text: 'El contacto ha sido eliminado correctamente.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Si hay un parámetro 'consulted' que indica que el contacto fue consultado correctamente
    if (urlParams.has('consulted') && urlParams.get('consulted') === 'true') {
        Swal.fire({
            icon: 'info',
            title: 'Consulta realizada',
            text: 'La información del contacto ha sido consultada correctamente.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Si hay un parámetro 'error' que indica que hubo un error en la acción
    if (urlParams.has('error') && urlParams.get('error') === 'true') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al realizar la acción. Por favor, intenta nuevamente.',
            showConfirmButton: true,
        });
    }
</script>

    <script src="../../js/aside.js"></script>
    <script src="../../js/mostrarmenu.js"></script>
    <script src="../../js/acciones_contacto.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>