document.getElementById('loginForm').addEventListener('submit', function (e) {
    // Previene el envío del formulario para realizar validaciones
    e.preventDefault();

    // Obtener los campos de correo y contraseña
    const email = document.querySelector('input[name="email"]');
    const contrasena = document.querySelector('input[name="contrasena"]');
    const errorContainer = document.getElementById('errorContainer');

    // Inicializar el estado de los errores
    let hasError = false;
    errorContainer.textContent = ''; // Limpia cualquier mensaje previo

    // Validar campo de correo electrónico
    if (!email.value.trim()) {
        hasError = true;
        email.classList.add('input-error');
        contrasena.value = ''; // Limpia el campo de contraseña si el email está vacío
    } else {
        email.classList.remove('input-error');
    }

    // Validar campo de contraseña
    if (!contrasena.value.trim()) {
        hasError = true;
        contrasena.classList.add('input-error');
        email.value = ''; // Limpia el campo de email si la contraseña está vacía
    } else {
        contrasena.classList.remove('input-error');
    }

    // Si hay errores, mostrar mensaje y evitar envío
    if (hasError) {
        errorContainer.textContent = 'Por favor, llena todos los campos.';
        errorContainer.style.display = 'block';
    } else {
        // Si todo está correcto, enviar el formulario
        e.target.submit();
    }
});
