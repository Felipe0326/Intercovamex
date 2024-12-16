<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/authadmin.php');
include('../../solicitudes_logic.php');
include('../backend/backend_usuarios.php');

// Obtener los datos del usuario logueado
$solicitudesPendientes = obtenerSolicitudesPendientes($conn);
$user = getLoggedInUser($conn, $_SESSION['usuarioId']);


// Obtener lista de empleados según los filtros
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;
$roleFilter = isset($_GET['role']) ? $_GET['role'] : null;
$result = obtenerEmpleados($conn, $searchTerm, $roleFilter);

// Validar si hay resultados en caso de error
if (!$result) {
    die("Error al obtener empleados: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="../../css/crudusuarios.css">
</head>
<body>
    <!-- Menú lateral -->
    <aside class="sidebar  p-3">
    <!-- Botón de colapso/despliegue -->
    <div class="toggle-btn">
        <button id="toggleSidebar" class="btn "><i class="fas fa-bars"></i></button>
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
        <!-- Menú principal -->
        <?php if (in_array($_SESSION['rol'], [1])): ?>
        <li class="nav-item"><a href="../../equipos/frontend/equipos_usuarios.php" class="nav-link text-white"><i class="fas fa-cogs"></i> Gestión de Equipos</a></li>
        <li class="nav-item"><a href="../../empresa/frontend/empresa_usuarios.php" class="nav-link text-white"><i class="fas fa-building"></i> Gestión de Empresas</a></li>
        <?php endif; ?>

        <!-- Menú desplegable para "Gestión de Empleados" (solo para Administrador) -->
        <?php if ($_SESSION['rol'] == 1): ?>
        <li class="nav-item">
            <a href="#" class="nav-link text-white dropdown-toggle" id="menuEmpleados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-tie"></i> Gestión de Empleados</a>
            <ul class="dropdown-menu">
                <li><a href="#" id="crearEmpleado" class="dropdown-item"><i class="fas fa-plus"></i> Crear Empleado</a></li>
                <li><a href="#" id="editarEmpleado" class="dropdown-item"><i class="fas fa-edit"></i> Editar Empleado</a></li>
                <li><a href="#" id="eliminarEmpleado" class="dropdown-item"><i class="fas fa-trash"></i> Eliminar Empleado</a></li>
                <li><a href="#" id="consultarEmpleado" class="dropdown-item"><i class="fas fa-eye"></i> Consultar Empleado</a></li>
            </ul>
        </li>
        <?php endif; ?>

        <?php if (in_array($_SESSION['rol'], [1])): ?>
        <li class="nav-item"><a href="../../citas/frontend/citas_usuarios.php" class="nav-link text-white"><i class="fas fa-calendar-alt"></i> Gestión de Citas</a></li>
        <?php endif; ?>

        <?php if (in_array($_SESSION['rol'], [1])): ?>
        <li class="nav-item">
            <a href="../../solicitudes/frontend/administrar_contactos.php" class="nav-link text-white position-relative">
                <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                <?php if ($solicitudesPendientes > 0): ?>
                <span class="badge badge-danger position-absolute"><?php echo $solicitudesPendientes; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php endif; ?>

        <?php if (in_array($_SESSION['rol'], [1])): ?>
        <li class="nav-item"><a href="../../contacto/frontend/contacto_usuarios.php" class="nav-link text-white"><i class="fas fa-phone"></i> Contacto</a></li>
        <?php endif; ?>

        
    </ul>
</nav>

</aside>

<main class="content p-4">
        <div class="container-fluid">
            <header class="header-title">
                <h2>Gestión de Empleados</h2>
            </header>
            
            <!-- Contenedor de búsqueda y contador -->
            <div class="search-buttons-container">
            <div class="buttons-left">
                <?php if (in_array($_SESSION['rol'], [1])): ?>
                    <a href="empleados_crear.php" class="btn btn-primary mr-2">
                        <i class="fas fa-plus"></i> Añadir Empleado
                     </a>
                        <?php endif; ?>
                    </div>
                <form action="empleados_usuarios.php" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control mr-2 rounded-pill" placeholder="Buscar empleados..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <select name="role" class="form-control rounded-pill">
                        <option value="Todos" <?php if ($roleFilter == 'Todos') echo 'selected'; ?>>Todos</option>
                        <option value="Administrador" <?php if ($roleFilter == 'Administrador') echo 'selected'; ?>>Administrador</option>
                        <option value="Vendedor" <?php if ($roleFilter == 'Vendedor') echo 'selected'; ?>>Vendedor</option>
                        <option value="Servicio" <?php if ($roleFilter == 'Servicio') echo 'selected'; ?>>Servicio</option>
                        <option value="Coordinador" <?php if ($roleFilter == 'Coordinador') echo 'selected'; ?>>Coordinador</option>
                    </select>
                    <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-search"></i> Buscar</button>
                </form>

                <div class="counter">
                    Total Empleados: <?php echo $totalRecords; ?>
                </div>
            </div>

            <!-- Tabla de empleados -->
            <table class="table table-striped table-hover table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Nombre de Usuario</th>
                        <th>Email</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Puesto</th>
                        <th>Descripción</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $empleado): ?>
                        <tr>
                            <td>
                                <input type="radio" name="selectedRecord" value="<?php echo $empleado['UsuarioId']; ?>" class="select-record">
                            </td>
                            <td><?php echo htmlspecialchars($empleado['NombreUsuario']); ?></td>
                            <td><?php echo htmlspecialchars($empleado['Email']); ?></td>
                            <td><?php echo htmlspecialchars($empleado['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($empleado['Apellido']); ?></td>
                            <td><?php echo htmlspecialchars($empleado['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($empleado['NombreRol']); ?></td>
                            <td><?php echo htmlspecialchars($empleado['Puesto']); ?></td>
                            <td><?php echo htmlspecialchars($empleado['Descripcion']); ?></td>
                            <td><img src="uploads/<?php echo $empleado['Foto']; ?>" alt="Foto del Empleado" width="100" class="img-thumbnail"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            

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

    
</div>
</div>
<!-- Copyright -->
<div class="Copyright">
        Copyright © 2025 INTERCOVAMEX S.A. DE C.V. ~ Powered by INTERCOVAMEX
    </div>
</body>
</html>
<script>
    const urlParams = new URLSearchParams(window.location.search);

    // Si hay un parámetro 'added' que indica que el empleado fue añadido correctamente
    if (urlParams.has('added') && urlParams.get('added') === 'true') {
        Swal.fire({
            icon: 'success',
            title: '¡Empleado añadido!',
            text: 'El empleado se ha añadido correctamente.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Si hay un parámetro 'edited' que indica que el empleado fue editado correctamente
    if (urlParams.has('edited') && urlParams.get('edited') === 'true') {
        Swal.fire({
            icon: 'success',
            title: '¡Empleado editado!',
            text: 'El empleado se ha editado correctamente.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Si hay un parámetro 'deleted' que indica que el empleado fue eliminado correctamente
    if (urlParams.has('deleted') && urlParams.get('deleted') === 'true') {
        Swal.fire({
            icon: 'success',
            title: '¡Empleado eliminado!',
            text: 'El empleado ha sido eliminado correctamente.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Si hay un parámetro 'consulted' que indica que la información del empleado fue consultada correctamente
    if (urlParams.has('consulted') && urlParams.get('consulted') === 'true') {
        Swal.fire({
            icon: 'info',
            title: 'Consulta realizada',
            text: 'La información del empleado ha sido consultada correctamente.',
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

    <script src="../../js/accionesempleados.js"></script>
    <script src="../../js/aside.js"></script>
    <script src="../../js/mostrarmenu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
