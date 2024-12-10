<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si el usuario tiene el rol adecuado (Administrador, Vendedor, Servicio)
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4])) {
    echo "Acceso denegado.";
    exit;
}

$usuarioId = $_GET['id'];

// Consultar los datos existentes del cliente
$result = mysqli_query($conn, "SELECT * FROM Clientes WHERE UsuarioId = $usuarioId");
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

$cliente = mysqli_fetch_assoc($result);
if (!$cliente) {
    die("No se encontraron datos para este cliente.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $razonS = $_POST['razonS'];
    $direccionFiscal = $_POST['direccionFiscal'];
    $estado = $_POST['estado'];
    $rfc = $_POST['rfc'];
    $codigoPostal = $_POST['codigoPostal'];
    $usuarioFinal = $_POST['usuarioFinal'];

    // Construir la consulta de actualización
    $query = "UPDATE Clientes SET 
                NombreEmpresa='$nombreEmpresa', 
                RazonS='$razonS', 
                DireccionFiscal='$direccionFiscal', 
                Estado='$estado', 
                Rfc='$rfc', 
                CodigoPostal='$codigoPostal', 
                UsuarioFinal='$usuarioFinal' 
              WHERE UsuarioId = $usuarioId";

    if (mysqli_query($conn, $query)) {
        echo "Información actualizada correctamente.";
        header("Location: vendedor_usuarios.php");
        exit();
    } else {
        echo "Error al actualizar la información del cliente: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/vendedoreditar.css">
    <title>Editar Información del Cliente</title>
</head>
<body>
    <div class="form-container">
        <h2>Editar Información del Cliente</h2>
        <form action="" method="POST">
            <label for="nombreEmpresa">Nombre de la Empresa:</label>
            <input type="text" id="nombreEmpresa" name="nombreEmpresa" value="<?php echo $cliente['NombreEmpresa']; ?>" required><br>

            <label for="razonS">Razón Social:</label>
            <input type="text" id="razonS" name="razonS" value="<?php echo $cliente['RazonS']; ?>"><br>

            <label for="direccionFiscal">Dirección Fiscal:</label>
            <input type="text" id="direccionFiscal" name="direccionFiscal" value="<?php echo $cliente['DireccionFiscal']; ?>"><br>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php echo $cliente['Estado']; ?>"><br>

            <label for="rfc">RFC:</label>
            <input type="text" id="rfc" name="rfc" value="<?php echo $cliente['Rfc']; ?>"><br>

            <label for="codigoPostal">Código Postal:</label>
            <input type="text" id="codigoPostal" name="codigoPostal" value="<?php echo $cliente['CodigoPostal']; ?>"><br>

            <label for="usuarioFinal">Usuario Final:</label>
            <input type="text" id="usuarioFinal" name="usuarioFinal" value="<?php echo $cliente['UsuarioFinal']; ?>"><br>

            <input type="submit" value="Actualizar">
        </form>
        <a href="vendedor_usuarios.php" class="btn-back">Volver a la lista de clientes</a>
    </div>
</body>
</html>

