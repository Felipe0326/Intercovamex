let selectedId = null;

// Capturar selección en la tabla
document.querySelectorAll('.select-record').forEach(record => {
    record.addEventListener('change', function () {
        selectedId = this.value; // Guardar el ID del registro seleccionado
    });
});

// Acciones del menú desplegable
document.getElementById('crearContacto').addEventListener('click', () => {
    window.location.href = `contacto_crear.php`;
});

document.getElementById('editarContacto').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `contacto_editar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para editar.');
    }
});

document.getElementById('eliminarContacto').addEventListener('click', () => {
    if (selectedId) {
        if (confirm('¿Está seguro de eliminar este registro?')) {
            window.location.href = `contacto_eliminar.php?id=${selectedId}`;
        }
    } else {
        alert('Seleccione un registro para eliminar.');
    }
});

document.getElementById('consultarContacto').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `contacto_consultar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para consultar.');
    }
});
