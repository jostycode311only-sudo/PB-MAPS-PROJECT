<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PB-MAPS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* CSS Personalizado para la magia visual */
        body {
            /* Imagen de fondo de turismo (puedes cambiar la URL por una de Puerto Boyacá) */
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Puerto_Boyac%C3%A1_-_Boyac%C3%A1_-_Colombia.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            /* Efecto Cristal (Glassmorphism) */
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 2rem;
            width: 100%;
            max-width: 450px;
            animation: fadeIn 0.8s ease-out;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo-container img {
            max-width: 120px; /* Tamaño para tu futuro logo */
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: #0d6efd;
        }

        .btn-ingresar {
            background-color: #0d6efd;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-ingresar:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }

        .back-link {
            text-decoration: none;
            color: white;
            position: absolute;
            top: 20px;
            left: 20px;
            font-weight: 500;
        }

        .back-link:hover {
            color: #f8f9fa;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Botón para volver al inicio -->
    <a href="index.php" class="back-link"><i class="bi bi-arrow-left"></i> Volver al Inicio</a>

    <div class="login-card">
        <div class="logo-container">
            <h2 class="fw-bold text-primary mb-0"><i class="bi bi-geo-alt-fill text-warning"></i> PB-MAPS</h2>
            <p class="text-muted small">Descubre Puerto Boyacá</p>
        </div>

        <h4 class="text-center mb-4 fw-bold text-dark">Iniciar Sesión</h4>

        <!-- Manejo de Errores PHP (Tu lógica original) -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php 
                    switch ($_GET['error']) {
                        case 'campos_vacios': echo "Debe ingresar email y contraseña."; break;
                        case 'credenciales_invalidas': echo "Email o contraseña incorrectos."; break;
                        case 'no_autenticado': echo "Debe iniciar sesión para acceder."; break;
                        case 'acceso_denegado': echo "Su rol no tiene permisos."; break;
                        default: echo "Ocurrió un error inesperado.";
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Sesión cerrada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="../app/Controllers/AuthController.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label text-secondary fw-semibold">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" placeholder="usuario@correo.com" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label text-secondary fw-semibold">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="********" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-ingresar w-100">INGRESAR</button>
        </form>

        <div class="text-center mt-4">
            <span class="text-muted">¿No tienes una cuenta?</span> 
            <a href="registro.html" class="text-primary fw-bold text-decoration-none hover-underline">Regístrate aquí</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>