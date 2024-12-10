<?php
include('../db.php'); // Conexión a la base de datos
include('../generar_folio.php'); // Incluye la lógica para generar el folio único
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Obtener el ID del cliente logueado desde la sesión
$clienteId = $_SESSION['usuarioId'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['equipo']) && isset($_POST['dia']) && isset($_POST['hora'])) {
    $equipoId = $_POST['equipo'];
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];

    // Generar un folio único utilizando la función del archivo incluido
    $folioGenerado = generarFolioUnico($conn);

    // Consultar la empresa asociada al cliente
    $empresaQuery = "SELECT EmpresaId FROM Contacto WHERE Id = ?";
    $stmtEmpresa = mysqli_prepare($conn, $empresaQuery);
    mysqli_stmt_bind_param($stmtEmpresa, 'i', $clienteId);
    mysqli_stmt_execute($stmtEmpresa);
    $empresaResult = mysqli_stmt_get_result($stmtEmpresa);
    $empresaData = mysqli_fetch_assoc($empresaResult);
    $empresaId = $empresaData['EmpresaId'];

    // Insertar en la tabla Citas
    $sqlInsertCita = "INSERT INTO Citas (Dia, Hora, EquipoId, EmpresaId, ContactoId, Estado, FolioIdC) VALUES (?, ?, ?, ?, ?, 'Propuesta', ?)";
    $stmtInsert = mysqli_prepare($conn, $sqlInsertCita);
    mysqli_stmt_bind_param($stmtInsert, 'ssiiii', $dia, $hora, $equipoId, $empresaId, $clienteId, $folioGenerado);

    if (mysqli_stmt_execute($stmtInsert)) {
        // Actualizar la tabla Contacto para asociar el folio generado
        $sqlUpdateContacto = "UPDATE Contacto SET FolioIdContacto = ? WHERE Id = ?";
        $stmtUpdateContacto = mysqli_prepare($conn, $sqlUpdateContacto);
        mysqli_stmt_bind_param($stmtUpdateContacto, 'ii', $folioGenerado, $clienteId);

        if (mysqli_stmt_execute($stmtUpdateContacto)) {
            // Redirigir a la página de citas con el folio generado en la URL
            header("Location: citas_clienteslogin.php?folio=" . $folioGenerado);
            exit();
        } else {
            echo "Error al actualizar el folio en Contacto: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmtUpdateContacto);
    } else {
        echo "Error al agendar la cita: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmtInsert);
    mysqli_stmt_close($stmtEmpresa);
    mysqli_close($conn);
}
?>
