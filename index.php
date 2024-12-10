<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Técnico - Intercovamex</title>
    <!-- CSS Personalizado -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
   <!-- Header -->
   <header class="header">
    <div class="logo">
        <img src="uploads/logolargo.png" alt="Logo Intercovamex">
    </div>
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="nav-item"><a href="index.php">Inicio</a></li>
            <li class="nav-item"><a href="citas_clientes.php">Agendar Citas</a></li>
            <li class="nav-item"><a href="#">Mis equipos</a></li>
            <li class="nav-item"><a href="contacto_cliente.php">Contacto</a></li>
        </ul>
    </nav>
    <!-- Botones de sesión -->
    <div>
        <?php if (!isset($_SESSION['usuarioId'])): ?>
            <!-- Mostrar solo si no hay usuario logueado -->
            <a href="login.html" class="btn login-btn">Iniciar Sesión</a>
        <?php else: ?>
            <!-- Mostrar solo si hay usuario logueado -->
            <a href="logout.php" class="btn logout-btn">Cerrar Sesión</a>
        <?php endif; ?>
    </div>
</header>

    <!-- Hero Section -->
<section class="hero">
    <h1>Servicio Técnico - Intercovamex</h1>
    <p>Confía en Intercovamex para obtener el mejor servicio técnico. ¡Nos adaptamos a tus necesidades!</p>
</section>


    <!-- Hero Section con Carrusel -->
    <section class="carousel-section">
        <div class="carousel-container">
            <div class="carousel">
                <div class="carousel-item">
                    <img src="servicio1.jpeg" alt="Imagen 1">
                    <p>Confía en expertos con más de 30 años brindando soluciones técnicas de calidad.</p>
                </div>
                <div class="carousel-item">
                    <img src="servicio2.jpeg" alt="Imagen 2">
                    <p>Mantenimiento preventivo y correctivo que prolonga la vida útil de tus equipos.</p>
                </div>
                <div class="carousel-item">
                    <img src="servicio3.jpeg" alt="Imagen 3">
                    <p>Soporte técnico especializado en equipos científicos e industriales.</p>
                </div>
                <div class="carousel-item">
                    <img src="servicio4.jpeg" alt="Imagen 4">
                    <p>Atención rápida y personalizada en Cuernavaca, Querétaro y Monterrey.</p>
                </div>
                <div class="carousel-item">
                    <img src="servicio5.jpeg" alt="Imagen 5">
                    <p>Certificados en tecnologías avanzadas para garantizar resultados precisos.</p>
                </div>
                <div class="carousel-item">
                    <img src="servicio6.jpeg" alt="Imagen 6">
                    <p>Optimiza tus operaciones con servicios diseñados para tus necesidades.</p>
                </div>
            </div>
            <button class="carousel-prev">&lt;</button>
            <button class="carousel-next">&gt;</button>
        </div>
    </section>

    <!-- Separador -->


<!-- Servicios Destacados -->
<section class="services-section">
      <!-- Separador -->
    <div class="section-separator-blue"></div>
    <h2>Nuestros Servicios Destacados</h2>
    <div class="services-container">
        <div class="service-item">
            <img src="icono-vacio.png" alt="Soluciones de Vacío">
            <h3>Soluciones de Vacío</h3>
            <p>Diseño, instalación y mantenimiento de sistemas de vacío industrial y científico.</p>
        </div>
        <div class="service-item">
            <img src="icono-fugas.png" alt="Detección de Fugas">
            <h3>Detección de Fugas</h3>
            <p>Inspecciones precisas para garantizar la seguridad y eficiencia de tus sistemas.</p>
        </div>
        <div class="service-item">
            <img src="icono-calidad.png" alt="Control de Calidad">
            <h3>Control de Calidad</h3>
            <p>Instrumentación avanzada para asegurar la calidad de tus procesos y productos.</p>
        </div>
        <div class="service-item">
            <img src="icono-servicio.png" alt="Servicio Técnico">
            <h3>Servicio Técnico</h3>
            <p>Reparación y mantenimiento preventivo de equipos científicos y tecnológicos.</p>
        </div>
        <div class="service-item">
            <img src="icono-ingenieria.png" alt="Proyectos de Ingeniería">
            <h3>Proyectos de Ingeniería</h3>
            <p>Desarrollo de soluciones personalizadas para optimizar tus operaciones.</p>
        </div>
        <div class="service-item">
            <img src="icono-equipos.png" alt="Equipos Usados y Promociones">
            <h3>Equipos Usados y Promociones</h3>
            <p>Adquiere equipos reacondicionados de calidad a precios competitivos.</p>
        </div>
    </div>
</section>

<!-- Llamado a la Acción -->
<section class="cta-section">
    <h2>¿Listo para optimizar tus operaciones?</h2>
    <p>Agenda un servicio técnico hoy mismo y experimenta la calidad y profesionalismo de Intercovamex.</p>
    <a href="#agendar-servicio" class="cta-button">Agendar Servicio</a>
</section>
       <!-- Empleados Destacados - Estilo Horizontal -->
