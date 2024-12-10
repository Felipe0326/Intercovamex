<?php
include('db.php'); // Conexión a la base de datos
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Obtener los datos del usuario logueado, incluyendo su rol
$usuarioId = $_SESSION['usuarioId'];
$query = "SELECT Usuarios.Nombre, Usuarios.Apellido, Usuarios.Email, Usuarios.Telefono, Empleados.Puesto, Empleados.Foto, Roles.NombreRol
          FROM Usuarios
          LEFT JOIN Empleados ON Usuarios.Id = Empleados.UsuarioId
          JOIN Roles ON Usuarios.RolId = Roles.Id
          WHERE Usuarios.Id = $usuarioId";
$resultUser = mysqli_query($conn, $query);

if ($resultUser && mysqli_num_rows($resultUser) > 0) {
    $user = mysqli_fetch_assoc($resultUser);
} else {
    echo "Error al obtener los datos del usuario.";
    exit;
}

// Inicializar variables de búsqueda y ordenamiento
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Por defecto, orden ascendente

// Consulta para obtener todos los clientes y su información con filtros
$sql = "SELECT Usuarios.Id AS UsuarioId, Usuarios.NombreUsuario, Usuarios.Email, Usuarios.Nombre, Usuarios.Apellido, Usuarios.Telefono,
        Clientes.Id AS ClienteId, Clientes.NombreEmpresa, Clientes.RazonS, Clientes.DireccionFiscal, Clientes.Rfc, Clientes.CodigoPostal, Clientes.UsuarioFinal
        FROM Usuarios
        LEFT JOIN Clientes ON Usuarios.Id = Clientes.UsuarioId
        WHERE Usuarios.RolId = (SELECT Id FROM Roles WHERE NombreRol = 'Cliente')";

if ($searchTerm) {
    $sql .= " AND (Usuarios.NombreUsuario LIKE '%$searchTerm%' 
                  OR Usuarios.Email LIKE '%$searchTerm%' 
                  OR Usuarios.Nombre LIKE '%$searchTerm%' 
                  OR Usuarios.Apellido LIKE '%$searchTerm%' 
                  OR Usuarios.Telefono LIKE '%$searchTerm%' 
                  OR Clientes.NombreEmpresa LIKE '%$searchTerm%' 
                  OR Clientes.RazonS LIKE '%$searchTerm%' 
                  OR Clientes.DireccionFiscal LIKE '%$searchTerm%')";
}

// Añadir el ordenamiento
$sql .= " ORDER BY Usuarios.Nombre $order"; // Cambiar $order para invertir el orden

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="css/vendedorusuario.css">
</head>
<body>
    <!-- Header con navegación y datos del usuario -->
    <header>
        <div class="header-content">
            <h2>Gestión de Clientes</h2>
            <nav>
                <ul>
                    <li><a href="admin_usuarios.php">Gestor de Usuarios</a></li>
                    <li><a href="empleados_usuarios.php">Gestor de Empleados</a></li>
                    <li><a href="empresa_usuarios.php">Gestor de Empresas</a></li>
                    <li><a href="equipos_usuarios.php">Gestor de Equipos</a></li>
                    <li><a href="citas_usuarios.php">Gestor de Citas</a></li>
                </ul>
            </nav>
        </div>

        <!-- Tarjeta del usuario logueado -->
        <div class="user-info">
            <img src="uploads/<?php echo $user['Foto']; ?>" alt="Foto de usuario" class="user-photo">
            <div class="user-details">
                <p><strong><?php echo $user['Nombre'] . ' ' . $user['Apellido']; ?></strong></p>
                <p><?php echo $user['Email']; ?></p>
                <p><?php echo $user['Telefono']; ?></p>
                <p><?php echo $user['Puesto']; ?></p> <!-- Puesto del usuario logueado -->
                <p><?php echo $user['NombreRol']; ?></strong></p> <!-- Nombre del rol del usuario logueado -->
            </div>
            <a href="login.html" class="btn-logout">Cerrar Sesión</a>
        </div>
    </header>

    <!-- Contenedor para el formulario de búsqueda y el botón de crear cliente -->
    <div class="search-and-create">
        <!-- Botón para crear nuevo cliente -->
        <a href="vendedor_crear.php" class="btn-create">Añadir Información de Cliente</a>

        <!-- Formulario de búsqueda y filtro -->
        <form action="vendedor_usuarios.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Buscar clientes..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            
            <label for="order">Ordenar por:</label>
            <select id="order" name="order">
                <option value="ASC" <?php if ($order == 'ASC') echo 'selected'; ?>>A a Z</option>
                <option value="DESC" <?php if ($order == 'DESC') echo 'selected'; ?>>Z a A</option>
            </select>

            <button type="submit">Buscar</button>
        </form>
    </div>

    <!-- Tabla de resultados de clientes -->
    <table>
        <tr>
            <th>Nombre de Usuario</th>
            <th>Email</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Empresa</th>
            <th>Razón Social</th>
            <th>Dirección Fiscal</th>
            <th>RFC</th>
            <th>Código Postal</th>
            <th>Usuario Final</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['NombreUsuario']; ?></td>
            <td><?php echo $row['Email']; ?></td>
            <td><?php echo $row['Nombre']; ?></td>
            <td><?php echo $row['Apellido']; ?></td>
            <td><?php echo $row['Telefono']; ?></td>
            <td><?php echo $row['NombreEmpresa']; ?></td>
            <td><?php echo $row['RazonS']; ?></td>
            <td><?php echo $row['DireccionFiscal']; ?></td>
            <td><?php echo $row['Rfc']; ?></td>
            <td><?php echo $row['CodigoPostal']; ?></td>
            <td><?php echo $row['UsuarioFinal']; ?></td>
            <td class="actions">
                <a href="vendedor_editar.php?id=<?php echo $row['UsuarioId']; ?>">Editar</a>
                <a href="vendedor_eliminar.php?id=<?php echo $row['ClienteId']; ?>" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                <a href="vendedor_consultar.php?id=<?php echo $row['UsuarioId']; ?>">Consultar</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
