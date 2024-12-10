const canvas = document.getElementById('particleCanvas');
const ctx = canvas.getContext('2d');
const container = document.querySelector('.register-info');
let particles = [];
const colors = ['#ffffff', '#d1e7ff', '#90caf9'];

// Ajustar el tamaño del canvas al contenedor
function resizeCanvas() {
    canvas.width = container.offsetWidth;
    canvas.height = container.offsetHeight;
}

window.addEventListener('resize', resizeCanvas);
resizeCanvas();

// Crear partículas
function createParticles() {
    for (let i = 0; i < 50; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 3 + 1,
            color: colors[Math.floor(Math.random() * colors.length)],
            speedX: (Math.random() - 0.5) * 2,
            speedY: (Math.random() - 0.5) * 2,
        });
    }
}

// Dibujar partículas
function drawParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles.forEach((p) => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fillStyle = p.color;
        ctx.fill();
        p.x += p.speedX;
        p.y += p.speedY;

        // Rebote en los bordes
        if (p.x < 0 || p.x > canvas.width) p.speedX = -p.speedX;
        if (p.y < 0 || p.y > canvas.height) p.speedY = -p.speedY;
    });
    requestAnimationFrame(drawParticles);
}

createParticles();
drawParticles();
