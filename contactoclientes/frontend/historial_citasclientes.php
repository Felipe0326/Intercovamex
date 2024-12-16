<?php
include('../../db.php');
include('../backend/backend_citashistorial.php');
$folio = isset($_GET['folio']) ? $_GET['folio'] : '';
$cita = getCitaByFolio($conn, $folio);
 
?>
<<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Equipos</title>
    <!-- Importación de Bootstrap y CSS personalizado -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/historialcitas.css"> <!-- Archivo CSS personalizado -->
</head>
<body>
    <!-- Canvas para partículas (Fondo animado) -->
    <canvas id="particleCanvas"></canvas>

    <!-- Contenido principal -->
    <main class="content p-4">
        <!-- Wrapper para centrar la sección -->
        <div class="timeline-section-wrapper">
            
            <!-- Sección de Historial de Citas -->
            <section class="timeline-section">
                     <div class="contact-image">
                        <img src="../../uploads/logolargo.png" alt="Imagen de Contacto" class="img-fluid">
                    </div>
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
                    <div class="button-back-container">
                        <button type="button" class="button-back" onclick="window.location.href='contactos_clientes.php'">Regresar a Contactanos</button>
                    </div>

            </section>
        </div>
    </main>

    <script src="../../js/atomoscontacto.js"></script>
</body>
</html>