<?php
include('../db.php'); // Conexión a la base de datos


$id = $_GET['id'];

// Verificar si el ID del contacto es válido
if (!isset($id) || !is_numeric($id)) {
    echo "ID de contacto no válido.";
    exit;
}

// Obtener información del contacto
$query = "SELECT * FROM Contacto WHERE Id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Verificar si el contacto existe
if (!$user) {
    echo "Contacto no encontrado.";
    exit;
}

// Obtener lista de empresas
$queryEmpresas = "SELECT Id, NombreEmpresa FROM Empresa";
$resultEmpresas = mysqli_query($conn, $queryEmpresas);

// Procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contrasena = $_POST['contrasena'];
    $email = trim($_POST['email']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $puesto = trim($_POST['puesto']);
    $estado = trim($_POST['estado']);
    $empresaId = intval($_POST['empresaId']);
    $usuarioFinal = isset($_POST['usuarioFinal']) ? 'Sí' : 'No';

    // Mantener la contraseña actual si no se ingresó una nueva
    if (empty($contrasena)) {
        $contrasena = $user['Contrasena'];
    } else {
        $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    }

    // Actualizar los datos del contacto
    $query = "UPDATE Contacto 
              SET Contrasena = ?, Email = ?, Nombre = ?, Apellido = ?, Telefono = ?, Puesto = ?, Estado = ?, EmpresaId = ?, UsuarioFinal = ? 
              WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssssssisi', $contrasena, $email, $nombre, $apellido, $telefono, $puesto, $estado, $empresaId, $usuarioFinal, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: contacto_usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el contacto: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>
