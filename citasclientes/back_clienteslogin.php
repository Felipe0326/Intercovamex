<?php
// Función para obtener los datos del cliente
function getClienteData($conn, $clienteId) {
    $clienteId = filter_var($clienteId, FILTER_VALIDATE_INT);
    if (!$clienteId) {
        return null;
    }

    $query = "SELECT 
                Contacto.TituloContacto, 
                Contacto.Nombre, 
                Contacto.Apellido, 
                Contacto.Telefono, 
                Contacto.Email, 
                Empresa.NombreEmpresa, 
                Contacto.ServicioInteres, 
                Empresa.Id AS EmpresaId
              FROM Contacto
              JOIN Empresa ON Contacto.EmpresaId = Empresa.Id
              WHERE Contacto.Id = ?";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'i', $clienteId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $data;
}

// Función para obtener los equipos asociados al cliente
function getEquiposByCliente($conn, $clienteId) {
    $query = "SELECT 
                Equipos.Id AS EquipoId, 
                Equipos.NParte, 
                Equipos.Marca, 
                Equipos.Modelo, 
                Equipos.NSerie
              FROM Equipos
              WHERE Equipos.EmpresaEId = (SELECT EmpresaId FROM Contacto WHERE Id = ?)";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, 'i', $clienteId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $equipos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $equipos[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $equipos;
}
?>

