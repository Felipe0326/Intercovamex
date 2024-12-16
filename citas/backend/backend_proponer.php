<?php
include('../../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dia']) && isset($_POST['hora'])) {
    $citaId = isset($_POST['citaId']) ? (int)$_POST['citaId'] : 0;
    $nuevaDia = $_POST['dia'];
    $nuevaHora = $_POST['hora'];

    // Validar datos básicos
    if ($citaId <= 0) {
        echo "ID de cita no válido.";
        exit;
    }

    // Verificar si la cita existe
    $checkQuery = "SELECT * FROM Citas WHERE Id = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, 'i', $citaId);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($result) > 0) {
        // Actualizar la cita con la nueva fecha y estado "Propuesta"
        $sql = "UPDATE Citas SET Dia = ?, Hora = ?, Estado = 'Propuesta' WHERE Id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $nuevaDia, $nuevaHora, $citaId);

        if (mysqli_stmt_execute($stmt)) {
            // Enviar notificación al cliente (simulación)
            $clienteEmail = obtenerEmailCliente($citaId);
            if ($clienteEmail) {
                $mensaje = "Estimado cliente, se le propone una nueva cita el día $nuevaDia a las $nuevaHora.";
                mail($clienteEmail, "Propuesta de nueva fecha de cita", $mensaje);
            }
            // Redirigir con parámetro 'propuesta=success' si la cita fue propuesta correctamente
            header("Location: citas_usuarios.php?proposed=true");
            exit();
        } else {
            // Redirigir con parámetro 'error' si hubo un problema al proponer la cita
            header("Location: citas_usuarios.php?error=true");
            exit();
        }

        mysqli_stmt_close($stmt);
    } 
}

// Función para obtener el correo del cliente (para el correo de notificación)
function obtenerEmailCliente($citaId) {
    global $conn;
    $sql = "SELECT Contacto.Email FROM Contacto
            JOIN Citas ON Citas.ContactoId = Contacto.Id
            WHERE Citas.Id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $citaId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $cliente = mysqli_fetch_assoc($result);
    return $cliente['Email'] ?? null;
}
?>

