<<?php
// htdocs_public/hoteles.php
session_start();

require_once __DIR__ . '/../app/Models/Lugar.php';
$lugarModel = new Lugar();
// Obtener solo los lugares que son de categoría 'Hotel'
$hoteles = $lugarModel->obtenerLugaresPorCategoria('Hotel');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteles en Puerto Boyacá - PB MAPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .hotel-header {
            /* Usando una imagen bonita de fondo para la cabecera */
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Puerto_Boyac%C3%A1_-_Boyac%C3%A1_-_Colombia.jpg');
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
            box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-geo-alt-fill text-warning"></i> PB-MAPS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="hoteles.php">Hoteles</a></li>
                    <li class="nav-item"><a class="nav-link" href="sitios.php">Sitios Turísticos</a></li>
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

    <!-- Cabecera -->
    <header class="hotel-header">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="bi bi-building"></i> Dónde Alojarse</h1>
            <p class="lead">Descubre la comodidad y hospitalidad de nuestra región.</p>
        </div>
    </header>

    <!-- Contenedor Principal -->
    <div class="container my-5">
        <?php if (empty($hoteles)): ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <h3 class="text-secondary mt-3">No hay hoteles registrados aún.</h3>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($hoteles as $hotel): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect">
                            
                            <!-- Imagen del Hotel -->
                            <?php $img = !empty($hotel['url_imagen']) ? htmlspecialchars($hotel['url_imagen']) : 'https://via.placeholder.com/600x400?text=Sin+Imagen'; ?>
                            <img src="<?= $img ?>" class="card-img-top card-img-custom" alt="<?= htmlspecialchars($hotel['nombre']) ?>" onerror="this.src='https://via.placeholder.com/600x400?text=Sin+Imagen'">
                            
                            <!-- Cuerpo de la Tarjeta -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold text-dark mb-0"><?= htmlspecialchars($hotel['nombre']) ?></h5>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Hotel</span>
                                </div>
                                <p class="card-text text-muted small"><?= htmlspecialchars($hotel['descripcion']) ?></p>
                            </div>
                            
                            <!-- Pie de la Tarjeta con el Botón de WhatsApp -->
                            <div class="card-footer bg-white border-0 pb-3 d-grid gap-2">
                                <?php 
                                    // 1. Verificamos si existe el campo teléfono y si no está vacío
                                    $telefonoOriginal = isset($hotel['telefono']) ? $hotel['telefono'] : '';
                                    
                                    // 2. Limpiamos el número dejando SOLO los dígitos (quita espacios, guiones, símbolos)
                                    $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefonoOriginal);
                                    
                                    if (!empty($telefonoLimpio)): 
                                        // 3. Preparamos el mensaje automático
                                        $mensajeWS = "Hola, estoy interesado en reservar en " . $hotel['nombre'] . ". ¿Tienen disponibilidad?";
                                        // 4. Armamos el enlace oficial de WhatsApp
                                        $linkWS = "https://wa.me/" . $telefonoLimpio . "?text=" . urlencode($mensajeWS);
                                ?>
                                    <!-- Botón Funcional de WhatsApp -->
                                    <a href="<?= $linkWS ?>" target="_blank" class="btn btn-success text-white fw-bold shadow-sm">
                                        <i class="bi bi-whatsapp"></i> Reservar por WhatsApp
                                    </a>
                                <?php else: ?>
                                    <!-- Si el admin NO le puso número de teléfono al crear el hotel -->
                                    <button class="btn btn-secondary disabled" title="Este hotel aún no tiene número registrado">
                                        <i class="bi bi-telephone-x"></i> Sin contacto registrado
                                    </button>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pie de página simple -->
    <footer class="bg-dark text-center text-white py-3 mt-5">
        <div class="container">
            <small>© 2026 PB-MAPS. Todos los derechos reservados.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>