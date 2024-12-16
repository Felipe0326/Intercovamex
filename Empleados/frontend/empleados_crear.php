<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/authadmin.php');
include('../backend/backend_crear.php');

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    procesarFormularioEmpleado($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/empleadoscrear.css">
    <title>Añadir Nuevo Empleado</title>
</head>
<body>
    <div class="form-container">
        <h2>Añadir Nuevo Empleado</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombreUsuario">Nombre de Usuario:</label>
                    <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingrese el nombre de usuario" required>
                </div>
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
                    <label for="rolId">Rol:</label>
                    <select id="rolId" name="rolId" required>
                        <option value="1">Administrador</option>
                        <option value="3">Vendedor</option>
                        <option value="4">Servicio</option>
                        <option value="5">Coordinador</option>
                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="puesto">Puesto:</label>
                    <input type="text" id="puesto" name="puesto" placeholder="Ingrese el puesto" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" id="descripcion" name="descripcion" placeholder="Ingrese la descripción" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto:</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                </div>
            </div>
            <input type="submit" value="Añadir Empleado" class="btn-submit">
        </form>
        <div class="btn-back-container">
            <a href="empleados_usuarios.php" class="btn-back">Volver a la lista de empleados</a>
        </div>
    </div>
</body>
</html>


