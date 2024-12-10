<?php
session_start();

// Verificar si el usuario tiene el rol de Vendedor o Servicio
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [3, 4])) { // 3 = Vendedor, 4 = Servicio
    echo "Acceso denegado.";
    exit;
}

// Determinar el rol para mostrar un mensaje personalizado, si es necesario
$rol = $_SESSION['rol'];
$nombreRol = ($rol == 3) ? "Vendedor" : "Servicio";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de <?php echo $nombreRol; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Bienvenido al Panel de <?php echo $nombreRol; ?></h2>
    <nav>
        <ul>
            <li><a href="equipos_usuarios.php">Gestión de Equipos</a></li>
            <li><a href="vendedor_usuarios.php">Gestión de Usuarios</a></li>
        </ul>
    </nav>
</body>
</html>
