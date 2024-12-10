<?php
// Consulta para contar solicitudes de contacto pendientes
$solicitudesPendientesQuery = "SELECT COUNT(*) AS pendientes FROM ContactoCliente WHERE Estado = 'Pendiente'";
$resultSolicitudes = mysqli_query($conn, $solicitudesPendientesQuery);
$solicitudesPendientes = mysqli_fetch_assoc($resultSolicitudes)['pendientes'];
?>