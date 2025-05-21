<!-- includes/sidebar.php -->
<aside class="sidebar p-3" id="sidebarMenu">
    <div class="d-flex justify-content-end d-lg-none mb-2">
        <button id="toggleSidebar" class="btn btn-outline-light btn-sm">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="logo text-center mb-3">
        <img src="/Portal_VFelipe/uploads/logocortoblanco.png" alt="Logo de Intercovamex">
    </div>
    <div class="user-profile text-center mb-4">
        <p class="welcome-text">Intercovamex</p>
        <h5 class="mb-0">
            <?php
            if (isset($user['Nombre']) && isset($user['Apellido'])) {
                echo htmlspecialchars($user['Nombre'] . ' ' . $user['Apellido']);
            }
            ?>
        </h5>
        <a href="/Portal_VFelipe/logout.php" class="btn logout-btn mt-2">Cerrar Sesión</a>
    </div>
    <nav>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="/Portal_VFelipe/dashboard/frontend/dashboard.php" class="nav-link active"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="/Portal_VFelipe/equipos/frontend/equipos_usuarios.php" class="nav-link"><i class="fas fa-cogs me-2"></i>Gestión de Equipos</a>
            </li>
            <li class="nav-item">
                <a href="/Portal_VFelipe/empresa/frontend/empresa_usuarios.php" class="nav-link"><i class="fas fa-building me-2"></i>Gestión de Empresas</a>
            </li>
            <li class="nav-item">
                <a href="/Portal_VFelipe/Empleados/frontend/empleados_usuarios.php" class="nav-link"><i class="fas fa-user-tie me-2"></i>Gestión de Empleados</a>
            </li>
            <li class="nav-item">
                <a href="/Portal_VFelipe/citas/frontend/citas_usuarios.php" class="nav-link"><i class="fas fa-calendar-alt me-2"></i>Gestión de Citas</a>
            </li>
            <li class="nav-item">
                <a href="/Portal_VFelipe/ontacto/frontend/contacto_usuarios.php" class="nav-link"><i class="fas fa-phone me-2"></i>Contacto</a>
            </li>
        </ul>
    </nav>
</aside>