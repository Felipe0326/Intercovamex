<?php
include('../../db.php'); // Conexión a la base de datos
include('../backend/backend_contactocliente.php'); // Función para obtener datos del cliente
// Obtener el ID del cliente logueado desde la sesión


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
    <link rel="stylesheet" href="../../css/contacto.css"> <!-- Archivo CSS personalizado -->
</head>

<body>
    <!-- Canvas para partículas (Fondo animado) -->
    <canvas id="particleCanvas"></canvas>

    <!-- Contenido principal -->
    <main class="content p-4">
        <div class="container-fluid">
            <header class="header-title"></header>

            <section class="contact-section">
                <div class="contact-form">
                    <div class="contact-image">
                        <img src="../../uploads/logolargo.png" alt="Imagen de Contacto" class="img-fluid">
                    </div>
                    <h2>Crear cuenta</h2>
                    

                    <!-- Formulario principal -->
                    <form action="../backend/contacto_procesar_cliente.php" method="POST">
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
                        <h2>Propuesta</h2>
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
                </div>

                <div class="contact-info">

                    <!-- Contenedor de inicio de sesión -->
                    <div class="login-container" style="margin-top: 40px;">
                        <div class="login-message">
                            <p><strong>¿Ya tienes cuenta?</strong> ¡Inicia sesión para consultar tu crédito de manera fácil y rápida!</p>
                        </div>
                        <div class="login-button-container">
                            <button type="button" class="login-button" onclick="window.location.href='../../login.html'">Inicia sesión aquí</button>
                        </div>

                        <hr class="separator">
                    </div>

                    <hr class="separator" style="margin-top: 40px;">

                    <!-- Información de contacto -->
                    <h2>Contáctanos</h2>
                    <ul>
                        <li><strong>Llámanos:</strong></li>
                        <li>Oficina Cuernavaca: +52 (777) 313 2260</li>
                        <li>Oficina Querétaro: +52 (442) 645 0474</li>
                        <li>Oficina Monterrey: +52 (818) 345 1166</li>
                    </ul>
                    <hr class="separator">
                    <ul>
                        <li><strong>Horario de Atención:</strong></li>
                        <li>De 9:00 a 18:00 hrs (GMT-6)</li>
                    </ul>
                    <hr class="separator">
                    <ul>
                        <li><strong>Email:</strong></li>
                        <li><a href="mailto:contacto@intercovamex.com">contacto@intercovamex.com</a></li>
                    </ul>
                </div>

            </section>
        </div>
    </main>

    <!-- Script para partículas en el fondo -->
    <script src="../../js/atomoscontacto.js"></script>
</body>
</html>



