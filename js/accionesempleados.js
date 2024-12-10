let selectedId = null;

// Capturar selección en la tabla
document.querySelectorAll('.select-record').forEach(record => {
    record.addEventListener('change', function () {
        selectedId = this.value; // Guardar el ID del registro seleccionado
        console.log(`ID seleccionado: ${selectedId}`);
    });
});

// Acción para Crear
document.getElementById('crearEmpleado').addEventListener('click', () => {
    window.location.href = `empleados_crear.php`;
});

// Acción para Editar
document.getElementById('editarEmpleado').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `empleados_editar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para editar.');
    }
});

// Acción para Eliminar
document.getElementById('eliminarEmpleado').addEventListener('click', () => {
    if (selectedId) {
        if (confirm('¿Está seguro de eliminar este registro?')) {
            window.location.href = `empleados_eliminar.php?id=${selectedId}`;
        }
    } else {
        alert('Seleccione un registro para eliminar.');
    }
});

// Acción para Consultar
document.getElementById('consultarEmpleado').addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `empleados_consultar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para consultar.');
    }
});