<section class="team-section">
    <div class="section-separator-blue"></div>
    <h2>Conoce a Nuestros Especialistas</h2>
    <p>Un equipo de expertos listos para brindarte el mejor servicio técnico.</p>
    <div class="team-container">
        <!-- Empleado 1 -->
        <div class="team-card">
            <img src="empleado1.jpg" alt="Juan Pérez">
            <div class="team-info">
                <h3>Juan Pérez</h3>
                <p class="role">Técnico en Sistemas de Vacío</p>
                <p>Especialista en mantenimiento preventivo con más de 10 años de experiencia en equipos industriales.</p>
            </div>
        </div>
        <!-- Empleado 2 -->
        <div class="team-card">
            <img src="empleado2.jpg" alt="Ana Martínez">
            <div class="team-info">
                <h3>Ana Martínez</h3>
                <p class="role">Especialista en Control de Calidad</p>
                <p>Certificada en procesos ISO y control de calidad para industrias farmacéuticas.</p>
            </div>
        </div>
        <!-- Empleado 3 -->
        <div class="team-card">
            <img src="empleado3.jpg" alt="Carlos López">
            <div class="team-info">
                <h3>Carlos López</h3>
                <p class="role">Ingeniero de Proyectos</p>
                <p>Desarrolla soluciones personalizadas para optimizar procesos industriales.</p>
            </div>
        </div>
        <!-- Empleado 4 -->
        <div class="team-card">
            <img src="empleado4.jpg" alt="María Fernández">
            <div class="team-info">
                <h3>María Fernández</h3>
                <p class="role">Especialista en Detección de Fugas</p>
                <p>Garantiza la seguridad y eficiencia de sistemas mediante inspecciones avanzadas.</p>
            </div>
        </div>
    </div>
    <a href="todos-empleados.html" class="team-more-button">Ver Más Especialistas</a>
<!-- Separador -->


<!-- Contáctanos - Diseño Asimétrico -->
<section class="contact-modern-section">
    <div class="section-separator-blue"></div>
    <div class="contact-modern-container">
        <!-- Texto de contacto -->
        <div class="contact-modern-text">
            <h2>Estamos aquí para ayudarte</h2>
            <p>
                ¿Tienes alguna pregunta o necesitas soporte? Nuestro equipo está listo para ofrecerte
                soluciones rápidas y efectivas. ¡Hablemos ahora!
            </p>
            <a href="pagina-contacto.html" class="contact-modern-button">Contáctanos</a>
        </div>
        <!-- Imagen representativa -->
        <div class="contact-modern-image">
            <img src="contacto-equipo.jpg" alt="Contáctanos en Intercovamex">
        </div>
    </div>
</section>
<div class="section-separator-blue"></div>
<!-- Garantía de Servicio -->
<section class="guarantee-modern-section">
    
    <div class="guarantee-modern-header">
        <div class="header-text">
            <h2>Nuestra Garantía</h2>
            <p>Brindamos servicios confiables y de alta calidad respaldados por nuestro compromiso profesional.</p>
        </div>
    </div>
    <div class="guarantee-modern-container">
        <!-- Tarjetas de Garantía -->
        <div class="guarantee-modern-item">
            <div class="guarantee-icon">
                <img src="icono-calidad.png" alt="Calidad Garantizada">
            </div>
            <h3>Calidad Garantizada</h3>
            <p>Utilizamos equipos y procesos certificados para ofrecerte los mejores resultados.</p>
        </div>
        <div class="guarantee-modern-item">
            <div class="guarantee-icon">
                <img src="icono-tiempo.png" alt="Soporte Técnico">
            </div>
            <h3>Soporte Técnico</h3>
            <p>Asistencia personalizada después de cada servicio para mantener tus equipos óptimos.</p>
        </div>
        <div class="guarantee-modern-item">
            <div class="guarantee-icon">
                <img src="icono-rapidez.png" alt="Entrega Rápida">
            </div>
            <h3>Entrega Rápida</h3>
            <p>Nos comprometemos a cumplir los plazos establecidos para tu tranquilidad.</p>
        </div>
    </div>
</section>


        
    <!-- Footer -->
    <footer>
        <!-- Logo centrado -->
        <div class="footer-logo">
            <img src="logolargo.png" alt="Logo Intercovamex">
        </div>

        <!-- Secciones del footer -->
        <div class="footer-container">
            <!-- Nosotros -->
            <div>
                <h3>Nosotros:</h3>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#productos">Ingenieros</a></li>
                    <li><a href="#nosotros">Agendar Servicios</a></li>
                    <li><a href="#contacto">Trabajos</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </div>
            <!-- Contacto -->
            <div>
                <h3>Contacto:</h3>
                <ul>
                    <li><strong>Oficina Cuernavaca:</strong> <br> +52 (777) 313 2260</li>
                    <li><strong>Oficina Querétaro:</strong> <br> +52 (442) 645 0474</li>
                    <li><strong>Oficina Monterrey:</strong> <br> +52 (818) 345 1166</li>
                    <li style="margin-top: 10px;"><strong>Correo Electrónico:</strong> <br> <a href="mailto:contacto@intercovamex.com">contacto@intercovamex.com</a></li>
                </ul>
            </div>
            <!-- Productos y Servicios -->
            <div>
                <h3>Productos y Servicios:</h3>
                <ul>
                    <li>Soluciones de Vacío</li>
                    <li>Detección de Fugas</li>
                    <li>Control de Calidad e Instrumentación Científica</li>
                    <li>Sistemas de Depósito y Tratamientos Térmicos</li>
                    <li>Servicio Técnico</li>
                    <li>Proyectos de Ingeniería</li>
                    <li>Equipos Usados y Promociones</li>
                </ul>
            </div>
        </div>

        <!-- Enlace a página oficial -->
        <div class="footer-official-link">
            <p style="margin-top: 10px;"><a href="https://www.intercovamex.com" class="intercovamex-link" target="_blank">Visita nuestra página web oficial</a></p>
        </div>
    </footer>

    <!-- Sección de Copyright -->
    <section class="copyright-section">
        <p>Copyright © 2024 INTERCOVAMEX S.A. DE C.V. ~ Powered by INTERCOVAMEX.</p>
    </section>

    <!-- JavaScript Personalizado -->
    <script src="carrucel.js"></script>
</body>
</html>