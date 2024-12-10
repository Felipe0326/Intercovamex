<?php
include('../db.php'); // Conexión a la base de datos
include('../authcliente.php'); // Verifica sesión y roles
include('backend_contactocliente.php'); // Función para obtener datos del cliente
// Obtener el ID del cliente logueado desde la sesión
$usuarioId = $_SESSION['usuarioId'];

$folioGenerado = null;
if (isset($_GET['contactoClienteId'])) {
    $contactoClienteId = intval($_GET['contactoClienteId']);
    $folioGenerado = getFolioGenerado($conn, $contactoClienteId);
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
    <link rel="stylesheet" href="../css/contacto.css"> <!-- Archivo CSS personalizado -->
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
                <h2>Contacto</h2>
            </header>
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
            <form action="historial_citasclientes.php" method="GET" style="margin-top: 40px; padding: 20px; border-top: 2px solid #ddd;">
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
    </main>
    <script src="../js/aside.js"></script>
</body>
</html>