<?php
include('../db.php'); // Conexión a la base de datos
include('../auth15.php'); // Verificación de sesión y permisos
include('backend_editar.php'); // Backend para editar citas
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/citaseditar.css">
    <title>Editar Cita</title>
    <script>
        function mostrarEquiposYContactos() {
            var empresaSelect = document.getElementById("empresaId");
            var empresaId = empresaSelect.options[empresaSelect.selectedIndex].value;
            var equipoSelect = document.getElementById("equipoId");
            var contactoSelect = document.getElementById("contactoId");

            // Filtrar equipos por empresa
            var opcionesEquipo = equipoSelect.options;
            for (var i = 0; i < opcionesEquipo.length; i++) {
                opcionesEquipo[i].style.display = "none";
                if (opcionesEquipo[i].classList.contains("empresa-" + empresaId)) {
                    opcionesEquipo[i].style.display = "block";
                }
            }

            // Filtrar contactos por empresa
            var opcionesContacto = contactoSelect.options;
            for (var i = 0; i < opcionesContacto.length; i++) {
                opcionesContacto[i].style.display = "none";
                if (opcionesContacto[i].classList.contains("empresa-" + empresaId)) {
                    opcionesContacto[i].style.display = "block";
                }
            }
        }

        // Ejecutar la función para mostrar los contactos y equipos correctos al cargar la página
        window.onload = function() {
            mostrarEquiposYContactos();
        };
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Editar Cita</h2>
        <form action="" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="dia">Día:</label>
                    <input type="date" id="dia" name="dia" value="<?php echo $cita['Dia']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <input type="time" id="hora" name="hora" value="<?php echo $cita['Hora']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="empresaId">Cliente:</label>
                    <select id="empresaId" name="empresaId" required onchange="mostrarEquiposYContactos()">
                        <option value="">Seleccione un Cliente</option>
                        <?php while ($cliente = mysqli_fetch_assoc($clientesResult)) { ?>
                            <option value="<?php echo $cliente['EmpresaId']; ?>" <?php echo ($cliente['EmpresaId'] == $cita['EmpresaId']) ? 'selected' : ''; ?>>
                                <?php echo $cliente['NombreEmpresa']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contactoId">Contacto:</label>
                    <select id="contactoId" name="contactoId">
                        <option value="">Seleccione un Contacto</option>
                        <?php
                        $contactoQuery = "SELECT Contacto.Id, Contacto.Nombre, Contacto.Apellido, Contacto.EmpresaId FROM Contacto";
                        $contactoResult = mysqli_query($conn, $contactoQuery);
                        while ($contacto = mysqli_fetch_assoc($contactoResult)) { ?>
                            <option class="empresa-<?php echo $contacto['EmpresaId']; ?>" value="<?php echo $contacto['Id']; ?>" <?php echo ($contacto['Id'] == $cita['ContactoId']) ? 'selected' : ''; ?> style="display:none;">
                                <?php echo $contacto['Nombre'] . " " . $contacto['Apellido']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="equipoId">Equipo:</label>
                    <select id="equipoId" name="equipoId">
                        <option value="">Seleccione un Equipo</option>
                        <?php foreach ($equipos as $empresaId => $equiposEmpresa) { ?>
                            <?php foreach ($equiposEmpresa as $equipo) { ?>
                                <option class="empresa-<?php echo $empresaId; ?>" value="<?php echo $equipo['Id']; ?>" <?php echo ($equipo['Id'] == $cita['EquipoId']) ? 'selected' : ''; ?> style="display:none;">
                                    <?php echo $equipo['NParte']; ?>
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
                            <option value="<?php echo $empleado['Id']; ?>" <?php echo ($empleado['Id'] == $cita['EmpleadoId']) ? 'selected' : ''; ?>>
                                <?php echo $empleado['Nombre'] . " " . $empleado['Apellido']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="Propuesta" <?php echo ($cita['Estado'] == 'Propuesta') ? 'selected' : ''; ?>>Propuesta</option>
                        <option value="Aceptada" <?php echo ($cita['Estado'] == 'Aceptada') ? 'selected' : ''; ?>>Aceptada</option>
                        <option value="Rechazada" <?php echo ($cita['Estado'] == 'Rechazada') ? 'selected' : ''; ?>>Rechazada</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estatus">Estatus:</label>
                    <select id="estatus" name="estatus" required>
                        <option value="En espera" <?php echo ($cita['Estatus'] == 'En espera') ? 'selected' : ''; ?>>En espera</option>
                        <option value="En proceso" <?php echo ($cita['Estatus'] == 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                        <option value="Reprogramada" <?php echo ($cita['Estatus'] == 'Reprogramada') ? 'selected' : ''; ?>>Reprogramada</option>
                        <option value="Concluida" <?php echo ($cita['Estatus'] == 'Concluida') ? 'selected' : ''; ?>>Concluida</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="Actualizar Cita" class="btn-submit">
        </form>
        <div class="btn-back-container">
            <a href="citas_usuarios.php" class="btn-back">Volver a la lista de citas</a>
        </div>
    </div>
</body>
</html>

