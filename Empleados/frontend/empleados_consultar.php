<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/authadmin.php');
include('../backend/backend_consultar.php');

// Obtener los datos del empleado desde el backend
$empleado = validarYObtenerEmpleado($conn, $_GET['id']);
if (!$empleado) {
    echo "Empleado no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/empleadosconsultar.css">
    <title>Detalles del Empleado</title>
</head>
<body>
    <div class="form-container">
        <h2>Detalles del Empleado</h2>
        <div class="info-grid">
            <p><strong>Nombre de Usuario:</strong> <?php echo $empleado['NombreUsuario']; ?></p>
            <p><strong>Email:</strong> <?php echo $empleado['Email']; ?></p>
            <p><strong>Nombre:</strong> <?php echo $empleado['Nombre']; ?></p>
            <p><strong>Apellido:</strong> <?php echo $empleado['Apellido']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $empleado['Telefono']; ?></p>
            <p><strong>Rol:</strong> <?php echo $empleado['NombreRol']; ?></p>
            <p><strong>Puesto:</strong> <?php echo $empleado['Puesto']; ?></p>
            <p><strong>Descripción:</strong> <?php echo $empleado['Descripcion']; ?></p>
            <div class="photo-container">
                <p><strong>Foto:</strong></p>
                <img src="uploads/<?php echo $empleado['Foto']; ?>" alt="Foto del Empleado" class="employee-photo">
            </div>
        </div>
        <div class="btn-back-container">
        <a href="empleados_usuarios.php?consulted=true" class="btn-back">Volver a la lista de empleados</a>

        </div>
    </div>
</body>
</html>



