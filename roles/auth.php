<?php
    session_start();
    if (!isset($_SESSION['usuarioId'])) {
        header('Location: /portal_vfelipe/login.html');
        exit;
    }

    // Verificar roles permitidos
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1,2,3])) {
        echo "Acceso denegado.";
        exit;
    }

    // Obtener los datos del usuario logueado
    function getLoggedInUser($conn, $usuarioId) {
        // Determinar qué tabla usar según el rol del usuario
        if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2)) {
            // Para administradores y empleados
            $query = "SELECT * FROM usuarios WHERE ID = ?";
        } else {
            // Para clientes
            $query = "SELECT usuarios.Nombre, usuarios.Apellido, usuarios.Email,
                        usuarios.TelefonoUsuario, roles.NombreRol
                FROM usuarios 
                JOIN roles ON usuarios.Rol_ID = roles.ID
                WHERE usuarios.ID = ?";
        }
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $usuarioId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
?>