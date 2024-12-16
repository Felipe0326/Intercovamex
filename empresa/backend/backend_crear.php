<?php
include('../../db.php'); // Conexión a la base de datos
$errorMsg = ''; // Variable para almacenar mensajes de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreEmpresa = mysqli_real_escape_string($conn, $_POST['nombreEmpresa']);
    $razonS = mysqli_real_escape_string($conn, $_POST['razonS']);
    $direccionFiscal = mysqli_real_escape_string($conn, $_POST['direccionFiscal']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $rfc = mysqli_real_escape_string($conn, $_POST['rfc']);
    $codigoPostal = mysqli_real_escape_string($conn, $_POST['codigoPostal']);

    // Validar campos requeridos
    if (empty($nombreEmpresa) || empty($estado)) {
        $errorMsg = "Los campos Nombre de la Empresa y Estado son obligatorios.";
    } else {
        // Insertar los datos de la empresa
        $insertEmpresaQuery = "INSERT INTO Empresa (NombreEmpresa, RazonS, DireccionFiscal, Estado, Rfc, CodigoPostal)
                               VALUES ('$nombreEmpresa', '$razonS', '$direccionFiscal', '$estado', '$rfc', '$codigoPostal')";

        if (mysqli_query($conn, $insertEmpresaQuery)) {
            // Redirigir con parámetro de éxito
            header("Location: empresa_usuarios.php?added=true");
            exit();
        } else {
            $errorMsg = "Error al crear empresa: " . mysqli_error($conn);
            // Redirigir con parámetro de error
            header("Location: empresa_usuarios.php?error=true");
            exit();
        }
    }
}
?>

