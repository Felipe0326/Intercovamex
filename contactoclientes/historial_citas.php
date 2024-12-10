<?php
include('../db.php');
session_start();

// Obtener el folio desde la URL
$folio = isset($_GET['folio']) ? $_GET['folio'] : '';

// Consultar los datos de la cita usando el `CodigoFolio` en la tabla `Folio`
$query = "
    SELECT 
        ContactoCliente.Id, Folio.CodigoFolio, ContactoCliente.Dia, ContactoCliente.Hora, ContactoCliente.Estado, 
        ContactoCliente.ServicioInteres, ContactoCliente.TituloContacto, ContactoCliente.Nombre, 
        ContactoCliente.Apellido, ContactoCliente.Telefono, ContactoCliente.Correo 
    FROM 
        ContactoCliente
    JOIN 
        Folio ON ContactoCliente.FolioIdContactoCliente = Folio.Id
    WHERE 
        Folio.CodigoFolio = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $folio);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cita = mysqli_fetch_assoc($result);

if ($cita) {
    if (isset($_POST['agendar'])) {
        // Actualizar el estado de la cita a 'Aceptado'
        $updateQuery = "UPDATE ContactoCliente SET Estado = 'Aceptado' WHERE FolioIdContactoCliente = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, 'i', $cita['Id']);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);

        // Redirigir después de aceptar
        header("Location: citas_usuarios.php");
        exit;
    }
} 

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cita</title>
    <link rel="stylesheet" href="css/historialcitas.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <img src="uploads/logolargo.png" alt="Logo Intercovamex">
        </div>
        <nav class="navbar">
            <ul class="navbar-nav">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="citas_clientes.php">Agendar Servicios</a></li>
                    <li><a href="citas_clientes_equipos.php">Mis Equipos</a></li>
                    <li><a href="contacto_cliente.php">Contacto</a></li>
            </ul>
        </nav>
        <button class="btn login-btn">Iniciar Sesión</button>
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

    <!-- Footer -->
    <footer>
        <!-- Logo centrado -->
        <div class="footer-logo">
            <img src="uploads/logolargo.png" alt="Logo Intercovamex">
        </div>

        <!-- Secciones del footer -->
        <div class="footer-container">
            <!-- Nosotros -->
            <div>
                <h3>Nosotros:</h3>
                <ul>
                <li><a href="index.php">Inicio</a></li>
                    <li><a href="citas_clientes.php">Agendar Servicios</a></li>
                    <li><a href="citas_clientes_equipos.php">Mis Equipos</a></li>
                    <li><a href="contacto_cliente.php">Contacto</a></li>
                </ul>
            </div>
            <!-- Contacto -->
            <div>
                <h3>Contacto:</h3>
                <ul>
                    <li><strong>Oficina Cuernavaca:</strong> <br> +52 (777) 313 2260</li>
                    <li><strong>Oficina Querétaro:</strong> <br> +52 (442) 645 0474</li>
                    <li><strong>Oficina Monterrey:</strong> <br> +52 (818) 345 1166</li>
                    <li style="margin-top: 10px;"><strong>Correo Electrónico:</strong> <br> <a href="mailto:contacto@intercovamex.com">contacto@intercovamex.com</a></li>
                </ul>
            </div>
            <!-- Productos y Servicios -->
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

        <!-- Enlace a página oficial -->
        <div class="footer-official-link">
            <p style="margin-top: 10px;"><a href="https://www.intercovamex.com" class="intercovamex-link" target="_blank">Conoce mas de Intercovamex</a></p>
        </div>
    </footer>

    <!-- Copyright -->
    <div class="footer-text">
        <p>© 2024 Intercovamex. Todos los derechos reservados.</p>
    </div>
</body>
</html>

