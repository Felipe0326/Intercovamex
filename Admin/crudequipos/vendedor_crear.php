<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si el usuario tiene el rol adecuado (Administrador, Vendedor, Servicio)
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4])) {
    echo "Acceso denegado.";
    exit;
}

// Consulta para obtener los usuarios con rol de Cliente que aún no tienen información en la tabla Clientes
$sql = "SELECT Id, NombreUsuario FROM Usuarios 
        WHERE RolId = (SELECT Id FROM Roles WHERE NombreRol = 'Cliente') 
        AND Id NOT IN (SELECT UsuarioId FROM Clientes)";
$result = mysqli_query($conn, $sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = $_POST['usuarioId'];
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $razonS = $_POST['razonS'];
    $direccionFiscal = $_POST['direccionFiscal'];
    $estado = $_POST['estado'];
    $rfc = $_POST['rfc'];
    $codigoPostal = $_POST['codigoPostal'];
    $usuarioFinal = $_POST['usuarioFinal'];

    $query = "INSERT INTO Clientes (UsuarioId, NombreEmpresa, RazonS, DireccionFiscal, Estado, Rfc, CodigoPostal, UsuarioFinal)
              VALUES ('$usuarioId', '$nombreEmpresa', '$razonS', '$direccionFiscal', '$estado', '$rfc', '$codigoPostal', '$usuarioFinal')";

    if (mysqli_query($conn, $query)) {
        header("Location: vendedor_usuarios.php");
        exit();
    } else {
        echo "Error al añadir la información del cliente: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/vendedorcrear.css">
    <title>Añadir Información de Cliente</title>
</head>
<body>
    <div class="form-container">
        <h2>Añadir Información de Cliente</h2>
        <form action="vendedor_crear.php" method="POST">
            <label for="usuarioId">Usuario Cliente:</label>
            <select id="usuarioId" name="usuarioId" required>
                <?php while ($user = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $user['Id']; ?>"><?php echo $user['NombreUsuario']; ?></option>
                <?php } ?>
            </select><br>

            <label for="nombreEmpresa">Nombre de la Empresa:</label>
            <input type="text" id="nombreEmpresa" name="nombreEmpresa" required><br>

            <label for="razonS">Razón Social:</label>
            <input type="text" id="razonS" name="razonS"><br>

            <label for="direccionFiscal">Dirección Fiscal:</label>
            <input type="text" id="direccionFiscal" name="direccionFiscal"><br>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado"><br>

            <label for="rfc">RFC:</label>
            <input type="text" id="rfc" name="rfc"><br>

            <label for="codigoPostal">Código Postal:</label>
            <input type="text" id="codigoPostal" name="codigoPostal"><br>

            <label for="usuarioFinal">Usuario Final:</label>
            <input type="text" id="usuarioFinal" name="usuarioFinal"><br>

            <input type="submit" value="Guardar">
        </form>
        <a href="vendedor_usuarios.php" class="btn-back">Volver a la lista de clientes</a>
    </div>
</body>
</html>
