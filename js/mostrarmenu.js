// Mostrar/ocultar el menú desplegable
document.querySelectorAll('.nav-link.dropdown-toggle').forEach((dropdown) => {
    dropdown.addEventListener('click', function (e) {
        e.preventDefault(); // Evitar redireccionamientos
        const menu = this.nextElementSibling; // El menú desplegable
        menu.classList.toggle('show'); // Alternar la clase "show"
    });
});