<?php
include('../../db.php'); // Conexión a la base de datos
function getCitaByFolio($conn, $folio) {
    $query = "
        SELECT 
            ContactoCliente.Id, Folio.CodigoFolio, ContactoCliente.Dia, ContactoCliente.Hora, 
            ContactoCliente.Estado, ContactoCliente.ServicioInteres, ContactoCliente.TituloContacto, 
            ContactoCliente.Nombre, ContactoCliente.Apellido, ContactoCliente.Telefono, ContactoCliente.Correo 
        FROM 
            ContactoCliente
        JOIN 
            Folio ON ContactoCliente.FolioIdContactoCliente = Folio.Id
        WHERE 
            Folio.CodigoFolio = ?
    ";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return null; // Retorna null si falla la preparación
    }

    // Vincular el parámetro y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 's', $folio);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Obtener los datos de la cita
    $cita = mysqli_fetch_assoc($result);

    // Cerrar la consulta
    mysqli_stmt_close($stmt);

    return $cita;
}
function updateCitaEstado($conn, $citaId, $estado) {
    $query = "UPDATE ContactoCliente SET Estado = ? WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return false; // Retorna false si falla la preparación
    }

    // Vincular los parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 'si', $estado, $citaId);
    $success = mysqli_stmt_execute($stmt);

    // Cerrar la consulta
    mysqli_stmt_close($stmt);

    return $success;
}
?>
