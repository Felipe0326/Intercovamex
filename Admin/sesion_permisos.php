<?php
// Iniciar sesión
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Verificar si el usuario tiene el rol adecuado para acceder (1, 3, 4, 5)
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], [1, 3, 4, 5])) {
    echo "Acceso denegado.";
    exit;
}
?>
