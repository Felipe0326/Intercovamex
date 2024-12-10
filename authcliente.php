<?php
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.html');
    exit;
}

// Verificar roles permitidos (Administrador: 1)
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1,2])) {
    echo "Acceso denegado.";
    exit;
}

// Función para obtener los datos del usuario logueado y su empresa
function getLoggedInUserAndClientData($conn, $usuarioId) {
    $query = "SELECT 
                  c.TituloContacto, 
                  c.Nombre, 
                  c.Apellido, 
                  c.Telefono, 
                  c.Email, 
                  c.ServicioInteres,
                  e.NombreEmpresa, 
                  e.RazonS, 
                  e.DireccionFiscal, 
                  e.Estado, 
                  e.Rfc, 
                  e.CodigoPostal,
                  e.Id AS EmpresaId
              FROM Contacto c
              JOIN Empresa e ON c.EmpresaId = e.Id
              WHERE c.Id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return null; // Retorna null si la consulta falla
    }

    // Vincular el parámetro y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 'i', $usuarioId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si hay resultados y devolver los datos
    return mysqli_fetch_assoc($result) ?: null;
}

// Obtener el ID del usuario logueado
$usuarioId = $_SESSION['usuarioId'];

// Llamar a la función para obtener los datos
$clienteData = getLoggedInUserAndClientData($conn, $usuarioId);

// Validar si se obtuvieron datos del cliente
if (!$clienteData) {
    echo "Error: No se encontraron datos del cliente.";
    exit;
}
?>
