<?php
include('../db.php'); // Conexión a la base de datos
include('../authcliente.php'); // Verifica sesión y roles
include('backend_citas_clientesequipos.php'); // Conexión a la base de datos

// Obtener el ID del usuario logueado
$usuarioId = $_SESSION['usuarioId'];

// Obtener los datos del cliente logueado y su empresa
$clienteData = getLoggedInUserAndClientData($conn, $usuarioId);

// Manejar el caso donde no se encuentran datos del cliente
if (!$clienteData) {
    $clienteData = [
        'Nombre' => 'No identificado',
        'Apellido' => '',
        'TituloContacto' => 'N/A',
        'Telefono' => 'N/A',
        'Email' => 'N/A',
        'NombreEmpresa' => 'N/A',
        'EmpresaId' => null,
    ];
    $equipos = [];
    $historial = [];
} else {
    // Obtener los equipos asociados a la empresa
    $empresaId = $clienteData['EmpresaId'];
    $equipos = getEquiposByEmpresa($conn, $empresaId);

    // Si se selecciona un equipo, obtener el historial de citas
    $historial = [];
    if (isset($_POST['equipo'])) {
        $equipoId = $_POST['equipo'];
        $historial = getHistorialByEquipo($conn, $equipoId);
    }
}
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
    <link rel="stylesheet" href="../css/citasclientesequipos.css"> <!-- Archivo CSS personalizado -->
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
            <h4><?php echo $clienteData['Nombre'] . ' ' . $clienteData['Apellido']; ?></h4>
            <a href="../logout.php" class="btn logout-btn mt-2">Cerrar Sesión</a>
        </div>

        <!-- Menú de navegación -->
        <nav class="mt-4">
            <ul class="nav flex-column">
                <!-- Opciones para Cliente (rol = 2) y Administrador (rol = 1) -->
                <?php if (in_array($_SESSION['rol'], [1, 2])): ?>
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
                <?php endif; ?>

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
                <h2>Mis equipos </h2>
            </header>

            
            <section class="client-info-section">
    <h2>Información del Cliente</h2>
    <div class="info-horizontal">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($clienteData['TituloContacto'] . " " . $clienteData['Nombre'] . " " . $clienteData['Apellido']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($clienteData['Telefono']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($clienteData['Email']); ?></p>
    </div>

    <h2>Datos de la Empresa</h2>
    <div class="info-horizontal">
        <p><strong>Nombre de la Empresa:</strong> <?php echo htmlspecialchars($clienteData['NombreEmpresa']); ?></p>
        <p><strong>Razón Social:</strong> <?php echo htmlspecialchars($clienteData['RazonS']); ?></p>
        <p><strong>Dirección Fiscal:</strong> <?php echo htmlspecialchars($clienteData['DireccionFiscal']); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($clienteData['Estado']); ?></p>
        <p><strong>RFC:</strong> <?php echo htmlspecialchars($clienteData['Rfc']); ?></p>
        <p><strong>Código Postal:</strong> <?php echo htmlspecialchars($clienteData['CodigoPostal']); ?></p>
    </div>

    <h3>Seleccionar Equipo</h3>
    <form method="POST">
        <label for="equipo">Equipo:</label>
        <select name="equipo" id="equipo" onchange="this.form.submit()" required>
            <option value="">Seleccione un equipo</option>
            <?php foreach ($equipos as $equipo): ?>
                <option value="<?php echo htmlspecialchars($equipo['Id']); ?>">
                    <?php echo "Parte: " . htmlspecialchars($equipo['NParte']) . " | Marca: " . htmlspecialchars($equipo['Marca']) . " | Modelo: " . htmlspecialchars($equipo['Modelo']) . " | NSerie: " . htmlspecialchars($equipo['NSerie']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (!empty($historial)): ?>
        <h3 class="timeline-section-title">Historial del Equipo</h3>
        <div class="timeline">
            <?php foreach ($historial as $evento): ?>
                <div class="timeline-event">
                    <div class="timeline-content">
                        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($evento['Dia']); ?></p>
                        <p><strong>Hora:</strong> <?php echo htmlspecialchars($evento['Hora']); ?></p>
                        <p><strong>Equipo:</strong> <?php echo htmlspecialchars($evento['Equipo']); ?></p>
                        <p><strong>Empleado:</strong> <?php echo htmlspecialchars($evento['NombreEmpleado']); ?></p>
                        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($evento['NombreCliente']); ?></p>
                        <p><strong>Título de Contacto:</strong> <?php echo htmlspecialchars($evento['TituloContacto']); ?></p>
                        <p><strong>Servicio de Interés:</strong> <?php echo htmlspecialchars($evento['ServicioInteres']); ?></p>
                        <p><strong>Folio:</strong> <?php echo htmlspecialchars($evento['CodigoFolio']); ?></p>
                        <p><strong>Empresa:</strong> <?php echo htmlspecialchars($evento['Empresa']); ?></p>
                        <form method="POST" action="generar_folio_pdf.php">
                            <input type="hidden" name="folio" value="<?php echo htmlspecialchars($evento['CodigoFolio']); ?>">
                            <button type="submit" class="btn btn-primary">Descargar Folio en PDF</button>
                        </form>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

</main>

<script src="../js/aside.js"></script>
</body>
</html>