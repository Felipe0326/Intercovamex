<?php
include('db.php'); // Conexión a la base de datos
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar entradas para prevenir inyecciones SQL
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);

    // Consulta para verificar si el usuario es un cliente o empleado
    $sqlCliente = "SELECT * FROM Contacto WHERE Email = '$email' AND RolId = 2";
    $resultCliente = mysqli_query($conn, $sqlCliente);
    $cliente = mysqli_fetch_assoc($resultCliente);

    if ($cliente && password_verify($contrasena, $cliente['Contrasena'])) {
        // Si el usuario es un cliente
        $_SESSION['usuarioId'] = $cliente['Id'];
        $_SESSION['rol'] = $cliente['RolId'];
        
        // Redirigir a la vista de cliente
        header("Location: ../citasclientes/frontend/citas_clienteslogin.php");
        exit();
    } else {
        // Si no es cliente, verificar en la tabla de Empleados
        $sqlEmpleado = "SELECT * FROM Empleados WHERE Email = '$email'";
        $resultEmpleado = mysqli_query($conn, $sqlEmpleado);
        $empleado = mysqli_fetch_assoc($resultEmpleado);

        if ($empleado && password_verify($contrasena, $empleado['Contrasena'])) {
            $_SESSION['usuarioId'] = $empleado['Id'];
            $_SESSION['rol'] = $empleado['RolId'];


            // Redirigir según el rol
            switch ($empleado['RolId']) {
                case 1: header("Location: ../dashboard/frontend/dashboard.php"); break; // Administrador
                case 3: header("Location: ../dashboard/frontend/dashboard.php"); break; // Vendedor
                case 4: header("Location: ../dashboard/frontend/dashboard.php"); break; // Servicio
                case 5: header("Location: ../dashboard/frontend/dashboard.php"); break; // Coordinador
                default: header("Location: login.html"); break;
            }
            exit();
        } else {
            // Si las credenciales no coinciden, redirigir al login.html con un mensaje de error
            header("Location: login.html?error=invalid");
            exit();
        }
    }
}
?>

