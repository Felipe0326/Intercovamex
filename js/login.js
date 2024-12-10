document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const loginButton = document.querySelector(".btn-login");
    const spinner = document.querySelector(".spinner");

    loginForm.addEventListener("submit", (event) => {
        event.preventDefault(); // Evitar envío inicial
        loginButton.classList.add("loading");
        loginButton.disabled = true;

        // Simular un proceso de carga
        setTimeout(() => {
            loginForm.submit(); // Enviar el formulario después del "loading"
        }, 2000); // 2 segundos de espera para simular el proceso
    });

    // Animación para los iconos sociales
    const socialIcons = document.querySelectorAll(".social-login a");
    socialIcons.forEach((icon) => {
        icon.addEventListener("mouseenter", () => {
            icon.style.transform = "scale(1.2)";
        });
        icon.addEventListener("mouseleave", () => {
            icon.style.transform = "scale(1)";
        });
    });
});
