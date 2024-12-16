// JavaScript para las partículas

document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("particleCanvas");
    const ctx = canvas.getContext("2d");

    // Ajustar el tamaño del canvas para que cubra toda la ventana
    function resizeCanvas() {
        canvas.width = window.innerWidth; // Ancho de la ventana
        canvas.height = window.innerHeight; // Alto de la ventana
    }

    resizeCanvas();
    window.addEventListener("resize", resizeCanvas); // Ajustar cuando se cambia el tamaño de la ventana

    // Configuración de partículas
    const particles = [];
    const particleCount = 100; // Aumentar la cantidad de partículas

    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * canvas.width, // Posición aleatoria
            y: Math.random() * canvas.height, // Posición aleatoria
            radius: Math.random() * 4 + 1, // Tamaño aleatorio de la partícula
            dx: (Math.random() - 0.5) * 1, // Velocidad aleatoria horizontal
            dy: (Math.random() - 0.5) * 1, // Velocidad aleatoria vertical
        });
    }

    // Dibujar partículas
    function drawParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);  // Limpiar el canvas

        particles.forEach((particle) => {
            // Dibujar círculo
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
            ctx.fillStyle = "#00BFFF"; // Color azul brillante para las partículas
            ctx.fill();
            ctx.closePath();

            // Dibujar líneas entre partículas cercanas
            particles.forEach((otherParticle) => {
                const dist = Math.hypot(particle.x - otherParticle.x, particle.y - otherParticle.y);
                if (dist < 100) {
                    ctx.beginPath();
                    ctx.moveTo(particle.x, particle.y);
                    ctx.lineTo(otherParticle.x, otherParticle.y);
                    ctx.strokeStyle = `rgba(0, 191, 255, ${1 - dist / 100})`; // Color azul brillante para las líneas
                    ctx.stroke();
                    ctx.closePath();
                }
            });

            // Actualizar posición de las partículas
            particle.x += particle.dx;
            particle.y += particle.dy;

            // Rebote en toda la pantalla (rebote horizontal y vertical)
            if (particle.x < 0 || particle.x > canvas.width) particle.dx *= -1;  // Rebote horizontal
            if (particle.y < 0 || particle.y > canvas.height) particle.dy *= -1; // Rebote vertical
        });

        requestAnimationFrame(drawParticles); // Llamar la función recursivamente
    }

    drawParticles(); // Iniciar el dibujo de las partículas
});