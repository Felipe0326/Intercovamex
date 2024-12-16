<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

$folioGenerado = null;
if (isset($_GET['contactoClienteId'])) {
    $contactoClienteId = intval($_GET['contactoClienteId']);
    $query = "
        SELECT Folio.CodigoFolio 
        FROM Folio
        INNER JOIN ContactoCliente ON ContactoCliente.FolioIdContactoCliente = Folio.Id
        WHERE ContactoCliente.Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $contactoClienteId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $folioGenerado = htmlspecialchars($row['CodigoFolio']);
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto del Cliente</title>
    <link href="css/contactocliente.css" rel="stylesheet">
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

    <!-- Sección de Contacto -->
<section class="contact-section">
    <div class="contact-form">
        <h2>¡Escríbenos!</h2>
        <?php if ($folioGenerado): ?>
            <p><strong>Folio generado:</strong> <?php echo $folioGenerado; ?></p>
            
            <!-- Botón para descargar el PDF -->
            <form method="POST" action="generar_folio_pdfcontacto.php">
                <input type="hidden" name="folio" value="<?php echo $folioGenerado; ?>">
                <button type="submit" class="submit-button" style="margin-top: 10px; background-color: #004080; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                    Descargar Folio en PDF
                </button>
            </form>
        <?php else: ?>
            <p><strong>Error:</strong> No se encontró un folio asociado.</p>
        <?php endif; ?>


            <!-- Formulario principal -->
            <form action="contacto_procesar_cliente.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="email" id="correo" name="correo" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="servicio_interes">Servicio de Interés:</label>
                        <input type="text" id="servicio_interes" name="servicio_interes" required>
                    </div>
                    <div class="form-group">
                        <label for="titulo_contacto">Título de Contacto:</label>
                        <select id="titulo_contacto" name="titulo_contacto" required>
                            <option value="">Seleccione</option>
                            <option value="Ingeniero/a">Ingeniero/a</option>
                            <option value="Doctor/a">Doctor/a</option>
                            <option value="Licenciado/a">Licenciado/a</option>
                            <option value="Estudiante">Estudiante</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="n_parte">Número de Parte:</label>
                        <input type="text" id="n_parte" name="n_parte">
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" id="modelo" name="modelo">
                    </div>
                    <div class="form-group">
                        <label for="n_serie">Número de Serie:</label>
                        <input type="text" id="n_serie" name="n_serie">
                    </div>
                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <input type="text" id="marca" name="marca">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dia">Día de la Cita:</label>
                        <input type="date" id="dia" name="dia" required>
                    </div>
                    <div class="form-group">
                        <label for="hora">Hora de la Cita:</label>
                        <input type="time" id="hora" name="hora" required>
                    </div>
                </div>

                <button type="submit" class="submit-button">Enviar</button>
            </form>

            <!-- Formulario para buscar el historial de una cita por folio -->
            <form action="historial_citas.php" method="GET" style="margin-top: 40px; padding: 20px; border-top: 2px solid #ddd;">
                <h2 style="text-align: center; color: #004080;">Consultar Historial de Cita</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="folio">Ingrese el folio de la cita:</label>
                        <input type="text" id="folio" name="folio" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
                <button type="submit" class="submit-button" style="margin-top: 20px; width: 100%;">Ver Historial de Citas</button>
            </form>
        </div>

        <div class="contact-info">
            <h2>Contáctanos</h2>
            <ul>
                <li><strong>Llámanos:</strong></li>
                <li>Oficina Cuernavaca: +52 (777) 313 2260</li>
                <li>Oficina Querétaro: +52 (442) 645 0474</li>
                <li>Oficina Monterrey: +52 (818) 345 1166</li>
            </ul>
            <ul>
                <li><strong>Horario de Atención:</strong></li>
                <li>De 9:00 a 18:00 hrs (GMT-6)</li>
            </ul>
            <ul>
                <li><strong>Email:</strong></li>
                <li><a href="mailto:contacto@intercovamex.com">contacto@intercovamex.com</a></li>
            </ul>
        </div>
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


