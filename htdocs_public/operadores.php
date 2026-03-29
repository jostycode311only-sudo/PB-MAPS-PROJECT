<?php
// htdocs_public/operadores.php
session_start();
require_once __DIR__ . '/../app/Models/Usuario.php';

// Instanciar modelo y traer solo los operadores
$userModel = new Usuario();
$operadores = $userModel->obtenerOperadoresPublicos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operadores Turísticos - PB MAPS</title>
    <!-- Bootstrap 5 y Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Puerto_Boyac%C3%A1_-_Boyac%C3%A1_-_Colombia.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .card-img-top {
            height: 200px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .avatar-placeholder {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            color: #adb5bd;
            font-size: 3rem;
        }
    </style>
</head>
<body>

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">
                <i class="bi bi-geo-alt-fill"></i> PB-MAPS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="hoteles.php">Hoteles</a></li>
                    <li class="nav-item"><a class="nav-link" href="sitios.php">Sitios Turísticos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="operadores.php">Operadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="contactanos.php">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Ingresar / Soy Agencia</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Encabezado Hero -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Agencias y Guías Turísticos</h1>
            <p class="lead">Conecta con los expertos locales y vive la mejor experiencia en Puerto Boyacá.</p>
        </div>
    </section>

    <!-- Lista de Operadores -->
    <div class="container my-5">
        
        <?php if (empty($operadores)): ?>
            <div class="text-center py-5">
                <div class="display-1 text-muted"><i class="bi bi-shop"></i></div>
                <h3 class="mt-3 text-muted">Aún no hay operadores registrados.</h3>
                <p>Si eres una agencia local, contáctanos para aparecer aquí.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($operadores as $op): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect">
                            <!-- Logo o Imagen por defecto -->
                            <?php if (!empty($op['logo_url'])): ?>
                                <img src="<?= htmlspecialchars($op['logo_url']) ?>" class="card-img-top" alt="Logo Agencia" onerror="this.src='https://via.placeholder.com/300x200?text=Sin+Logo'">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <i class="bi bi-briefcase"></i>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($op['nombre_agencia']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars($op['descripcion_agencia']) ?>
                                </p>
                                <hr>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-telephone-fill text-success me-2"></i>
                                    <span><?= htmlspecialchars($op['telefono_contacto']) ?></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope-fill text-secondary me-2"></i>
                                    <small><?= htmlspecialchars($op['email']) ?></small>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-white border-0 pb-3">
                                <div class="d-grid gap-2">
                                    <!-- Botón Chat Interno (Lo activaremos mañana) -->
                                    <a href="chat_turista.php?id=<?= $op['usuario_id'] ?>" class="btn btn-primary">
                                        <i class="bi bi-chat-dots-fill"></i> Chatear en la Web
                                    </a>
                                    
                                    <!-- Botón WhatsApp (Extra útil) -->
                                    <a href="https://wa.me/57<?= preg_replace('/[^0-9]/', '', $op['telefono_contacto']) ?>" target="_blank" class="btn btn-outline-success">
                                        <i class="bi bi-whatsapp"></i> Contactar por WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2026 PB-MAPS. Todos los derechos reservados.</p>
            <small class="text-muted">Promoviendo el turismo en Puerto Boyacá</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>