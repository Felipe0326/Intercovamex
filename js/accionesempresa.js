let selectedId = null;

// Capturar selección en la tabla
document.querySelectorAll('.select-record').forEach(record => {
    record.addEventListener('change', function () {
        selectedId = this.value; // Guardar el ID del registro seleccionado
        console.log(`ID seleccionado: ${selectedId}`);
    });
});

// Acción para Crear Empresa
document.getElementById('crearEmpresa')?.addEventListener('click', () => {
    window.location.href = `empresa_crear.php`; // Redirige directamente a la creación
});

// Acción para Editar Empresa
document.getElementById('editarEmpresa')?.addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `empresa_editar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para editar.');
    }
});

// Acción para Eliminar Empresa
document.getElementById('eliminarEmpresa')?.addEventListener('click', () => {
    if (selectedId) {
        if (confirm('¿Está seguro de eliminar este registro?')) {
            window.location.href = `empresa_eliminar.php?id=${selectedId}`;
        }
    } else {
        alert('Seleccione un registro para eliminar.');
    }
});

// Acción para Consultar Empresa
document.getElementById('consultarEmpresa')?.addEventListener('click', () => {
    if (selectedId) {
        window.location.href = `empresa_consultar.php?id=${selectedId}`;
    } else {
        alert('Seleccione un registro para consultar.');
    }
});

// Acción para Añadir Contacto
document.getElementById('añadirContacto')?.addEventListener('click', () => {
    window.location.href = `contacto_usuarios.php`;
});