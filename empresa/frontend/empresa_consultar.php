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
    <link rel="stylesheet" href="../../css/empresaconsultar.css">
    <title>Consultar Empresa</title>
</head>
<body>
    <div class="form-container">
        <h2>Información de la Empresa</h2>
        <div class="info-grid">
            <p><strong>Nombre de la Empresa:</strong> <?php echo $empresa['NombreEmpresa']; ?></p>
            <p><strong>Razón Social:</strong> <?php echo $empresa['RazonS']; ?></p>
            <p><strong>Dirección Fiscal:</strong> <?php echo $empresa['DireccionFiscal']; ?></p>
            <p><strong>Estado:</strong> <?php echo $empresa['Estado']; ?></p>
            <p><strong>RFC:</strong> <?php echo $empresa['Rfc']; ?></p>
            <p><strong>Código Postal:</strong> <?php echo $empresa['CodigoPostal']; ?></p>
            <p><strong>Usuario Final:</strong> <?php echo $empresa['UsuarioFinalNombre'] ? $empresa['UsuarioFinalNombre'] : 'No Asignado'; ?></p>
        </div>
        <div class="btn-back-container">
        <a href="empresa_usuarios.php?consulted=true" class="btn-back">Volver a la lista de empresas</a>

        </div>
    </div>
</body>
</html>