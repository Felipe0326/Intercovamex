<?php
include('../db.php'); // Conexión a la base de datos
include('../auth135.php');
include('backend_editar.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admineditar.css">
    <title>Editar Contacto</title>
</head>
<body>
    <div class="form-container">
        <h2>Editar Contacto</h2>
        <form action="" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="contrasena">Contraseña (dejar en blanco para mantenerla igual):</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Nueva contraseña">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['Nombre']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($user['Apellido']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['Telefono']); ?>">
                </div>

                <div class="form-group">
                    <label for="puesto">Puesto:</label>
                    <input type="text" id="puesto" name="puesto" value="<?php echo htmlspecialchars($user['Puesto']); ?>">
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($user['Estado']); ?>">
                </div>

                <div class="form-group">
                    <label for="empresaId">Empresa:</label>
                    <select id="empresaId" name="empresaId" required>
                        <?php while ($empresa = mysqli_fetch_assoc($resultEmpresas)) { 
                            $selected = ($empresa['Id'] == $user['EmpresaId']) ? 'selected' : ''; ?>
                            <option value="<?php echo $empresa['Id']; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($empresa['NombreEmpresa']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuarioFinal">
                        <input type="checkbox" id="usuarioFinal" name="usuarioFinal" <?php echo ($user['UsuarioFinal'] == 'Sí') ? 'checked' : ''; ?>>
                        ¿Es el usuario final?
                    </label>
                </div>
            </div>

            <input type="submit" value="Actualizar Contacto" class="btn-submit">
        </form>

        <div class="btn-back-container">
            <a href="contacto_usuarios.php" class="btn-back">Volver a la lista de contactos</a>
        </div>
    </div>
</body>
</html>



