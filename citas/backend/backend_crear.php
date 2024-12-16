<?php
include('../../db.php');
include('../../generar_folio.php'); // Incluye el archivo que genera e inserta un folio único

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];
    $equipoId = $_POST['equipoId'] ?: NULL; // Permitir nulos
    $empresaId = $_POST['usuarioId'];
    $contactoId = $_POST['contactoId'] ?: NULL; // Permitir nulos
    $empleadoId = $_POST['empleadoId'] ?: NULL; // Permitir nulos
    $estado = "Propuesta"; // Estado predeterminado
    $estatus = "En espera"; // Estatus predeterminado

    // Generar un folio único y obtener su ID
    $folioId = generarFolioUnico($conn); // Función para generar e insertar el folio único

    // Insertar la cita en la tabla Citas
    $stmt = mysqli_prepare($conn, "INSERT INTO Citas (Dia, Hora, EquipoId, EmpresaId, ContactoId, EmpleadoId, Estado, Estatus, FolioIdC) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssiiisssi', $dia, $hora, $equipoId, $empresaId, $contactoId, $empleadoId, $estado, $estatus, $folioId);

    if (mysqli_stmt_execute($stmt)) {
        // Redirigir con parámetro 'added' si la cita fue añadida correctamente
        header("Location: citas_usuarios.php?added=true");
        exit();
    } else {
        // Redirigir con parámetro 'error' si hubo un problema al añadir la cita
        header("Location: citas_usuarios.php?error=true");
        exit();
    }
    mysqli_stmt_close($stmt);
}

// Consultar todas las empresas (clientes)
$clientesQuery = "SELECT Empresa.Id AS EmpresaId, Empresa.NombreEmpresa FROM Empresa";
$clientesResult = mysqli_query($conn, $clientesQuery);

// Consultar todos los equipos
$equiposQuery = "SELECT * FROM Equipos";
$equiposResult = mysqli_query($conn, $equiposQuery);
$equipos = [];
while ($equipo = mysqli_fetch_assoc($equiposResult)) {
    $equipos[$equipo['EmpresaEId']][] = $equipo;
}

// Consultar contactos
$contactoQuery = "SELECT Contacto.Id, Contacto.Nombre, Contacto.EmpresaId FROM Contacto";
$contactoResult = mysqli_query($conn, $contactoQuery);

// Consultar empleados
$empleadoQuery = "SELECT Empleados.Id, Empleados.Nombre, Empleados.Apellido FROM Empleados";
$empleadoResult = mysqli_query($conn, $empleadoQuery);
?>

