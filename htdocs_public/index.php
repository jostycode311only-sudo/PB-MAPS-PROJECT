<?php
// htdocs_public/index.php
session_start();

// Variables para controlar el menú
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$userRol = $_SESSION['user_rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PB-MAPS - Turismo Puerto Boyacá</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Puerto_Boyac%C3%A1_-_Boyac%C3%A1_-_Colombia.jpg');
            background-size: cover;
            background-position: center;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        .hover-effect:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body>

    <!-- NAVEGACIÓN PRINCIPAL -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-geo-alt-fill text-warning"></i> PB-MAPS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="hoteles.php">Hoteles</a></li>
                    <li class="nav-item"><a class="nav-link" href="sitios.php">Sitios Turísticos</a></li>
                    <li class="nav-item"><a class="nav-link" href="operadores.php">Operadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="contactanos.php">Contacto</a></li>

                    <!-- LÓGICA DE USUARIO LOGUEADO -->
                    <?php if ($isLoggedIn): ?>
                        
                        <!-- Si es ADMIN -> Botón Dashboard -->
                        <?php if ($userRol === 'administrador'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-bold" href="admin/dashboard.php">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Si es OPERADOR -> Botón Mis Mensajes -->
                        <?php if ($userRol === 'operador'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-info fw-bold" href="admin/mensajes.php">
                                    <i class="bi bi-chat-dots-fill"></i> Mis Chats
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Menú Desplegable de Usuario -->
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle btn btn-outline-light px-3" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($userName) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text text-muted small">Rol: <?= ucfirst(str_replace('_', ' ', $userRol)) ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="../app/Controllers/LogoutController.php"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>

                    <?php else: ?>
                        <!-- Si NO está logueado -->
                        <li class="nav-item ms-3">
                            <a class="btn btn-primary px-4 fw-bold" href="login.php">Ingresar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION (Portada) -->
    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3">Descubre Puerto Boyacá</h1>
            <p class="lead fs-4 mb-5">Naturaleza, cultura y la mejor hospitalidad del Magdalena Medio.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="hoteles.php" class="btn btn-warning btn-lg fw-bold px-4 shadow">
                    <i class="bi bi-building"></i> Buscar Hotel
                </a>
                <a href="sitios.php" class="btn btn-success btn-lg fw-bold px-4 shadow">
                    <i class="bi bi-tree"></i> Ver Sitios
                </a>
                <a href="operadores.php" class="btn btn-outline-light btn-lg fw-bold px-4 backdrop-blur">
                    <i class="bi bi-people"></i> Agencias
                </a>
            </div>
        </div>
    </header>

    <!-- SECCIÓN DE ACCESO RÁPIDO -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">¿Qué estás buscando?</h2>
                <p class="text-muted">Explora las opciones que tenemos para ti</p>
            </div>

            <div class="row g-4">
                <!-- Tarjeta Hoteles -->
                <div class="col-md-4">
                    <a href="hoteles.php" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm feature-card p-4 text-center hover-effect">
                            <div class="mb-3 text-primary display-4">
                                <i class="bi bi-houses-fill"></i>
                            </div>
                            <h3 class="text-dark">Hoteles y Alojamiento</h3>
                            <p class="text-secondary">Encuentra el lugar perfecto para descansar, desde hoteles lujosos hasta hostales acogedores.</p>
                        </div>
                    </a>
                </div>

                <!-- Tarjeta Sitios Turísticos -->
                <div class="col-md-4">
                    <a href="sitios.php" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm feature-card p-4 text-center hover-effect">
                            <div class="mb-3 text-success display-4">
                                <i class="bi bi-tree-fill"></i>
                            </div>
                            <h3 class="text-dark">Sitios Turísticos</h3>
                            <p class="text-secondary">Visita la Ciénaga de Palagua, parques naturales y rutas ecológicas inolvidables.</p>
                        </div>
                    </a>
                </div>

                <!-- Tarjeta Operadores -->
                <div class="col-md-4">
                    <a href="operadores.php" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm feature-card p-4 text-center hover-effect">
                            <div class="mb-3 text-info display-4">
                                <i class="bi bi-chat-quote-fill"></i>
                            </div>
                            <h3 class="text-dark">Habla con Agencias</h3>
                            <p class="text-secondary">Contacta directamente a los guías y operadores locales. ¡Chatea con ellos en vivo!</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0 fw-bold">PB-MAPS &copy; 2026</p>
            <small class="text-white-50">Promoviendo el turismo en Puerto Boyacá</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>