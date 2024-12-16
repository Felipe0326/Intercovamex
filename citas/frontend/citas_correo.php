<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $errorMessage = '';

    // Verificar si el campo de correo está vacío
    if (empty($email) || empty($subject) || empty($body)) {
        $errorMessage = "Por favor, ingresa todos los campos.";
    } else {
        // Validación para Gmail
        if (strpos($email, '@gmail.com') !== false) {
            // Construir la URL mailto para Gmail
            $mailtoLink = "https://mail.google.com/mail/u/0/?view=cm&fs=1&to=" . urlencode($email) . "&su=" . urlencode($subject) . "&body=" . urlencode($body);
            header("Location: " . $mailtoLink);
            exit();
        }
        // Validación para Outlook
        elseif (strpos($email, '@outlook.com') !== false || strpos($email, '@hotmail.com') !== false) {
            // Construir la URL mailto para Outlook
            $mailtoLink = "https://outlook.office365.com/mail/deeplink/compose?to=" . urlencode($email) . "&subject=" . urlencode($subject) . "&body=" . urlencode($body);
            header("Location: " . $mailtoLink);
            exit();
        } else {
            $errorMessage = "Solo se puede abrir Gmail o Outlook. Ingresa una dirección válida.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Correo</title>
</head>
<body>

    <div id="emailForm">
        <h2>Abrir tu Correo con Asunto y Descripción</h2>
        <form method="POST" action="">
            <label for="email">Ingresa tu correo:</label>
            <input type="email" name="email" id="email" placeholder="ejemplo@gmail.com" value="<?php echo isset($email) ? $email : ''; ?>" required><br><br>

            <label for="subject">Asunto:</label>
            <input type="text" name="subject" id="subject" placeholder="Asunto del correo" value="<?php echo isset($subject) ? $subject : ''; ?>" required><br><br>

            <label for="body">Cuerpo del correo:</label>
            <textarea name="body" id="body" placeholder="Escribe tu mensaje aquí" required><?php echo isset($body) ? $body : ''; ?></textarea><br><br>

            <button type="submit">Abrir Bandeja de Entrada</button>
        </form>

        <?php if (!empty($errorMessage)): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

    </div>

</body>
</html>
