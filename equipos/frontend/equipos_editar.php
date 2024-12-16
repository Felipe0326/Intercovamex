<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/auth15.php');
include('../backend/backend_editar.php');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/equiposeditar.css">
    <title>Editar Equipo</title>
</head>
<body>
    <div class="form-container">
        <h2>Editar Equipo</h2>
        <form action="" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nParte">Parte:</label>
                    <input type="text" id="nParte" name="nParte" value="<?php echo $equipo['NParte']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <input type="text" id="modelo" name="modelo" value="<?php echo $equipo['Modelo']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nSerie">Número de Serie:</label>
                    <input type="text" id="nSerie" name="nSerie" value="<?php echo $equipo['NSerie']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="marca">Marca:</label>
                    <input type="text" id="marca" name="marca" value="<?php echo $equipo['Marca']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="empresaEId">Empresa:</label>
                    <select id="empresaEId" name="empresaEId" required>
                        <?php while ($empresa = mysqli_fetch_assoc($empresas)) { ?>
                            <option value="<?php echo $empresa['Id']; ?>" <?php if ($empresa['Id'] == $equipo['EmpresaEId']) echo 'selected'; ?>>
                                <?php echo $empresa['NombreEmpresa']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <input type="text" id="observaciones" name="observaciones" value="<?php echo $equipo['Observaciones']; ?>">
                </div>
                <div class="form-group">
                    <label for="estatus">Estatus:</label>
                    <select id="estatus" name="estatus" required>
                        <option value="Activo" <?php if ($equipo['Estatus'] == 'Activo') echo 'selected'; ?>>Activo</option>
                        <option value="Pendiente" <?php if ($equipo['Estatus'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                        <option value="Finalizado" <?php if ($equipo['Estatus'] == 'Finalizado') echo 'selected'; ?>>Finalizado</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="Actualizar Equipo" class="btn-submit">
        </form>
        <div class="btn-back-container">
            <a href="equipos_usuarios.php" class="btn-back">Volver a la lista de equipos</a>
        </div>
    </div>
</body>
</html>
