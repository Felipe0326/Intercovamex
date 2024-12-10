<?php
include('../db.php'); // Conexión a la base de datos
$errorMsg = ''; // Variable para almacenar mensajes de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $razonS = $_POST['razonS'];
    $direccionFiscal = $_POST['direccionFiscal'];
    $estado = $_POST['estado'];
    $rfc = $_POST['rfc'];
    $codigoPostal = $_POST['codigoPostal'];

    // Validar campos requeridos
    if (empty($nombreEmpresa) || empty($estado)) {
        $errorMsg = "Los campos Nombre de la Empresa y Estado son obligatorios.";
    } else {
        // Insertar los datos de la empresa
        $insertEmpresaQuery = "INSERT INTO Empresa (NombreEmpresa, RazonS, DireccionFiscal, Estado, Rfc, CodigoPostal)
                               VALUES ('$nombreEmpresa', '$razonS', '$direccionFiscal', '$estado', '$rfc', '$codigoPostal')";

        if (mysqli_query($conn, $insertEmpresaQuery)) {
            header("Location: empresa_usuarios.php");
            exit();
        } else {
            $errorMsg = "Error al crear empresa: " . mysqli_error($conn);
        }
    }
}
?>
