let selectedId = null;

// Capturar selección en la tabla
document.querySelectorAll('.select-record').forEach(record => {
    record.addEventListener('change', function () {
        selectedId = this.value; // Guardar el ID del registro seleccionado
        console.log(`ID seleccionado: ${selectedId}`);
    });
});

// Acción para Crear
document.getElementById('crearCita').addEventListener('click', () => {
    window.location.href = `citas_crear.php`;
});

// Acción para Editar
document.getElementById('editarCita').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `citas_editar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para editar.');
    }
});

// Acción para Eliminar
document.getElementById('eliminarCita').addEventListener('click', () => {
    if (selectedId) {
        if (confirm('¿Está seguro de eliminar esta cita?')) {
            window.location.href = `citas_eliminar.php?id=${selectedId}`;
        }
    } else {
        alert('Seleccione un registro para eliminar.');
    }
});

// Acción para Consultar
document.getElementById('consultarCita').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `citas_consultar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para consultar.');
    }
});

// Acción para Proponer Nueva Cita
document.getElementById('proponerCita').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `citas_proponer.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para proponer una nueva cita.');
    }
});

// Acción para Historial de Cita
document.getElementById('historialCita').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `citas_historial.php?id=${selectedId}`;
    } else {
        window.location.href = `citas_historial.php`; // Sin selección, muestra el historial completo
    }
});

// Acción para Correo
document.getElementById('correoCita').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `citas_correo.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para enviar un correo.');
    }
});
