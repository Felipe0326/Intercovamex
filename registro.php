<?php
include('db.php'); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Almacenar la contraseña de forma segura
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    // Asignar el rol de Cliente automáticamente
    $rolId = 1; // ID del rol Cliente

    // Verificar que el email no esté registrado en la tabla Empleados
    $sql = "SELECT * FROM Empleados WHERE Email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "El correo ya está registrado.";
    } else {
        // Insertar nuevo empleado como Cliente
        $query = "INSERT INTO Empleados (NombreUsuario, Contrasena, Email, Nombre, Apellido, Telefono, RolId) 
                  VALUES ('$nombreUsuario', '$contrasena', '$email', '$nombre', '$apellido', '$telefono', '$rolId')";
        
        if (mysqli_query($conn, $query)) {
            echo "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            echo "Error en el registro: " . mysqli_error($conn);
        }
    }
}
?>
