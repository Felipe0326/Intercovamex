<?php
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['usuarioId'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Verificar si el usuario tiene el rol adecuado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    echo "Acceso denegado.";
    exit;
}
?>