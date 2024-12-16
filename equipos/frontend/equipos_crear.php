<?php
include('../../db.php'); // Conexión a la base de datos
include('../../roles/auth15.php');
include('../backend/backend_crear.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/equiposcrear.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Añadir Nuevo Equipo</title>
</head>
<body>
    <div class="form-container">
        <h2>Añadir Nuevo Equipo</h2>
        <form action="equipos_crear.php" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nParte">Parte:</label>
                    <input type="text" id="nParte" name="nParte" placeholder="Ingrese la parte" required>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <input type="text" id="modelo" name="modelo" placeholder="Ingrese el modelo" required>
                </div>
                <div class="form-group">
                    <label for="nSerie">Número de Serie:</label>
                    <input type="text" id="nSerie" name="nSerie" placeholder="Ingrese el número de serie" required>
                </div>
                <div class="form-group">
                    <label for="marca">Marca:</label>
                    <input type="text" id="marca" name="marca" placeholder="Ingrese la marca" required>
                </div>
                <div class="form-group">
                    <label for="empresaEId">Empresa:</label>
                    <select id="empresaEId" name="empresaEId" required>
                        <?php while ($empresa = mysqli_fetch_assoc($empresas)) { ?>
                            <option value="<?php echo $empresa['Id']; ?>"><?php echo $empresa['NombreEmpresa']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <input type="text" id="observaciones" name="observaciones" placeholder="Ingrese observaciones">
                </div>
                <div class="form-group">
                    <label for="estatus">Estatus:</label>
                    <select id="estatus" name="estatus" required>
                        <option value="Activo">Activo</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="Añadir Equipo" class="btn-submit">
        </form>
        <div class="btn-back-container">
            <a href="equipos_usuarios.php" class="btn-back">Volver a la lista de equipos</a>
        </div>
    </div>
    <script>
    // Verifica si hay un parámetro de éxito o error en la URL
    const urlParams = new URLSearchParams(window.location.search);

    // Si hay un parámetro 'success' que indica que el equipo se añadió correctamente
    if (urlParams.has('success') && urlParams.get('success') === 'true') {
        Swal.fire({
            icon: 'success',
            title: '¡Equipo añadido!',
            text: 'El equipo se ha añadido correctamente.',
            showConfirmButton: false, // Oculta el botón "OK"
            timer: 3000, // Duración en milisegundos (3 segundos)
            timerProgressBar: true, // Muestra una barra de progreso
        });
    }

    // Si hay un parámetro 'error' que indica que hubo un error al añadir el equipo
    if (urlParams.has('error') && urlParams.get('error') === 'true') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al añadir el equipo. Por favor, intenta nuevamente.',
            showConfirmButton: true, // Muestra el botón "OK"
        });
    }

    // Si el parámetro de error es 'emptyfields' (campos vacíos)
    if (urlParams.has('error') && urlParams.get('error') === 'emptyfields') {
        Swal.fire({
            icon: 'warning',
            title: 'Campos obligatorios faltantes',
            text: 'Por favor, completa todos los campos obligatorios.',
            showConfirmButton: true,
        });
    }
</script>
</body>
</html>
