document.addEventListener('DOMContentLoaded', function () {
    const timers = {};
    
    // Función para realizar polling de los estados
    function checkStatusAndUpdate() {
        document.querySelectorAll('[id^="estatus-"]').forEach(estatusElement => {
            const citaId = estatusElement.id.split('-')[1];
            const timerElement = document.getElementById(`metrica-${citaId}`);
            const startTimestamp = localStorage.getItem(`startTimestamp-${citaId}`);

            // Realizar consulta AJAX para obtener el estado actualizado de la cita
            fetch(`../../citas/backend/get_estatus.php?citaId=${citaId}`)
                .then(response => response.json())
                .then(data => {
                    const estatusActual = data.estatus.trim();
                    
                    if (estatusActual === 'En espera') {
                        if (!startTimestamp) {
                            const now = new Date().getTime();
                            localStorage.setItem(`startTimestamp-${citaId}`, now);
                            guardarHoraInicio(citaId, now);
                            startTimer(citaId, timerElement, now);
                        } else {
                            startTimer(citaId, timerElement, parseInt(startTimestamp, 10));
                        }
                    } else if (estatusActual === 'Concluida') {
                        stopTimer(citaId);
                    }
                })
                .catch(error => console.error('Error al obtener el estado:', error));
        });
    }

    // Llamamos a la función de polling cada 5 segundos
    setInterval(checkStatusAndUpdate, 5000);

    // Función para iniciar el temporizador
    function startTimer(citaId, timerElement, startTimestamp) {
        if (timers[citaId]) return; // Ya está corriendo

        timers[citaId] = setInterval(() => {
            const now = new Date().getTime();
            const elapsed = Math.floor((now - startTimestamp) / 1000); // Tiempo en segundos
            const hours = Math.floor(elapsed / 3600);
            const minutes = Math.floor((elapsed % 3600) / 60);
            const seconds = elapsed % 60;

            timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    // Función para detener el temporizador
    function stopTimer(citaId) {
        if (timers[citaId]) {
            clearInterval(timers[citaId]);
            timers[citaId] = null;
        }
        localStorage.removeItem(`startTimestamp-${citaId}`); // Eliminar el tiempo de inicio para la cita
    }

    // Función para guardar la hora de inicio
    function guardarHoraInicio(citaId, timestamp) {
        const horaInicio = new Date(timestamp).toISOString();

        fetch('../../citas/backend/guardar_horainicio.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                citaId: citaId,
                horaInicio: horaInicio
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error al guardar la HoraInicio:', data.error);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud AJAX:', error);
        });
    }
});
