<?php
include('../../db.php');

// Validar y obtener el ID de la cita
$citaId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($citaId <= 0) {
    echo "ID de cita no válido.";
    exit;
}

// Consultar la cita
$sql = "SELECT * FROM Citas WHERE Id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $citaId);
mysqli_stmt_execute($stmt);
$citaResult = mysqli_stmt_get_result($stmt);
$cita = mysqli_fetch_assoc($citaResult);
mysqli_stmt_close($stmt);

// Si no se encuentra la cita, muestra un mensaje de error con detalles
if (!$cita) {
    echo "Cita no encontrada con ID: " . $citaId;
    exit;
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];
    $equipoId = $_POST['equipoId'] ?: NULL;  // Permitir nulos
    $empresaId = $_POST['empresaId'];
    $contactoId = $_POST['contactoId'] ?: NULL;  // Permitir nulos
    $empleadoId = $_POST['empleadoId'] ?: NULL;  // Permitir nulos
    $estado = $_POST['estado'];  // Valor del estado
    $estatus = $_POST['estatus'];  // Valor del estatus

    // Actualizar la cita
    $updateSql = "UPDATE Citas SET Dia = ?, Hora = ?, EquipoId = ?, EmpresaId = ?, ContactoId = ?, EmpleadoId = ?, Estado = ?, Estatus = ? WHERE Id = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, 'ssiiisssi', $dia, $hora, $equipoId, $empresaId, $contactoId, $empleadoId, $estado, $estatus, $citaId);

    if (mysqli_stmt_execute($updateStmt)) {
        // Redirigir con parámetro 'edited' si la cita fue editada correctamente
        header("Location: citas_usuarios.php?edited=true");
        exit();
    } else {
        // Redirigir con parámetro 'error' si hubo un problema al editar la cita
        header("Location: citas_usuarios.php?error=true");
        exit();
    }
    mysqli_stmt_close($updateStmt);
}

// Consultar empresas (clientes)
$clientesQuery = "SELECT Empresa.Id AS EmpresaId, Empresa.NombreEmpresa FROM Empresa";
$clientesResult = mysqli_query($conn, $clientesQuery);

// Consultar equipos agrupados por EmpresaId
$equiposQuery = "SELECT * FROM Equipos";
$equiposResult = mysqli_query($conn, $equiposQuery);
$equipos = [];
while ($equipo = mysqli_fetch_assoc($equiposResult)) {
    $equipos[$equipo['EmpresaEId']][] = $equipo;
}

// Consultar empleados con nombre y apellido
$empleadoQuery = "SELECT Empleados.Id, Empleados.Nombre, Empleados.Apellido FROM Empleados";
$empleadoResult = mysqli_query($conn, $empleadoQuery);
?>



