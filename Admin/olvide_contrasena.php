<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="cssLoginRegister.css">
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <form action="procesar_olvide_contrasena.php" method="POST">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" required>
            <input type="submit" value="Enviar enlace de recuperación" class="btn">
        </form>
    </div>
</body>
</html>
