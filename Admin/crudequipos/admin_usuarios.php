<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Obtener los datos del usuario logueado
$usuarioId = $_SESSION['usuarioId'];
$query = "SELECT Usuarios.NombreUsuario, Usuarios.Nombre, Usuarios.Apellido, Usuarios.Email, Usuarios.Telefono, Roles.NombreRol, Empleados.Puesto, Empleados.Foto
          FROM Usuarios 
          JOIN Roles ON Usuarios.RolId = Roles.Id
          LEFT JOIN Empleados ON Usuarios.Id = Empleados.UsuarioId
          WHERE Usuarios.Id = $usuarioId";
$resultUser = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($resultUser);

// Inicializar variables de búsqueda y filtro
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$roleFilter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Consultar todos los usuarios con sus roles, aplicando filtros de búsqueda y rol
$sql = "SELECT Usuarios.Id, NombreUsuario, Email, Nombre, Apellido, Telefono, Roles.NombreRol 
        FROM Usuarios 
        JOIN Roles ON Usuarios.RolId = Roles.Id
        WHERE (NombreUsuario LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%' OR Nombre LIKE '%$searchTerm%' OR Apellido LIKE '%$searchTerm%')";

if ($roleFilter) {
    $sql .= " AND Roles.NombreRol = '$roleFilter'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="css/adminusuario.css">
</head>
<body>

    <!-- Header con navegación y datos del usuario -->
    <header>
        <div class="header-content">
            <h2>Gestión de Usuarios</h2>
            <nav>
                <ul>
                    <li><a href="admin_usuarios.php">Gestor de Usuarios</a></li>
                    <li><a href="empleados_usuarios.php">Gestor de Empleados</a></li>
                    <li><a href="vendedor_usuarios.php">Gestor de Clientes</a></li>
                    <li><a href="equipos_usuarios.php">Gestor de Equipos</a></li>
                    <li><a href="citas_usuarios.php">Gestor de Citas</a></li>
                </ul>
            </nav>
        </div>

        <!-- Datos del usuario logueado -->
        <div class="user-info">
            <img src="uploads/<?php echo $user['Foto']; ?>" alt="Foto de usuario" class="user-photo">
            <div class="user-details">
                <p><strong><?php echo $user['Nombre'] . ' ' . $user['Apellido']; ?></strong></p>
                <p><?php echo $user['Email']; ?></p>
                <p><?php echo $user['Telefono']; ?></p>
                <p><?php echo $user['Puesto']; ?></p> <!-- Puesto añadido -->
                <p><?php echo $user['NombreRol']; ?></p>
            </div>
            <a href="login.html" class="btn-logout">Cerrar Sesión</a>
        </div>
    </header>

    <!-- Contenedor para el botón de crear usuario y el formulario de búsqueda -->
    <div class="actions-container">
        <a href="admin_crear_usuario.php" class="btn-create">Crear Nuevo Usuario</a>

        <!-- Formulario de búsqueda centrado -->
        <form action="admin_usuarios.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Buscar usuarios..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            
            <label for="filter">Filtrar por rol:</label>
            <select id="filter" name="filter">
                <option value="">Todos</option>
                <option value="Cliente" <?php if ($roleFilter == 'Cliente') echo 'selected'; ?>>Clientes</option>
                <option value="Servicio" <?php if ($roleFilter == 'Servicio') echo 'selected'; ?>>Servicio</option>
                <option value="Vendedor" <?php if ($roleFilter == 'Vendedor') echo 'selected'; ?>>Vendedor</option>
            </select>

            <button type="submit">Buscar</button>
        </form>
    </div>

    <!-- Tabla de resultados de usuarios -->
    <table>
        <tr>
            <th>Nombre de Usuario</th>
            <th>Email</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['NombreUsuario']; ?></td>
            <td><?php echo $row['Email']; ?></td>
            <td><?php echo $row['Nombre']; ?></td>
            <td><?php echo $row['Apellido']; ?></td>
            <td><?php echo $row['Telefono']; ?></td>
            <td><?php echo $row['NombreRol']; ?></td>
            <td>
                <a href="admin_editar_usuario.php?id=<?php echo $row['Id']; ?>">Editar</a>
                <a href="admin_eliminar_usuario.php?id=<?php echo $row['Id']; ?>" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                <a href="admin_consultar_usuario.php?id=<?php echo $row['Id']; ?>">Consultar</a>
                <a href="admin_ver_mas.php?id=<?php echo $row['Id']; ?>">Ver más</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
