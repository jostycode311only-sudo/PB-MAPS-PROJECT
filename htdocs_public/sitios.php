<?php
// htdocs_public/sitios.php
session_start();

// 1. Cargar el Modelo
require_once __DIR__ . '/../app/Models/Lugar.php';

// 2. Instanciar y obtener datos
$lugarModel = new Lugar();

// Traemos TODOS los lugares
$todosLosLugares = $lugarModel->obtenerLugares();

// 3. Filtrar: Queremos todo lo que NO sea "Hotel"
// (Parques, Restaurantes, Rutas, etc.)
$sitios = [];
if ($todosLosLugares) {
    foreach ($todosLosLugares as $lugar) {
        if ($lugar['categoria'] !== 'Hotel') {
            $sitios[] = $lugar;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitios Turísticos - PB MAPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .sitios-header {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Puerto_Boyac%C3%A1_-_Boyac%C3%A1_-_Colombia.jpg');
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

    <!-- Navegación -->
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
                    <li class="nav-item"><a class="nav-link" href="hoteles.php">Hoteles</a></li>
                    <li class="nav-item"><a class="nav-link active" href="sitios.php">Sitios Turísticos</a></li>
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

    <!-- Header Específico -->
    <header class="sitios-header">
        <div class="container">
            <h1 class="display-4 fw-bold">Descubre Puerto Boyacá</h1>
            <p class="lead">Naturaleza, gastronomía y cultura te esperan.</p>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="container my-5">
        
        <?php if (empty($sitios)): ?>
            <div class="text-center py-5">
                <div class="display-1 text-muted mb-3"><i class="bi bi-tree"></i></div>
                <h3 class="text-secondary">No hay sitios registrados aún.</h3>
                <p class="text-muted">Pronto agregaremos parques y rutas ecológicas.</p>
                <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador'): ?>
                    <a href="admin/dashboard.php" class="btn btn-primary mt-3">Crear un Sitio Turístico</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            
            <div class="row g-4">
                <?php foreach ($sitios as $sitio): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect">
                            
                            <?php 
                                $img = !empty($sitio['url_imagen']) ? htmlspecialchars($sitio['url_imagen']) : 'https://via.placeholder.com/600x400?text=Sitio+Turistico';
                            ?>
                            <img src="<?= $img ?>" class="card-img-top card-img-custom" alt="<?= htmlspecialchars($sitio['nombre']) ?>" onerror="this.src='https://via.placeholder.com/600x400?text=Sin+Imagen'">
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold text-dark mb-0"><?= htmlspecialchars($sitio['nombre']) ?></h5>
                                    <!-- Badge dinámico según categoría -->
                                    <?php 
                                        $badgeClass = 'bg-secondary';
                                        if($sitio['categoria'] == 'Restaurante') $badgeClass = 'bg-danger';
                                        if($sitio['categoria'] == 'Ruta Ecológica') $badgeClass = 'bg-success';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($sitio['categoria']) ?></span>
                                </div>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars($sitio['descripcion']) ?>
                                </p>
                            </div>
                            
                            <div class="card-footer bg-white border-0 pb-3">
                                <!-- Botón para contactar un guía -->
                                <a href="operadores.php" class="btn btn-outline-success w-100">
                                    <i class="bi bi-person-walking"></i> Buscar Guía / Agencia
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