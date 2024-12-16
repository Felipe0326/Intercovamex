<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}
// Obtener el ID del cliente logueado desde la sesión
$clienteId = $_SESSION['usuarioId'];

// Definir la consulta para obtener los datos del cliente, incluyendo su título de contacto y los equipos asociados
$sqlCliente = "SELECT Contacto.TituloContacto, Contacto.Nombre, Contacto.Apellido, Contacto.Telefono, 
               Contacto.Email, Empresa.NombreEmpresa, Contacto.ServicioInteres, Empresa.Id AS EmpresaId,
               Equipos.Id AS EquipoId, Equipos.NParte, Equipos.Marca, Equipos.Modelo, Equipos.NSerie, Equipos.Observaciones
               FROM Contacto
               JOIN Empresa ON Contacto.EmpresaId = Empresa.Id
               LEFT JOIN Equipos ON Equipos.EmpresaEId = Empresa.Id
               WHERE Contacto.Id = ?";

// Preparar la consulta
$stmt = mysqli_prepare($conn, $sqlCliente);
mysqli_stmt_bind_param($stmt, 'i', $clienteId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$clienteData = mysqli_fetch_assoc($result);

// Verificar si se encontraron datos del cliente
if (!$clienteData) {
    echo "No se encontraron datos para el cliente.";
    exit;
}
?>

<!-- Aquí comienza el HTML para mostrar los datos del cliente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link rel="stylesheet" href="../css/citasclientes.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
    <div class="logo">
        <img src="uploads/logolargo.png" alt="Logo Intercovamex">
    </div>
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="nav-item"><a href="index.php">Inicio</a></li>
            <li class="nav-item"><a href="citas_clientes.php">Agendar Citas</a></li>
            <li class="nav-item"><a href="citas_clientes_equipos.php">Mis equipos</a></li>
            <li class="nav-item"><a href="contacto_cliente.php">Contacto</a></li>
        </ul>
    </nav>
    <!-- Botones de sesión -->
    <div>
        <?php if (!isset($_SESSION['usuarioId'])): ?>
            <!-- Mostrar solo si no hay usuario logueado -->
            <a href="login.html" class="btn login-btn">Iniciar Sesión</a>
        <?php else: ?>
            <!-- Mostrar solo si hay usuario logueado -->
            <a href="logout.php" class="btn logout-btn">Cerrar Sesión</a>
        <?php endif; ?>
    </div>
</header>

    <!-- Contenido principal -->
    <section class="client-info-section">
        <h2>Información del Cliente</h2>
        <div class="client-info">
            <p><strong>Título de Contacto:</strong> <?php echo $clienteData['TituloContacto']; ?></p>
            <p><strong>Nombre:</strong> <?php echo $clienteData['Nombre']; ?></p>
            <p><strong>Apellido:</strong> <?php echo $clienteData['Apellido']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $clienteData['Telefono']; ?></p>
            <p><strong>Correo:</strong> <?php echo $clienteData['Email']; ?></p>
            <p><strong>Nombre de la Empresa:</strong> <?php echo $clienteData['NombreEmpresa']; ?></p>
            <p><strong>Servicio de Interés:</strong> <?php echo $clienteData['ServicioInteres']; ?></p>
        </div>

        <?php if ($clienteData['NParte']) { ?>
            <h3>Agendar Cita</h3>
            <form action="agendar_cita_procesar.php" method="POST" class="appointment-form">
                <label for="equipo">Equipo:</label>
                <select name="equipo" id="equipo" required>
                    <option value="">Seleccione un equipo</option>
                    <?php do { ?>
                        <option value="<?php echo htmlspecialchars($clienteData['EquipoId']); ?>">
                            <?php echo "Parte: " . htmlspecialchars($clienteData['NParte']) . " | Marca: " . htmlspecialchars($clienteData['Marca']) . " | Modelo: " . htmlspecialchars($clienteData['Modelo']) . " | NSerie: " . htmlspecialchars($clienteData['NSerie']); ?>
                        </option>
                    <?php } while ($clienteData = mysqli_fetch_assoc($result)); ?>
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

    <!-- Footer -->
    <footer>
        <div class="footer-logo">
            <img src="uploads/logolargo.png" alt="Logo Intercovamex">
        </div>
        <div class="footer-container">
            <div>
                <h3>Nosotros:</h3>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#productos">Ingenieros</a></li>
                    <li><a href="#nosotros">Agendar Servicios</a></li>
                    <li><a href="#contacto">Trabajos</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </div>
            <div>
                <h3>Contacto:</h3>
                <ul>
                    <li><strong>Oficina Cuernavaca:</strong> <br> +52 (777) 313 2260</li>
                    <li><strong>Oficina Querétaro:</strong> <br> +52 (442) 645 0474</li>
                    <li><strong>Oficina Monterrey:</strong> <br> +52 (818) 345 1166</li>
                    <li style="margin-top: 10px;"><strong>Correo Electrónico:</strong> <br> <a href="mailto:contacto@intercovamex.com">contacto@intercovamex.com</a></li>
                </ul>
            </div>
            <div>
                <h3>Productos y Servicios:</h3>
                <ul>
                    <li>Soluciones de Vacío</li>
                    <li>Detección de Fugas</li>
                    <li>Control de Calidad e Instrumentación Científica</li>
                    <li>Sistemas de Depósito y Tratamientos Térmicos</li>
                    <li>Servicio Técnico</li>
                    <li>Proyectos de Ingeniería</li>
                    <li>Equipos Usados y Promociones</li>
                </ul>
            </div>
        </div>
        <div class="footer-official-link">
            <p style="margin-top: 10px;"><a href="https://www.intercovamex.com" class="intercovamex-link" target="_blank">Visita nuestra página web oficial</a></p>
        </div>
    </footer>
    <div class="footer-text">
        <p>© 2024 Intercovamex. Todos los derechos reservados.</p>
    </div>
</body>
</html>



