<?php
include('../db.php'); // Conexión a la base de datos
include('../auth.php');
include('backend_consultar.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/adminconsultar.css">
    <title>Consultar Contacto</title>
</head>
<body>
    <div class="form-container">
        <h2>Información del Contacto</h2>

        <div class="form-grid">
            <div class="form-group">
                <label><strong>Correo Electrónico:</strong></label>
                <p><?php echo htmlspecialchars($user['Email']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Nombre:</strong></label>
                <p><?php echo htmlspecialchars($user['Nombre']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Apellido:</strong></label>
                <p><?php echo htmlspecialchars($user['Apellido']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Teléfono:</strong></label>
                <p><?php echo htmlspecialchars($user['Telefono']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Puesto:</strong></label>
                <p><?php echo htmlspecialchars($user['Puesto']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Estado:</strong></label>
                <p><?php echo htmlspecialchars($user['Estado']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Empresa:</strong></label>
                <p><?php echo htmlspecialchars($user['NombreEmpresa']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Rol:</strong></label>
                <p><?php echo htmlspecialchars($user['NombreRol']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>¿Es Usuario Final?:</strong></label>
                <p><?php echo $user['UsuarioFinal'] === 'Sí' ? 'Sí' : 'No'; ?></p>
            </div>
        </div>

        <div class="btn-back-container">
            <a href="contacto_usuarios.php" class="btn-back">Volver a la lista de contactos</a>
        </div>
    </div>
</body>
</html>

