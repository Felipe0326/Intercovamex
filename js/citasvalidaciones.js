// citasjavascript.js

// Validar los formularios de crear y editar citas
function validarFormulario() {
    var dia = document.getElementById("dia").value;
    var hora = document.getElementById("hora").value;
    var sucursal = document.getElementById("sucursal").value;

    // Comprobar que todos los campos están llenos
    if (!dia || !hora || !sucursal) {
        alert("Todos los campos son obligatorios.");
        return false;
    }

    // Comprobar que la fecha seleccionada no sea anterior a la actual
    var fechaActual = new Date().toISOString().split('T')[0];
    if (dia < fechaActual) {
        alert("No puedes seleccionar una fecha pasada.");
        return false;
    }

    return true; // Todos los campos son válidos
}

// Confirmar la eliminación de una cita
function confirmarEliminacion() {
    return confirm("¿Estás seguro de que deseas eliminar esta cita?");
}
