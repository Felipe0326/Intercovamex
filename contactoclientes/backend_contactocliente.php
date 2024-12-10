<?php
include('../db.php'); // Conexión a la base de datos
function getFolioGenerado($conn, $contactoClienteId) {
    $query = "
        SELECT Folio.CodigoFolio 
        FROM Folio
        INNER JOIN ContactoCliente ON ContactoCliente.FolioIdContactoCliente = Folio.Id
        WHERE ContactoCliente.Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return null; // Retorna null si la consulta falla
    }

    // Vincular el parámetro y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 'i', $contactoClienteId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Obtener el folio generado si existe
    $folioGenerado = null;
    if ($row = mysqli_fetch_assoc($result)) {
        $folioGenerado = htmlspecialchars($row['CodigoFolio']);
    }

    // Cerrar la consulta
    mysqli_stmt_close($stmt);

    return $folioGenerado;
}
?>
