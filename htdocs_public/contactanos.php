<?php
// htdocs_public/contactanos.php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userRol = $_SESSION['user_rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - PB MAPS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .contact-hero {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 60px 0 100px 0;
            text-align: center;
        }
        .contact-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: -60px; /* Para que suba un poco sobre el fondo azul */
            position: relative;
            z-index: 10;
            text-align: center;
        }
        .profile-icon {
            font-size: 5rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
        .heart-icon {
            color: #dc3545;
            animation: heartbeat 1.5s infinite;
        }
        @keyframes heartbeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-geo-alt-fill text-warning"></i> PB-MAPS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="hoteles.php">Hoteles</a></li>
                    <li class="nav-item"><a class="nav-link" href="sitios.php">Sitios Turísticos</a></li>
                    <li class="nav-item"><a class="nav-link" href="operadores.php">Operadores</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="contactanos.php">Contacto</a></li>
                    
                    <?php if($isLoggedIn): ?>
                         <li class="nav-item"><a class="btn btn-outline-light ms-3 btn-sm" href="../app/Controllers/LogoutController.php">Salir</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="btn btn-primary ms-3 btn-sm" href="login.php">Ingresar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Fondo Azul Superior -->
    <div class="contact-hero">
        <div class="container">
            <h1 class="display-5 fw-bold"><i class="bi bi-envelope-paper"></i> Contáctanos</h1>
            <p class="lead">Estamos aquí para escucharte y mejorar juntos.</p>
        </div>
    </div>

    <!-- Tarjeta de Contacto / Acerca de mí -->
    <div class="container mb-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="contact-card">
                    
                    <div class="profile-icon">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    
                    <h2 class="fw-bold text-dark">Josstin Sammuel Gómez Bernal</h2>
                    <h5 class="text-primary mb-4">Aprendiz SENA - Análisis y Desarrollo de Software</h5>
                    
                    <p class="text-muted fs-5 mb-4 px-md-3">
                        ¿Quieres notificar algún fallo, bug o tienes una agencia de viajes y quieres ser operador en nuestra plataforma? 
                        <strong>¡No dudes en escribirme!</strong>
                    </p>

                    <a href="mailto:josstingomezbernal21@gmail.com" class="btn btn-primary btn-lg rounded-pill px-4 mb-4 shadow-sm">
                        <i class="bi bi-envelope-fill me-2"></i> josstingomezbernal21@gmail.com
                    </a>

                    <hr class="my-4 text-muted">

                    <p class="mb-0 fw-medium text-secondary">
                        Este proyecto fue hecho con mucho amor y entusiasmo. <i class="bi bi-heart-fill heart-icon"></i>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="bg-dark text-center text-white py-3 mt-auto">
        <div class="container">
            <small>&copy; 2026 PB-MAPS. Todos los derechos reservados.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>