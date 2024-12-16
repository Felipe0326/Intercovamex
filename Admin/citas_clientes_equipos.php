<?php
include('../db.php'); // Conexión a la base de datos
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Obtener el ID del cliente logueado desde la sesión
$clienteId = $_SESSION['usuarioId'];

// Consultar los datos del cliente logueado y su empresa
$sqlCliente = "SELECT c.TituloContacto, c.Nombre, c.Apellido, c.Telefono, c.Email, c.ServicioInteres,
                      e.NombreEmpresa, e.RazonS, e.DireccionFiscal, e.Estado, e.Rfc, e.CodigoPostal,
                      e.Id AS EmpresaId
               FROM Contacto c
               JOIN Empresa e ON c.EmpresaId = e.Id
               WHERE c.Id = ?";
$stmt = mysqli_prepare($conn, $sqlCliente);
mysqli_stmt_bind_param($stmt, 'i', $clienteId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$clienteData = mysqli_fetch_assoc($result);

if (!$clienteData) {
    echo "No se encontraron datos para el cliente.";
    exit;
}

// Consultar equipos asociados a la empresa
$sqlEquipos = "SELECT Id, NParte, Modelo, Marca, NSerie FROM Equipos WHERE EmpresaEId = ?";
$stmtEquipos = mysqli_prepare($conn, $sqlEquipos);
mysqli_stmt_bind_param($stmtEquipos, 'i', $clienteData['EmpresaId']);
mysqli_stmt_execute($stmtEquipos);
$equiposResult = mysqli_stmt_get_result($stmtEquipos);

// Consultar historial de citas si un equipo está seleccionado
$historialResult = null;
if (isset($_POST['equipo'])) {
    $equipoId = $_POST['equipo'];
    $sqlHistorial = "
        SELECT c.Dia, c.Hora, 
               CONCAT('Nparte:',e.NParte, ' | ','Modelo:', e.Modelo, ' | ','Marca:', e.Marca, ' | ','NSerie:', e.NSerie) AS Equipo,
               emp.Nombre AS NombreEmpleado, 
               cli.Nombre AS NombreCliente,
               c.TituloContacto, c.ServicioInteres, f.CodigoFolio, 
               empresa.NombreEmpresa AS Empresa
        FROM Citas c
        LEFT JOIN Equipos e ON c.EquipoId = e.Id
        LEFT JOIN Empleados emp ON c.EmpleadoId = emp.Id
        LEFT JOIN Contacto cli ON c.ContactoId = cli.Id
        LEFT JOIN Empresa empresa ON c.EmpresaId = empresa.Id
        LEFT JOIN Folio f ON c.FolioIdC = f.Id
        WHERE c.EquipoId = ?
        ORDER BY c.Dia DESC, c.Hora DESC";
    $stmtHistorial = mysqli_prepare($conn, $sqlHistorial);
    mysqli_stmt_bind_param($stmtHistorial, 'i', $equipoId);
    mysqli_stmt_execute($stmtHistorial);
    $historialResult = mysqli_stmt_get_result($stmtHistorial);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Cliente</title>
    <link rel="stylesheet" href="css/citasclientesequipos.css">
</head>
<body>
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
    <div>
        <a href="logout.php" class="btn logout-btn">Cerrar Sesión</a>
    </div>
</header>

<section class="client-info-section">
    <h2>Información del Cliente</h2>
    <div class="info-horizontal">
        <p><strong>Nombre:</strong> <?php echo $clienteData['TituloContacto'] . " " . $clienteData['Nombre'] . " " . $clienteData['Apellido']; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $clienteData['Telefono']; ?></p>
        <p><strong>Correo:</strong> <?php echo $clienteData['Email']; ?></p>
    </div>

    <h2>Datos de la Empresa</h2>
    <div class="info-horizontal">
        <p><strong>Nombre de la Empresa:</strong> <?php echo $clienteData['NombreEmpresa']; ?></p>
        <p><strong>Razón Social:</strong> <?php echo $clienteData['RazonS']; ?></p>
        <p><strong>Dirección Fiscal:</strong> <?php echo $clienteData['DireccionFiscal']; ?></p>
        <p><strong>Estado:</strong> <?php echo $clienteData['Estado']; ?></p>
        <p><strong>RFC:</strong> <?php echo $clienteData['Rfc']; ?></p>
        <p><strong>Código Postal:</strong> <?php echo $clienteData['CodigoPostal']; ?></p>
    </div>

    <h3>Seleccionar Equipo</h3>
    <form method="POST">
        <label for="equipo">Equipo:</label>
        <select name="equipo" id="equipo" onchange="this.form.submit()" required>
            <option value="">Seleccione un equipo</option>
            <?php while ($equipo = mysqli_fetch_assoc($equiposResult)): ?>
                <option value="<?php echo $equipo['Id']; ?>">
                    <?php echo "Parte: {$equipo['NParte']} | Marca: {$equipo['Marca']} | Modelo: {$equipo['Modelo']} | NSerie: {$equipo['NSerie']}"; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($historialResult): ?>
    <h3 class="timeline-section-title">Historial del Equipo</h3>
    <div class="timeline">
        <?php while ($historial = mysqli_fetch_assoc($historialResult)): ?>
            <div class="timeline-event">
                <div class="timeline-content">
                    <p><strong>Fecha:</strong> <?php echo $historial['Dia']; ?></p>
                    <p><strong>Hora:</strong> <?php echo $historial['Hora']; ?></p>
                    <p><strong>Equipo:</strong> <?php echo $historial['Equipo']; ?></p>
                    <p><strong>Empleado:</strong> <?php echo $historial['NombreEmpleado']; ?></p>
                    <p><strong>Cliente:</strong> <?php echo $historial['NombreCliente']; ?></p>
                    <p><strong>Título de Contacto:</strong> <?php echo $historial['TituloContacto']; ?></p>
                    <p><strong>Servicio de Interés:</strong> <?php echo $historial['ServicioInteres']; ?></p>
                    <p><strong>Folio:</strong> <?php echo $historial['CodigoFolio']; ?></p>
                    <p><strong>Empresa:</strong> <?php echo $historial['Empresa']; ?></p>
                    <!-- Formulario para descargar el PDF -->
                    <form method="POST" action="generar_folio_pdf.php">
                        <input type="hidden" name="folio" value="<?php echo $historial['CodigoFolio']; ?>">
                        <button type="submit" class="btn btn-primary">Descargar Folio en PDF</button>
                    </form>
                </div>
            </div>
            <hr>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

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
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="citas_clientes.php">Agendar Servicios</a></li>
                    <li><a href="citas_clientes_equipos.php">Mis Equipos</a></li>
                    <li><a href="contacto_cliente.php">Contacto</a></li>
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
                <li><a href="https://intercovamex.com/soluciones-de-vacio/">Soluciones de Vacío</a></li>
                    <li><a href="https://intercovamex.com/deteccion-de-fugas/">Detección de Fugas</a></li>
                    <li><a href="https://intercovamex.com/control-calidad-instrumentacion-cientifica/">Control de Calidad e Instrumentación Científica</a></li>
                    <li><a href="https://intercovamex.com/sistemas-de-deposito-y-tratamientos-termicos/">Sistemas de Depósito y Tratamientos Térmicos</a></li>
                    <li><a href="https://intercovamex.com/servicio-tecnico/">Servicio Técnico</a></li>
                    <li><a href="https://intercovamex.com/ingenieria/">Proyectos de Ingeniería</a></li>
                    <li><a href="https://diseno667.wixsite.com/intercovamex-hotsale">Equipos Usados y Promociones</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-official-link">
            <p style="margin-top: 10px;"><a href="https://www.intercovamex.com" class="intercovamex-link" target="_blank">Conoce mas de Intercovamex</a></p>
        </div>
    </footer>
    <div class="footer-text">
        <p>© 2024 Intercovamex. Todos los derechos reservados.</p>
    </div>
    
</body>
</html>


