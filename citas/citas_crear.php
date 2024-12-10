<?php
include('../db.php'); // Conexión a la base de datos
include('../auth.php');
include('backend_crear.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/citascrear.css">
    <title>Crear Cita</title>
    <script>
        function mostrarEquiposYContactos() {
            var clienteSelect = document.getElementById("usuarioId");
            var clienteId = clienteSelect.options[clienteSelect.selectedIndex].getAttribute("data-cliente-id");
            var equipoSelect = document.getElementById("equipoId");
            var contactoSelect = document.getElementById("contactoId");

            // Filtrar equipos por cliente
            var opcionesEquipo = equipoSelect.options;
            for (var i = 0; i < opcionesEquipo.length; i++) {
                opcionesEquipo[i].style.display = "none";
            }
            for (var i = 0; i < opcionesEquipo.length; i++) {
                if (opcionesEquipo[i].classList.contains("cliente-" + clienteId)) {
                    opcionesEquipo[i].style.display = "block";
                }
            }

            // Filtrar contactos por cliente
            var opcionesContacto = contactoSelect.options;
            for (var i = 0; i < opcionesContacto.length; i++) {
                opcionesContacto[i].style.display = "none";
            }
            for (var i = 0; i < opcionesContacto.length; i++) {
                if (opcionesContacto[i].classList.contains("cliente-" + clienteId)) {
                    opcionesContacto[i].style.display = "block";
                }
            }
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Crear Nueva Cita</h2>
        <form action="citas_crear.php" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="dia">Día:</label>
                    <input type="date" id="dia" name="dia" required>
                </div>
                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <input type="time" id="hora" name="hora" required>
                </div>
                <div class="form-group">
                    <label for="usuarioId">Cliente:</label>
                    <select id="usuarioId" name="usuarioId" required onchange="mostrarEquiposYContactos()">
                        <option value="">Seleccione un Cliente</option>
                        <?php while ($cliente = mysqli_fetch_assoc($clientesResult)) { ?>
                            <option value="<?php echo $cliente['EmpresaId']; ?>" data-cliente-id="<?php echo $cliente['EmpresaId']; ?>">
                                <?php echo $cliente['NombreEmpresa']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contactoId">Contacto:</label>
                    <select id="contactoId" name="contactoId">
                        <option value="">Seleccione un Contacto</option>
                        <?php while ($contacto = mysqli_fetch_assoc($contactoResult)) { ?>
                            <option class="cliente-<?php echo $contacto['EmpresaId']; ?>" value="<?php echo $contacto['Id']; ?>" style="display:none;">
                                <?php echo $contacto['Nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="equipoId">Equipo:</label>
                    <select id="equipoId" name="equipoId">
                        <option value="">Seleccione un Equipo</option>
                        <?php foreach ($equipos as $empresaId => $equiposCliente) { ?>
                            <?php foreach ($equiposCliente as $equipo) { ?>
                                <option class="cliente-<?php echo $empresaId; ?>" value="<?php echo $equipo['Id']; ?>" style="display:none;">
                                    <?php echo "NParte: " . $equipo['NParte'] . " | Modelo: " . $equipo['Modelo'] . " | NSerie: " . $equipo['NSerie']; ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="empleadoId">Empleado:</label>
                    <select id="empleadoId" name="empleadoId">
                        <option value="">Seleccione un Empleado</option>
                        <?php while ($empleado = mysqli_fetch_assoc($empleadoResult)) { ?>
                            <option value="<?php echo $empleado['Id']; ?>">
                                <?php echo $empleado['Nombre'] . " " . $empleado['Apellido']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required disabled>
                        <option value="Propuesta" selected>Propuesta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estatus">Estatus:</label>
                    <select id="estatus" name="estatus" required disabled>
                        <option value="En espera" selected>En espera</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="Guardar Cita" class="btn-submit">
        </form>
        <div class="btn-back-container">
            <a href="citas_usuarios.php" class="btn-back">Volver a la lista de citas</a>
        </div>
    </div>
</body>
</html>



