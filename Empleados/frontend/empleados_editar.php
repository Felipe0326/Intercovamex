<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/authadmin.php');
include('../backend/backend_editar.php');

// Validar y obtener el ID del empleado desde la URL
$usuarioId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$usuarioId) {
    echo "Empleado no encontrado.";
    exit;
}

// Validar y obtener los datos del empleado
$empleado = obtenerEmpleado($conn, $usuarioId);
if (!$empleado) {
    echo "Empleado no encontrado.";
    exit;
}

// Procesar formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    actualizarEmpleado($conn, $empleado);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/empleadoseditar.css">
    <title>Editar Empleado</title>
</head>
<body>
    <div class="form-container">
        <h2>Editar Empleado</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombreUsuario">Nombre de Usuario:</label>
                    <input type="text" id="nombreUsuario" name="nombreUsuario" value="<?php echo $empleado['NombreUsuario']; ?>" placeholder="Ingrese el nombre de usuario" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo $empleado['Email']; ?>" placeholder="Ingrese el correo electrónico" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $empleado['Nombre']; ?>" placeholder="Ingrese el nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo $empleado['Apellido']; ?>" placeholder="Ingrese el apellido" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo $empleado['Telefono']; ?>" placeholder="Ingrese el teléfono">
                </div>
                <div class="form-group">
                    <label for="rolId">Rol:</label>
                    <select id="rolId" name="rolId" required>
                        <?php
                        $roles = obtenerRoles($conn);
                        foreach ($roles as $rol) {
                            $selected = ($rol['Id'] == $empleado['RolId']) ? 'selected' : '';
                            echo "<option value='{$rol['Id']}' $selected>{$rol['NombreRol']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="puesto">Puesto:</label>
                    <input type="text" id="puesto" name="puesto" value="<?php echo $empleado['Puesto']; ?>" placeholder="Ingrese el puesto" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" id="descripcion" name="descripcion" value="<?php echo $empleado['Descripcion']; ?>" placeholder="Ingrese la descripción" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto (dejar en blanco para mantener la actual):</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                    <?php if ($empleado['Foto']): ?>
                        <img src="uploads/<?php echo $empleado['Foto']; ?>" alt="Foto actual" width="100" style="margin-top: 10px; border-radius: 8px;">
                    <?php endif; ?>
                </div>
            </div>
            <input type="submit" value="Actualizar Empleado" class="btn-submit">
        </form>
        <div class="btn-back-container">
            <a href="empleados_usuarios.php" class="btn-back">Volver a la lista de empleados</a>
        </div>
    </div>
</body>
</html>

