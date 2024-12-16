<?php
include('../../db.php'); // Conexión a la base de datos

// Inicializar variables
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM Equipos WHERE Id = $id");
$equipo = mysqli_fetch_assoc($result);

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nParte = $_POST['nParte'];
    $modelo = $_POST['modelo'];
    $nSerie = $_POST['nSerie'];
    $marca = $_POST['marca'];
    $empresaEId = $_POST['empresaEId'];
    $observaciones = $_POST['observaciones'];
    $estatus = $_POST['estatus'];

    // Consulta para actualizar el equipo
    $query = "UPDATE Equipos SET 
                NParte='$nParte', 
                Modelo='$modelo', 
                NSerie='$nSerie', 
                Marca='$marca', 
                EmpresaEId='$empresaEId', 
                Observaciones='$observaciones', 
                Estatus='$estatus' 
              WHERE Id = $id";
    
   // Si el equipo fue editado correctamente
if (mysqli_query($conn, $query)) {
    header("Location: equipos_usuarios.php?edited=true"); // Redirige con éxito
    exit();
} else {
    header("Location: equipos_usuarios.php?error=true"); // Redirige con error
    exit();
}

}

// Obtener la lista de empresas
$empresas = mysqli_query($conn, "SELECT Id, NombreEmpresa FROM Empresa");
?>

