function validarRegistro() {
    var nombreUsuario = document.getElementById("nombreUsuario").value.trim();
    var contrasena = document.getElementById("contrasena").value.trim();
    var email = document.getElementById("email").value.trim();
    var nombre = document.getElementById("nombre").value.trim();
    var apellido = document.getElementById("apellido").value.trim();
    var telefono = document.getElementById("telefono").value.trim();

    if (!nombreUsuario || !contrasena || !email || !nombre || !apellido || !telefono) {
        alert("Todos los campos son obligatorios.");
        return false;
    }

    if (contrasena.length < 8) {
        alert("La contraseña debe tener al menos 8 caracteres.");
        return false;
    }

    if (!/^\d{10}$/.test(telefono)) {
        alert("El número de teléfono debe tener exactamente 10 dígitos.");
        return false;
    }

    return true;
}

function validarLogin() {
    var email = document.getElementById("email").value.trim();
    var contrasena = document.getElementById("contrasena").value.trim();

    if (!email || !contrasena) {
        alert("Por favor, completa todos los campos.");
        return false;
    }

    return true;
}
