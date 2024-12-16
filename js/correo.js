function openEmail() {
    var email = document.getElementById("email").value;
    
    // Verificar si el correo está vacío
    if (email === "") {
        alert("Por favor, ingresa tu correo.");
        return;
    }

    // Validación para Gmail
    if (email.endsWith("@gmail.com")) {
        var gmailUrl = "https://mail.google.com/mail/u/0/#inbox";
        window.open(gmailUrl, '_blank');
    }
    // Validación para Outlook
    else if (email.endsWith("@outlook.com") || email.endsWith("@hotmail.com")) {
        var outlookUrl = "https://outlook.office365.com/mail/inbox";
        window.open(outlookUrl, '_blank');
    } else {
        alert("Solo se puede abrir Gmail o Outlook. Ingresa una dirección válida.");
    }
}

// Llamar a la función cuando se haga clic en el botón
document.getElementById("openButton").addEventListener("click", openEmail);
