<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/auth135.php');

// Obtener el ID del contacto
$id = $_GET['id'];

// Actualizar estado a "Rechazado"
$query = "UPDATE ContactoCliente SET Estado = 'Rechazado' WHERE Id = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    // Obtener el folio del contacto rechazado
    $folioQuery = "SELECT Folio FROM ContactoCliente WHERE Id = $id";
    $folioResult = mysqli_query($conn, $folioQuery);
    $folio = mysqli_fetch_assoc($folioResult)['Folio'];
    echo "Contacto con Folio $folio rechazado.";
} else {
    echo "Error al rechazar el contacto: " . mysqli_error($conn);
}

header("Location: ../frontend/administrar_contactos.php");
exit;
?>
