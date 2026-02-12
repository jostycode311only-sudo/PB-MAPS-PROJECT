<?php
// htdocs_public/hoteles.php
session_start();

// 1. Cargar el Modelo Lugar
require_once __DIR__ . '/../app/Models/Lugar.php';

// 2. Instanciar y obtener datos
$lugarModel = new Lugar();

// Usamos el método de filtro por categoría.
// Asegúrate de que en la BD la categoría se llame 'Hotel' (La primera letra mayúscula)
$hoteles = $lugarModel->obtenerLugaresPorCategoria('Hotel');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteles en Puerto Boyacá - PB MAPS</title>
    <!-- Bootstrap y CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .hotel-header {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/1b/3a/0e/e6/exterior.jpg?w=1200&h=-1&s=1');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .card-img-custom {
            height: 250px;
            object-fit: cover;
        }
        .hover-effect:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body>

    <!-- Navegación (Consistente con index.php) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-geo-alt-fill text-warning"></i> PB-MAPS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active" href="hoteles.php">Hoteles</a></li>
                    <li class="nav-item"><a class="nav-link" href="sitios.php">Sitios Turísticos</a></li>
                    <li class="nav-item"><a class="nav-link" href="operadores.php">Operadores</a></li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                         <li class="nav-item"><a class="btn btn-outline-light ms-3 btn-sm" href="../app/Controllers/LogoutController.php">Salir</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="btn btn-primary ms-3 btn-sm" href="login.php">Ingresar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Específico de Hoteles -->
    <header class="hotel-header">
        <div class="container">
            <h1 class="display-4 fw-bold">Dónde Alojarse</h1>
            <p class="lead">Descubre la comodidad y hospitalidad de nuestra región.</p>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="container my-5">
        
        <?php if (empty($hoteles)): ?>
            <!-- Caso: No hay hoteles -->
            <div class="text-center py-5">
                <div class="display-1 text-muted mb-3"><i class="bi bi-suitcase-lg"></i></div>
                <h3 class="text-secondary">No hay hoteles registrados aún.</h3>
                <p class="text-muted">Estamos trabajando para agregar las mejores opciones para ti.</p>
            </div>
        <?php else: ?>
            
            <!-- Caso: Sí hay hoteles (Grid) -->
            <div class="row g-4">
                <?php foreach ($hoteles as $hotel): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect">
                            <!-- Imagen con protección si no existe -->
                            <?php 
                                $img = !empty($hotel['url_imagen']) ? htmlspecialchars($hotel['url_imagen']) : 'https://via.placeholder.com/600x400?text=Sin+Imagen';
                            ?>
                            <img src="<?= $img ?>" class="card-img-top card-img-custom" alt="<?= htmlspecialchars($hotel['nombre']) ?>" onerror="this.src='https://via.placeholder.com/600x400?text=Imagen+No+Disponible'">
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold text-dark mb-0"><?= htmlspecialchars($hotel['nombre']) ?></h5>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Hotel</span>
                                </div>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars($hotel['descripcion']) ?>
                                </p>
                            </div>
                            
                            <div class="card-footer bg-white border-0 pb-3">
                                <!-- Enlace a Operadores para preguntar -->
                                <a href="operadores.php" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-chat-text"></i> Preguntar disponibilidad
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center py-4 border-top">
        <small class="text-muted">&copy; 2026 PB-MAPS</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>