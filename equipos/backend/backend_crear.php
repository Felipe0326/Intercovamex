<?php
include('../../db.php'); // Conexión a la base de datos

// Si se envía el formulario, procesar la solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nParte = $_POST['nParte'];
    $modelo = $_POST['modelo'];
    $nSerie = $_POST['nSerie'];
    $marca = $_POST['marca'];
    $empresaEId = $_POST['empresaEId'];
    $observaciones = $_POST['observaciones'];
    $estatus = $_POST['estatus'];

    // Validar campos obligatorios
    if (empty($nParte) || empty($modelo) || empty($nSerie) || empty($marca) || empty($empresaEId) || empty($estatus)) {
        // Si algún campo obligatorio está vacío, redirigir con un mensaje de error
        header("Location: equipos_crear.php?error=emptyfields");
        exit();
    }

    // Consulta para insertar el nuevo equipo
    $query = "INSERT INTO Equipos (NParte, Modelo, NSerie, Marca, EmpresaEId, Observaciones, Estatus) 
              VALUES ('$nParte', '$modelo', '$nSerie', '$marca', '$empresaEId', '$observaciones', '$estatus')";

   // Si el equipo fue añadido correctamente
if (mysqli_query($conn, $query)) {
    header("Location: equipos_usuarios.php?added=true"); // Redirige con éxito
    exit();
} else {
    header("Location: equipos_usuarios.php?error=true"); // Redirige con error
    exit();
}

}

// Obtener la lista de empresas para el campo de selección
$empresas = mysqli_query($conn, "SELECT Id, NombreEmpresa FROM Empresa");
if (!$empresas) {
    die("Error al obtener la lista de empresas: " . mysqli_error($conn));
}
?>

