<?php
function obtenerSolicitudesPendientes($conn) {
    $query = "SELECT COUNT(*) AS pendientes FROM ContactoCliente WHERE Estado = 'Pendiente'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['pendientes'];
    }
    return 0; // Retorna 0 si no hay resultados o hay un error
}
?>
