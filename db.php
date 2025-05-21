<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_portal";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Función para obtener el número de notificaciones pendientes
//function obtenerNotificacionesPendientes($conn) {
  //  $query = "SELECT COUNT(*) AS total FROM ContactoCliente WHERE Estado = 'Pendiente'";
    //$result = mysqli_query($conn, $query);
    //$row = mysqli_fetch_assoc($result);
    //return $row['total'];
//}
?>
