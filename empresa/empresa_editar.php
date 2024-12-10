<?php
include('../db.php'); // Conexión a la base de datos
include('../auth15.php');
include('backend_editar.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/empresaeditar.css">
    <title>Editar Empresa</title>
</head>
<body>
    <div class="form-container">
        <h2>Editar Empresa</h2>
        <form action="" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombreEmpresa">Nombre de la Empresa:</label>
                    <input type="text" name="nombreEmpresa" id="nombreEmpresa" value="<?php echo $empresa['NombreEmpresa']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="razonS">Razón Social:</label>
                    <input type="text" name="razonS" id="razonS" value="<?php echo $empresa['RazonS']; ?>">
                </div>

                <div class="form-group">
                    <label for="direccionFiscal">Dirección Fiscal:</label>
                    <input type="text" name="direccionFiscal" id="direccionFiscal" value="<?php echo $empresa['DireccionFiscal']; ?>">
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" name="estado" id="estado" value="<?php echo $empresa['Estado']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="rfc">RFC:</label>
                    <input type="text" name="rfc" id="rfc" value="<?php echo $empresa['Rfc']; ?>">
                </div>

                <div class="form-group">
                    <label for="codigoPostal">Código Postal:</label>
                    <input type="text" name="codigoPostal" id="codigoPostal" value="<?php echo $empresa['CodigoPostal']; ?>">
                </div>

                <div class="form-group">
                    <label for="usuarioFinal">Usuario Final:</label>
                    <input type="text" name="usuarioFinal" id="usuarioFinal" value="<?php echo $usuarioFinal['NombreCompleto']; ?>" disabled>
                </div>
            </div>
            <input type="submit" value="Actualizar" class="btn-submit">
        </form>

        <div class="btn-back-container">
            <a href="empresa_usuarios.php" class="btn-back">Volver a la lista de empresas</a>
        </div>
    </div>
</body>
</html>
