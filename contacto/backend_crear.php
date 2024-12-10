<?php
include('../db.php'); // Conexión a la base de datos

// Consultar todas las empresas registradas
$queryEmpresas = "SELECT Id, NombreEmpresa FROM Empresa";
$resultEmpresas = mysqli_query($conn, $queryEmpresas);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitización y validación de los datos ingresados
    $contrasena = password_hash(trim($_POST['contrasena']), PASSWORD_DEFAULT);
    $email = trim($_POST['email']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $puesto = trim($_POST['puesto']);
    $estado = trim($_POST['estado']);
    $empresaId = intval($_POST['empresaId']);
    $usuarioFinal = isset($_POST['usuarioFinal']) ? 'Sí' : 'No';
    $rolId = 2; // Rol predeterminado como Cliente

    // Preparar la consulta para insertar el contacto
    $queryContacto = "INSERT INTO Contacto (Contrasena, Email, Nombre, Apellido, Telefono, Puesto, Estado, EmpresaId, UsuarioFinal, RolId) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $queryContacto);
    mysqli_stmt_bind_param($stmt, 'sssssssssi', $contrasena, $email, $nombre, $apellido, $telefono, $puesto, $estado, $empresaId, $usuarioFinal, $rolId);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: contacto_usuarios.php");
        exit();
    } else {
        echo "Error al crear el contacto: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>
