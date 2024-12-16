<?php
session_start();

// Verificar si el usuario tiene el rol de Vendedor o Servicio
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1])) { // 3 = Vendedor, 4 = Servicio
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
        <li><a href="admin_usuarios.php">Gestión de Usuarios</a></li>
            <li><a href="equipos/equipos_usuarios.php">Gestión de Equipos</a></li>
            <li><a href="vendedor_usuarios.php">Gestión de Clientes</a></li>
            <li><a href="empresa/empresa_usuarios.php">Gestión de Empresas</a></li>
            <li><a href="Empleados/empleados_usuarios.php">Gestión de Empleados</a></li>
            <li><a href="citas/citas_usuarios.php">Gestión de Citas</a></li>
            <li><a href="contacto_cliente.php">Gestión de Solicitudes Clientes</a></li>
            <li><a href="solicitudes/administrar_contactos.php">Gestión de Solicitudes Clientes</a></li>
            <li><a href="citasclientes/citas_clientes.php">Gestión de Citas Clientes</a></li>
            <li><a href="historial_citas.php">Cliente historial_citas</a></li>
            <li><a href="citas_clientes_equipos.php">Mis equipos</a></li>
            <li><a href="citasclientes/citas_clienteslogin.php">Mis equipos</a></li>
            <li><a href="citasclientes/citas_clientesequipos.php">Mis equipos</a></li>
            <li><a href="contactoclientes/contactos_clientes.php">Mis equipos</a></li>
            <li><a href="contactoclientes/historial_citasclientes.php">Mis equipos</a></li>
        </ul>
    </nav>
</body>
</html>
