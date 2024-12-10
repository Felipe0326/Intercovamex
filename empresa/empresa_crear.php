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
    <link rel="stylesheet" href="../css/empresacrear.css">
    <title>Añadir Empresa</title>
</head>
<body>
    <div class="form-container">
        <h2>Añadir Empresa</h2>

        <!-- Mostrar mensaje de error si ocurre un problema -->
        <?php if (isset($errorMsg) && $errorMsg): ?>
            <p class="error-message"><?php echo $errorMsg; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <!-- Contenedor de campos en formato grid -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombreEmpresa">Nombre de la Empresa:</label>
                    <input type="text" name="nombreEmpresa" id="nombreEmpresa" placeholder="Ingrese el nombre de la empresa" required>
                </div>
                <div class="form-group">
                    <label for="razonS">Razón Social:</label>
                    <input type="text" name="razonS" id="razonS" placeholder="Ingrese la razón social">
                </div>
                <div class="form-group">
                    <label for="direccionFiscal">Dirección Fiscal:</label>
                    <input type="text" name="direccionFiscal" id="direccionFiscal" placeholder="Ingrese la dirección fiscal">
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" name="estado" id="estado" placeholder="Ingrese el estado" required>
                </div>
                <div class="form-group">
                    <label for="rfc">RFC:</label>
                    <input type="text" name="rfc" id="rfc" placeholder="Ingrese el RFC">
                </div>
                <div class="form-group">
                    <label for="codigoPostal">Código Postal:</label>
                    <input type="text" name="codigoPostal" id="codigoPostal" placeholder="Ingrese el código postal">
                </div>
                <div class="form-group">
                    <label for="usuarioFinal">Usuario Final:</label>
                    <input type="text" id="usuarioFinal" value="Asignar en el CRUD de Contacto" disabled>
                </div>
            </div>
            <input type="submit" value="Guardar" class="btn-submit">
        </form>
        
        <!-- Contenedor del botón de regresar -->
        <div class="btn-back-container">
            <a href="empresa_usuarios.php" class="btn-back">Volver a la lista de empresas</a>
        </div>
    </div>
</body>
</html>

