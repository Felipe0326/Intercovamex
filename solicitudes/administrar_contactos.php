<?php
include('../db.php'); // Conexión a la base de datos
include('../auth135.php');
include('backend_admincontactos.php'); // Incluye el backend para la lógica
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Contactos de Clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/crudusuarios.css">
</head>
<body>
    <!-- Menú lateral -->
    <aside class="sidebar  p-3">
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
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
        <li class="nav-item">
            <a href="../citas/citas_usuarios.php" class="nav-link text-white"><i class="fas fa-calendar-alt"></i> Gestión de Citas</a>
        </li>
        <?php endif; ?>

        <!-- Gestión de Solicitudes Clientes -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
        <li class="nav-item">
            <a href="administrar_contactos.php" class="nav-link text-white position-relative active">
                <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                <span class="badge badge-danger position-absolute" style="top: 0; right: 10px;"><?php echo $totalContactos; ?></span>
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
                <h2>Gestión de Contactos de Clientes</h2>
            </header>
            <div class="search-buttons-container d-flex align-items-center justify-content-between mb-4">
                <div class="mx-auto">
                    <form method="GET" action="administrar_contactos.php" class="d-flex align-items-center justify-content-center">
                        <label for="estado" class="mr-2">Filtrar por estado:</label>
                        <select name="estado" id="estado" class="form-control rounded-pill" onchange="this.form.submit()">
                            <option value="Todos" <?php if ($estadoFiltro === 'Todos') echo 'selected'; ?>>Todos</option>
                            <option value="Pendiente" <?php if ($estadoFiltro === 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                            <option value="Aceptado" <?php if ($estadoFiltro === 'Aceptado') echo 'selected'; ?>>Aceptado</option>
                            <option value="Rechazado" <?php if ($estadoFiltro === 'Rechazado') echo 'selected'; ?>>Rechazado</option>
                        </select>
                    </form>
                </div>
                <div class="counter bg-light p-2 rounded-pill">
                    Total Contactos: <strong><?php echo $totalContactos; ?></strong>
                </div>
            </div>

            <table class="table table-striped table-hover table-bordered">
            <thead >
        <tr>
            <th>Código Folio</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Servicio de Interés</th>
            <th>Número de Parte</th>
            <th>Modelo</th>
            <th>Número de Serie</th>
            <th>Marca</th>
            <th>Descripción</th>
            <th>Estado</th>
            <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
                <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['CodigoFolio']); ?></td>
            <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
            <td><?php echo htmlspecialchars($row['Apellido']); ?></td>
            <td><?php echo htmlspecialchars($row['Telefono']); ?></td>
            <td><?php echo htmlspecialchars($row['Correo']); ?></td>
            <td><?php echo htmlspecialchars($row['ServicioInteres']); ?></td>
            <td><?php echo htmlspecialchars($row['NParte']); ?></td>
            <td><?php echo htmlspecialchars($row['Modelo']); ?></td>
            <td><?php echo htmlspecialchars($row['NSerie']); ?></td>
            <td><?php echo htmlspecialchars($row['Marca']); ?></td>
            <td><?php echo htmlspecialchars($row['Descripcion']); ?></td>
            <td><?php echo htmlspecialchars($row['Estado']); ?></td>
            <?php if (in_array($_SESSION['rol'], [1, 3, 5])): ?>
                <td class="actions">
                    <?php if ($row['Estado'] === 'Pendiente') { ?>
                        <a href="aprobar_contacto.php?id=<?php echo $row['Id']; ?>" class="btn btn-success btn-sm">Aceptar</a>
                        <a href="rechazar_contacto.php?id=<?php echo $row['Id']; ?>" class="btn btn-danger btn-sm">Rechazar</a>
                    <?php } ?>
                </td>
            <?php endif; ?>
        </tr>
        <?php } ?>
    </tbody>
</table>

        </div>
    </main>
    <script src="../js/aside.js"></script>
    <script src="../js/mostrarmenu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

