<?php
include('../db.php'); // Conexión a la base de datos
include('../auth135.php');
include('backend_crear.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admincrear.css">
    <title>Crear Contacto</title>
</head>
<body>
    <div class="form-container">
        <h2>Crear Nuevo Contacto</h2>

        <form action="contacto_crear.php" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese la contraseña" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese el correo electrónico" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingrese el nombre" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Ingrese el apellido" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Ingrese el teléfono">
                </div>

                <div class="form-group">
                    <label for="puesto">Puesto:</label>
                    <input type="text" id="puesto" name="puesto" placeholder="Ingrese el puesto">
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" placeholder="Ingrese el estado">
                </div>

                <div class="form-group">
                    <label for="empresaId">Empresa:</label>
                    <select id="empresaId" name="empresaId" required>
                        <option value="">Selecciona una empresa</option>
                        <?php while ($empresa = mysqli_fetch_assoc($resultEmpresas)) { ?>
                            <option value="<?php echo $empresa['Id']; ?>"><?php echo $empresa['NombreEmpresa']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuarioFinal">
                        <input type="checkbox" id="usuarioFinal" name="usuarioFinal">
                        ¿Es el usuario final?
                    </label>
                </div>
            </div>

            <input type="submit" value="Crear Contacto" class="btn-submit">
        </form>

        <div class="btn-back-container">
            <a href="contacto_usuarios.php" class="btn-back">Volver a la lista de contactos</a>
        </div>
    </div>
</body>
</html>


