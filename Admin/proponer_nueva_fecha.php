<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dia']) && isset($_POST['hora'])) {
    $citaId = $_POST['citaId'];
    $nuevaDia = $_POST['dia'];
    $nuevaHora = $_POST['hora'];

    $sql = "UPDATE Citas SET Dia = ?, Hora = ?, Estado = 'Propuesta' WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $nuevaDia, $nuevaHora, $citaId);

    if (mysqli_stmt_execute($stmt)) {
        // Notificación vía correo aquí
        echo "Fecha propuesta enviada.";
    }
    mysqli_stmt_close($stmt);
}
?>
