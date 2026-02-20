<?php
// htdocs_public/sitios.php
session_start();
require_once __DIR__ . '/../app/Models/Lugar.php';

$lugarModel = new Lugar();
// Traemos TODOS los lugares
$todosLosLugares = $lugarModel->obtenerLugares();

// Filtramos en PHP para quitar los "Hoteles" (porque esos tienen su propia página)
// Aquí solo dejamos: Restaurantes, Rutas, Sitios Generales, etc.
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
    <title>Sitios Turísticos - PB MAPS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .sitios-header {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Puerto_Boyac%C3%A1_-_Boyac%C3%A1_-_Colombia.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        .filter-btn {
            border-radius: 20px;
            padding: 8px 20px;
            margin: 5px;
            font-weight: 500;
        }
        .filter-btn.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.3);
        }
        .card-img-top {
            height: 200px;
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
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-geo-alt-fill text-warning"></i> PB-MAPS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="hoteles.php">Hoteles</a></li>
                    <li class="nav-item"><a class="nav-link active" href="sitios.php">Sitios Turísticos</a></li>
                    <li class="nav-item"><a class="nav-link" href="operadores.php">Operadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="contactanos.php">Contacto</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                         <li class="nav-item"><a class="btn btn-outline-light ms-3 btn-sm" href="../app/Controllers/LogoutController.php">Salir</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="btn btn-primary ms-3 btn-sm" href="login.php">Ingresar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="sitios-header">
        <div class="container">
            <h1 class="display-4 fw-bold">Explora Puerto Boyacá</h1>
            <p class="lead">Filtra por categoría y encuentra tu próximo destino.</p>
        </div>
    </header>

    <div class="container my-5">
        
        <!-- --- AQUÍ ESTÁN LOS BOTONES DE FILTRO --- -->
        <div class="d-flex justify-content-center flex-wrap gap-2 mb-5">
            <button class="btn btn-outline-primary filter-btn active" onclick="filtrar('todos', this)">Todos</button>
            <button class="btn btn-outline-primary filter-btn" onclick="filtrar('Restaurante', this)">🍴 Restaurantes</button>
            <button class="btn btn-outline-primary filter-btn" onclick="filtrar('Ruta Ecológica', this)">🌿 Rutas Ecológicas</button>
            <button class="btn btn-outline-primary filter-btn" onclick="filtrar('Sitio General', this)">🏛️ Sitios Generales</button>
        </div>

        <?php if (empty($sitios)): ?>
            <div class="text-center py-5">
                <h3 class="text-secondary">No hay sitios registrados aún.</h3>
                <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador'): ?>
                    <a href="admin/dashboard.php" class="btn btn-primary mt-3">Ir al Dashboard para crear uno</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            
            <!-- Contenedor de Tarjetas -->
            <div class="row g-4" id="contenedorSitios">
                <?php foreach ($sitios as $sitio): ?>
                    
                    <!-- IMPORTANTE: data-categoria es lo que lee el JavaScript para filtrar -->
                    <div class="col-md-4 item-sitio" data-categoria="<?= htmlspecialchars($sitio['categoria']) ?>">
                        
                        <div class="card h-100 shadow-sm border-0 hover-effect">
                            <?php $img = !empty($sitio['url_imagen']) ? htmlspecialchars($sitio['url_imagen']) : 'https://via.placeholder.com/600x400?text=Sitio+Turistico'; ?>
                            
                            <img src="<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($sitio['nombre']) ?>" 
                                 onerror="this.src='https://via.placeholder.com/600x400?text=Sin+Imagen'">
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="card-title fw-bold mb-0 text-dark"><?= htmlspecialchars($sitio['nombre']) ?></h5>
                                    
                                    <!-- Badge de color según categoría -->
                                    <?php 
                                        $badge = 'bg-secondary';
                                        if($sitio['categoria'] == 'Restaurante') $badge = 'bg-danger';
                                        if($sitio['categoria'] == 'Ruta Ecológica') $badge = 'bg-success';
                                        if($sitio['categoria'] == 'Sitio General') $badge = 'bg-info text-dark';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= htmlspecialchars($sitio['categoria']) ?></span>
                                </div>
                                <p class="card-text text-muted small"><?= htmlspecialchars($sitio['descripcion']) ?></p>
                            </div>
                            
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="operadores.php" class="btn btn-outline-success w-100">
                                    <i class="bi bi-person-walking"></i> Buscar Guía
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php endif; ?>
    </div>

    <!-- Script de Filtrado (JavaScript) -->
    <script>
        function filtrar(categoria, boton) {
            // 1. Quitar clase 'active' a todos los botones
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            // 2. Activar el botón presionado
            boton.classList.remove('btn-outline-primary');
            boton.classList.add('active', 'btn-primary');

            // 3. Mostrar u ocultar tarjetas
            const items = document.querySelectorAll('.item-sitio');
            
            items.forEach(item => {
                const catItem = item.getAttribute('data-categoria');
                
                // Si es 'todos' O si la categoría coincide, mostrar. Si no, ocultar.
                if (categoria === 'todos' || catItem === categoria) {
                    item.style.display = 'block'; 
                    // Pequeña animación de entrada
                    item.style.opacity = '0';
                    setTimeout(() => item.style.opacity = '1', 100);
                } else {
                    item.style.display = 'none'; 
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>