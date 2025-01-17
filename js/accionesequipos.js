let selectedId = null;

// Capturar selección en la tabla
document.querySelectorAll('.select-record').forEach(record => {
    record.addEventListener('change', function () {
        selectedId = this.value; // Guardar el ID del registro seleccionado
        console.log(`ID seleccionado: ${selectedId}`);
    });
});

// Acción para Crear
document.getElementById('crearEquipo')?.addEventListener('click', () => {
    window.location.href = `equipos_crear.php`; // Redirige directamente a la creación
});

// Acción para Editar
document.getElementById('editarEquipo')?.addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `equipos_editar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para editar.');
    }
});

// Acción para Eliminar
document.getElementById('eliminarEquipo')?.addEventListener('click', () => {
    if (selectedId) {
        if (confirm('¿Está seguro de eliminar este registro?')) {
            window.location.href = `equipos_eliminar.php?id=${selectedId}`;
        }
    } else {
        alert('Seleccione un registro para eliminar.');
    }
});

// Acción para Consultar (Equipo específico)
document.getElementById('consultarEquipo')?.addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `equipos_consultar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para consultar.');
    }
});

// Acción para Consultar Otro (Acción adicional para consultar otro equipo)
document.getElementById('consultarOtroEquipo')?.addEventListener('click', () => {
    if (selectedId) {
        // Si se desea abrir otro equipo en una vista diferente o realizar otra acción
        window.location.href = `equipos_consultar_otro.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para consultar.');
    }
});