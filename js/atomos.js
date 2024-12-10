document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("particleCanvas");
    const ctx = canvas.getContext("2d");

    // Ajustar el tamaño del canvas al contenedor
    function resizeCanvas() {
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
    }
    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);

    // Configuración de partículas
    const particles = [];
    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 4 + 1,
            dx: (Math.random() - 0.5) * 2,
            dy: (Math.random() - 0.5) * 2,
        });
    }

    // Dibujar partículas
    function drawParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        particles.forEach((particle) => {
            // Dibujar círculo
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
            ctx.fillStyle = "#000000"; // Color negro para las partículas
            ctx.fill();
            ctx.closePath();

            // Dibujar líneas entre partículas cercanas
            particles.forEach((otherParticle) => {
                const dist = Math.hypot(particle.x - otherParticle.x, particle.y - otherParticle.y);
                if (dist < 100) {
                    ctx.beginPath();
                    ctx.moveTo(particle.x, particle.y);
                    ctx.lineTo(otherParticle.x, otherParticle.y);
                    ctx.strokeStyle = `rgba(0, 0, 0, ${1 - dist / 100})`; // Color negro para las líneas
                    ctx.stroke();
                    ctx.closePath();
                }
            });

            // Actualizar posición
            particle.x += particle.dx;
            particle.y += particle.dy;

            // Rebotar en los bordes
            if (particle.x < 0 || particle.x > canvas.width) particle.dx *= -1;
            if (particle.y < 0 || particle.y > canvas.height) particle.dy *= -1;
        });

        requestAnimationFrame(drawParticles);
    }

    drawParticles();
});
