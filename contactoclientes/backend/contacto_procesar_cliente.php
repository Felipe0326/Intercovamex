<?php
include('../../db.php'); // Conexión a la base de datos
include('../../generar_folio.php'); // Archivo que contiene la función para generar el folio único
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del contacto
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $servicio_interes = $_POST['servicio_interes'];
    $titulo_contacto = isset($_POST['titulo_contacto']) ? $_POST['titulo_contacto'] : '';
    $n_parte = $_POST['n_parte'];
    $modelo = $_POST['modelo'];
    $n_serie = $_POST['n_serie'];
    $marca = $_POST['marca'];
    $descripcion = $_POST['descripcion'];

    // Datos de la cita (Día y Hora)
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];

    // Validar que el título de contacto esté presente
    if (empty($titulo_contacto)) {
        echo "El campo 'Título de Contacto' es obligatorio.";
        exit;
    }

    // Generar un folio único para el contacto
    $folioId = generarFolioUnico($conn); // Usar la función del archivo `codigodos.php`

    // Insertar en ContactoCliente con el ID del folio como clave foránea
    $sql_contacto_cliente = "INSERT INTO ContactoCliente (Nombre, Apellido, Telefono, Correo, ServicioInteres, TituloContacto, NParte, Modelo, NSerie, Marca, Descripcion, Estado, Dia, Hora, FolioIdContactoCliente) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pendiente', ?, ?, ?)";

    $stmt_contacto_cliente = mysqli_prepare($conn, $sql_contacto_cliente);
    mysqli_stmt_bind_param($stmt_contacto_cliente, 'sssssssssssssi', $nombre, $apellido, $telefono, $correo, $servicio_interes, $titulo_contacto, $n_parte, $modelo, $n_serie, $marca, $descripcion, $dia, $hora, $folioId);

    if (mysqli_stmt_execute($stmt_contacto_cliente)) {
        $contactoClienteId = mysqli_insert_id($conn);
        header("Location: ../frontend/contactos_clientes.php?contactoClienteId=" . $contactoClienteId);
        exit();
    } else {
        echo "Error al registrar el contacto en ContactoCliente: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt_contacto_cliente);
    mysqli_close($conn);
}
?>
