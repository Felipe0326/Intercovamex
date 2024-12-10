<?php
// Iniciar sesión
session_start();

// Verificar si hay una sesión activa
if (isset($_SESSION)) {
    // Destruir todas las variables de sesión
    session_unset();

    // Destruir la sesión
    session_destroy();
}

// Redirigir al usuario a la página de inicio de sesión
header("Location: login.html"); // Cambia "login.php" si tu página de inicio de sesión tiene otro nombre
exit;
?>
