<?php
include('../db.php'); // Conexión a la base de datos
include('../auth.php');
include('../solicitudes_logic.php');
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
    <link rel="stylesheet" href="../css/crudusuarios.css"> <!-- Archivo CSS personalizado -->
</head>
<body>
    <!-- Menú lateral -->
    <aside class="sidebar  p-3">
        <div class="toggle-btn">
            <button id="toggleSidebar" class="btn btn-dark"><i class="fas fa-bars"></i></button>
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
        <li class="nav-item"><a href="../equipos/equipos_usuarios.php" class="nav-link text-white"><i class="fas fa-cogs"></i> Gestión de Equipos</a></li>
        <li class="nav-item"><a href="../empresa/empresa_usuarios.php" class="nav-link text-white"><i class="fas fa-building"></i> Gestión de Empresas</a></li>
        <li class="nav-item"><a href="../Empleados/empleados_usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie"></i> Gestión de Empleados</a></li>
        <li class="nav-item"><a href="../citas/citas_usuarios.php" class="nav-link text-white"><i class="fas fa-calendar-alt"></i> Gestión de Citas</a></li>
        <li class="nav-item">
            <a href="../solicitudes/administrar_contactos.php" class="nav-link text-white position-relative">
                <i class="fas fa-envelope"></i> Gestión de Solicitudes Clientes
                <?php if ($solicitudesPendientes > 0): ?>
                    <span class="badge badge-danger position-absolute" style="top: 0; right: 10px;"><?php echo $solicitudesPendientes; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="../contacto/contacto_usuarios.php" class="nav-link text-white dropdown-toggle" id="menuContacto"><i class="fas fa-phone"></i> Contacto</a>
        </li>
        <!-- Nuevo ítem para Dashboard -->
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
                <h2>Dashboard</h2>
                <p>Proximamente</p>
            </header>
            <!-- Aquí puedes añadir contenido o notificaciones, si lo necesitas -->
            <p class="text-center"></p>
        </div>
    </main>
    <script src="../js/aside.js"></script>
    <script src="../js/mostrarmenu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
