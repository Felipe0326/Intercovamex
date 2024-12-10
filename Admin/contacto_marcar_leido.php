<?php
include('db.php');

$id = $_GET['id'];

// Marcar la notificación como leída
$sql = "UPDATE Notificaciones SET Estado = 'Leído' WHERE Id = $id";
mysqli_query($conn, $sql);

header("Location: notificaciones.php");
exit();
?>
