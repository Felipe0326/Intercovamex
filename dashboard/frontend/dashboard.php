<?php
include('../../db.php');                  // Conexión a la base de datos
include('../../roles/auth.php');          // Autenticación de usuario
$user = getLoggedInUser($conn, $_SESSION['usuarioId']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Tu CSS global para la barra lateral -->
    <link rel="stylesheet" href="/Portal_VFelipe/css/global.css">
</head>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/Portal_VFelipe/includes/sidebar_adm.php'); ?>
    <!-- Topbar (opcional) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
        <div class="container-fluid position-relative">
            <button class="btn btn-outline-secondary d-lg-none" id="openSidebar" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <span class="navbar-brand fw-bold text-dark">Panel de Administración</span>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="content p-4">
        <div class="row">
            <!-- Tarjeta: Usuarios -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary p-3 rounded-circle text-white me-3">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Usuarios</h6>
                                <h3 class="mb-0">
                                    <?php
                                    $resUsuarios = mysqli_query($conn, "SELECT COUNT(*) AS total FROM usuarios");
                                    $numUsuarios = mysqli_fetch_assoc($resUsuarios)['total'];
                                    echo $numUsuarios;
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarjeta: Empresas -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success p-3 rounded-circle text-white me-3">
                                <i class="fas fa-building fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Empresas</h6>
                                <h3 class="mb-0">
                                    <?php
                                    $resEmpresas = mysqli_query($conn, "SELECT COUNT(*) AS total FROM empresas");
                                    $numEmpresas = mysqli_fetch_assoc($resEmpresas)['total'];
                                    echo $numEmpresas;
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        // Para mostrar la sidebar en móvil (opcional)
        document.getElementById('openSidebar')?.addEventListener('click', function() {
            document.getElementById('sidebarMenu').classList.add('show');
        });
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.getElementById('sidebarMenu').classList.remove('show');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>