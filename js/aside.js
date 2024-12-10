// Control del aside para colapso y despliegue
document.getElementById('toggleSidebar').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
});