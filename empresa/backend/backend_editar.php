<?php
include('../../db.php'); // Conexión a la base de datos
$empresaId = $_GET['id'] ?? null;

if (!$empresaId) {
    die("Error: ID de empresa no especificado.");
}

// Obtener los datos de la empresa
$result = mysqli_query($conn, "SELECT * FROM Empresa WHERE Id = $empresaId");
if (!$result) {
    die("Error al obtener datos de la empresa: " . mysqli_error($conn));
}
$empresa = mysqli_fetch_assoc($result);

// Obtener el nombre y apellido del usuario final asociado a esta empresa en la tabla Contacto
$usuarioFinalQuery = "SELECT CONCAT(Nombre, ' ', Apellido) AS NombreCompleto FROM Contacto WHERE EmpresaId = {$empresaId} AND UsuarioFinal = 'Sí'";
$usuarioFinalResult = mysqli_query($conn, $usuarioFinalQuery);
if (!$usuarioFinalResult) {
    die("Error al obtener datos del usuario final: " . mysqli_error($conn));
}
$usuarioFinal = mysqli_fetch_assoc($usuarioFinalResult) ?: ['NombreCompleto' => 'No Asignado'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $razonS = $_POST['razonS'];
    $direccionFiscal = $_POST['direccionFiscal'];
    $estado = $_POST['estado'];
    $rfc = $_POST['rfc'];
    $codigoPostal = $_POST['codigoPostal'];

    // Validar campos requeridos
    if (empty($nombreEmpresa) || empty($estado)) {
        // Redirigir con parámetro de error si faltan campos obligatorios
        header("Location: empresa_usuarios.php?error=true");
        exit();
    }

    // Actualizar los datos de la empresa
    $updateQuery = "UPDATE Empresa SET 
                    NombreEmpresa='$nombreEmpresa', 
                    RazonS='$razonS', 
                    DireccionFiscal='$direccionFiscal', 
                    Estado='$estado', 
                    Rfc='$rfc', 
                    CodigoPostal='$codigoPostal' 
                    WHERE Id = $empresaId";

    if (mysqli_query($conn, $updateQuery)) {
        // Redirigir con parámetro de éxito si la actualización fue exitosa
        header("Location: empresa_usuarios.php?edited=true");
        exit();
    } else {
        // Redirigir con parámetro de error si hubo un problema al actualizar
        header("Location: empresa_usuarios.php?error=true");
        exit();
    }
}
?>
