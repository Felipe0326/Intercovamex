<?php
// Incluir la conexión a la base de datos y los scripts necesarios
include('../../db.php'); // Archivo de conexión a la base de datos
include('../../roles/authcliente.php'); // Verifica sesión y roles
include('../backend/back_clienteslogin.php'); // Función para obtener datos del cliente

// Obtener el ID del cliente logueado desde la sesión
$usuarioId = $_SESSION['usuarioId'];

// Obtener los datos del cliente logueado
$clienteData = getClienteData($conn, $usuarioId);

// Verificar si se encontraron datos del cliente
if (!$clienteData) {
    echo "No se encontraron datos para el cliente.";
    exit;
}

// Obtener los equipos asociados al cliente
$equipos = getEquiposByCliente($conn, $usuarioId);
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
    <link rel="stylesheet" href="../../css/citasclientes.css"> <!-- Archivo CSS personalizado -->
</head>
<html lang="es">
  <!-- Menú lateral -->
  <aside class="sidebar p-3">
        <!-- Botón de colapso/despliegue -->
        <div class="toggle-btn">
            <button id="toggleSidebar" class="btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="logo">
            <img src="../../uploads/logocortoblanco.png" alt="Logo de Intercovamex">
        </div>
        <div class="user-profile text-center">
            <p class="welcome-text">Intercovamex</p>
            <h4><?php echo $clienteData['Nombre'] . ' ' . $clienteData['Apellido']; ?></h4>
            <a href="../../logout.php" class="btn logout-btn mt-2">Cerrar Sesión</a>
        </div>

        <!-- Menú de navegación -->
        <nav class="mt-4">
            <ul class="nav flex-column">
                <!-- Opciones para Cliente (rol = 2) y Administrador (rol = 1) -->
                <?php if (in_array($_SESSION['rol'], [1, 2])): ?>
                
                    <li class="nav-item">
                        <a href="../../citasclientes/frontend/citas_clienteslogin.php" class="nav-link">
                            <i class="fas fa-calendar-alt"></i> Gestión de Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../citasclientes/frontend/citas_clientesequipos.php" class="nav-link">
                            <i class="fas fa-tools"></i> Mis Equipos
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Opciones solo para Administrador (rol = 1) -->
                <?php if ($_SESSION['rol'] == 1): ?>
                    <li class="nav-item">
                        <a href="../../dashboard/frontend/dashboard.php" class="nav-link">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                        <a href="../../equipos/frontend/equipos_consultar.php" class="nav-link">
                            <i class="fas fa-tools"></i>Consultar Ficha
                        </a>
                    </li>
            </ul>
            
        </nav>
    </aside>


<!-- Contenido principal -->
<main class="content p-4">
        <div class="container-fluid">
            <header class="header-title">
                <h2>Citas Clientes</h2>
            </header>

            <section class="client-info-section">
    <h2>Información del Cliente</h2>
    <div class="client-info">
        <p><strong>Título de Contacto:</strong> <?php echo htmlspecialchars($clienteData['TituloContacto']); ?></p>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($clienteData['Nombre']); ?></p>
        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($clienteData['Apellido']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($clienteData['Telefono']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($clienteData['Email']); ?></p>
        <p><strong>Nombre de la Empresa:</strong> <?php echo htmlspecialchars($clienteData['NombreEmpresa']); ?></p>
        <p><strong>Servicio de Interés:</strong> <?php echo htmlspecialchars($clienteData['ServicioInteres']); ?></p>
    </div>

    <?php if (!empty($equipos)) { ?>
        <h3>Agendar Cita</h3>
        <form action="../backend/agendar_cita_procesar.php" method="POST" class="appointment-form">
            <label for="equipo">Equipo:</label>
            <select name="equipo" id="equipo" required>
                <option value="">Seleccione un equipo</option>
                <?php foreach ($equipos as $equipo): ?>
                    <option value="<?php echo htmlspecialchars($equipo['EquipoId']); ?>">
                        <?php echo "Parte: " . htmlspecialchars($equipo['NParte']) . " | Marca: " . htmlspecialchars($equipo['Marca']) . " | Modelo: " . htmlspecialchars($equipo['Modelo']) . " | NSerie: " . htmlspecialchars($equipo['NSerie']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="dia">Fecha de la Cita:</label>
            <input type="date" id="dia" name="dia" required><br>

            <label for="hora">Hora de la Cita:</label>
            <input type="time" id="hora" name="hora" required><br>

            <input type="hidden" name="clienteId" value="<?php echo htmlspecialchars($clienteId); ?>">
            <input type="hidden" name="empresaId" value="<?php echo htmlspecialchars($clienteData['EmpresaId']); ?>">
            <input type="submit" value="Agendar Cita" class="btn-primary">
        </form>
    <?php } else { ?>
        <p>No hay equipos asociados a este cliente.</p>
    <?php } ?>
</section>

</main>


<script src="../../js/aside.js"></script>
</body>
</html>
