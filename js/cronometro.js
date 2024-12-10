document.addEventListener('DOMContentLoaded', function () {
    const timers = {};

    // Función para inicializar cronómetros automáticamente
    document.querySelectorAll('.start-timer').forEach(button => {
        const citaId = button.dataset.id;
        const timerElement = document.getElementById(`metrica-${citaId}`);
        const estatusElement = document.getElementById(`estatus-${citaId}`);

        if (estatusElement) {
            console.log(`Cita ID: ${citaId} - Estatus Inicial: ${estatusElement.textContent.trim()}`);

            // Verifica si el estatus es "En espera" y activa el cronómetro
            if (estatusElement.textContent.trim() === 'En espera') {
                console.log(`Iniciando cronómetro automáticamente para la cita ID: ${citaId}`);
                startTimer(citaId, timerElement);
            }

            // Observa cambios en el estatus
            const observer = new MutationObserver(() => {
                const updatedStatus = estatusElement.textContent.trim();
                console.log(`Cita ID: ${citaId} - Estatus Actualizado: ${updatedStatus}`);

                if (updatedStatus === 'En espera') {
                    console.log(`Reiniciando cronómetro para la cita ID: ${citaId}`);
                    startTimer(citaId, timerElement);
                } else if (updatedStatus === 'Concluida') {
                    console.log(`Deteniendo cronómetro para la cita ID: ${citaId}`);
                    stopTimer(citaId);
                }
            });

            observer.observe(estatusElement, { childList: true, subtree: true });
        } else {
            console.error(`No se encontró el elemento de estatus para la cita ID: ${citaId}`);
        }
    });

    /**
     * Función para iniciar el cronómetro.
     * @param {string} citaId - ID único de la cita.
     * @param {HTMLElement} timerElement - Elemento HTML donde se muestra la métrica.
     */
    function startTimer(citaId, timerElement) {
        if (!timers[citaId]) {
            timers[citaId] = { hours: 0, minutes: 0, seconds: 0, interval: null };
        }

        // Verifica si ya está corriendo
        if (timers[citaId].interval) return;

        timers[citaId].interval = setInterval(() => {
            timers[citaId].seconds++;
            if (timers[citaId].seconds === 60) {
                timers[citaId].seconds = 0;
                timers[citaId].minutes++;
            }
            if (timers[citaId].minutes === 60) {
                timers[citaId].minutes = 0;
                timers[citaId].hours++;
            }

            // Actualiza el texto en el elemento correspondiente
            timerElement.textContent = `${timers[citaId].hours.toString().padStart(2, '0')}:${timers[citaId].minutes.toString().padStart(2, '0')}:${timers[citaId].seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    /**
     * Función para detener el cronómetro.
     * @param {string} citaId - ID único de la cita.
     */
    function stopTimer(citaId) {
        if (timers[citaId] && timers[citaId].interval) {
            clearInterval(timers[citaId].interval);
            timers[citaId].interval = null;
        }
    }
});
