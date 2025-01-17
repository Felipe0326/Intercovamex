<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/auth.php');
include('../backend/backend_consultar.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/equiposconsultar.css">
    <title>Consultar Equipo</title>
</head>
<body>
    <div class="form-container">
        <h2>Detalles del Equipo</h2>
        <div class="info-grid">
            <p><strong>Parte:</strong> <?php echo $equipo['NParte']; ?></p>
            <p><strong>Modelo:</strong> <?php echo $equipo['Modelo']; ?></p>
            <p><strong>Número de Serie:</strong> <?php echo $equipo['NSerie']; ?></p>
            <p><strong>Marca:</strong> <?php echo $equipo['Marca']; ?></p>
            <p><strong>Empresa:</strong> <?php echo $equipo['NombreEmpresa']; ?></p>
            <p><strong>Observaciones:</strong> <?php echo $equipo['Observaciones']; ?></p>
            <p><strong>Estatus:</strong> <?php echo $equipo['Estatus']; ?></p>
        </div>

        <!-- Validación para mostrar los botones -->
        <?php if (in_array($_SESSION['rol'], [1, 3, 4, 5])): ?>
            <!-- Botón Volver visible para Administrador, Vendedor, Servicio y Coordinador -->
            <div class="btn-back-container">
            <a href="equipos_usuarios.php?consulted=true" class="btn-back">Volver a la lista de equipos</a>

            </div>
        <?php else: ?>
            <!-- Botón Iniciar Sesión visible para otros roles -->
            <div class="btn-back-container">
                <a href="../../login.html" class="btn-back">Iniciar Sesión</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

