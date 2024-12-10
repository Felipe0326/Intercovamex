<?php
include('../db.php');
include('../authcliente.php'); // Verifica sesión y roles
include('backend_citashistorial.php');

// Obtener el ID del cliente logueado desde la sesión
$usuarioId = $_SESSION['usuarioId'];
$folio = isset($_GET['folio']) ? $_GET['folio'] : '';
$cita = getCitaByFolio($conn, $folio);
 
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
    <link rel="stylesheet" href="../css/historialcitas.css"> <!-- Archivo CSS personalizado -->
</head>

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
            <h4><?php echo $clienteData['Nombre'] . ' ' . $clienteData['Apellido']; ?></h4>
            <a href="../logout.php" class="btn logout-btn mt-2">Cerrar Sesión</a>
        </div>

        <!-- Menú de navegación -->
        <nav class="mt-4">
            <ul class="nav flex-column">
                
                    <li class="nav-item">
                        <a href="../contactoclientes/contactos_clientes.php" class="nav-link">
                            <i class="fas fa-address-book"></i> Contacto Cliente
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../citasclientes/citas_clienteslogin.php" class="nav-link">
                            <i class="fas fa-calendar-alt"></i> Gestión de Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../contactoclientes/historial_citasclientes.php" class="nav-link">
                            <i class="fas fa-history"></i> Historial de Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../citasclientes/citas_clientesequipos.php" class="nav-link">
                            <i class="fas fa-tools"></i> Citas Clientes Equipos
                        </a>
                    </li>
              
                <!-- Opciones solo para Administrador (rol = 1) -->
                <?php if ($_SESSION['rol'] == 1): ?>
                    <li class="nav-item">
                        <a href="../dashboard/dashboard.php" class="nav-link">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </aside>


<!-- Contenido principal -->
<main class="content p-4">
        <div class="container-fluid">
            <header class="header-title">
                <h2>Historial De Citas</h2>
            </header>
            <!-- Sección de Historial de Citas -->
    <section class="timeline-section">
        <h2>Historial de Cita</h2>
        <?php if ($cita): ?>
            <div class="timeline">
                <div class="timeline-event">
                    <div class="timeline-content">
                        <h3>Folio: <?php echo htmlspecialchars($cita['CodigoFolio']); ?></h3>
                        <p><strong>Id de la Cita:</strong> <?php echo htmlspecialchars($cita['Id']); ?></p>
                        <p><strong>Día:</strong> <?php echo htmlspecialchars($cita['Dia']); ?></p>
                        <p><strong>Hora:</strong> <?php echo htmlspecialchars($cita['Hora']); ?></p>
                        <p><strong>Estado Actual:</strong> <?php echo htmlspecialchars($cita['Estado']); ?></p>
                        <p><strong>Servicio de Interés:</strong> <?php echo htmlspecialchars($cita['ServicioInteres']); ?></p>
                        <p><strong>Título de Contacto:</strong> <?php echo htmlspecialchars($cita['TituloContacto']); ?></p>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($cita['Nombre']); ?></p>
                        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($cita['Apellido']); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($cita['Telefono']); ?></p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($cita['Correo']); ?></p>

                        <?php if ($cita['Estado'] === 'Propuesta'): ?>
                            <form method="post" class="action-form">
                                <button type="submit" name="agendar" class="btn-primary">Aceptar Cita</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>No se encontró una cita con el folio proporcionado.</p>
        <?php endif; ?>
    </section>
</main>
<script src="../js/aside.js"></script>
</body>
</html>