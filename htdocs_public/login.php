<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PB-MAPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin-top: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 login-container">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4 class="mb-0">Inicio de Sesión</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php 
                            // Este script PHP DEBE estar para manejar los errores y mensajes
                            if (isset($_GET['error'])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php 
                                        switch ($_GET['error']) {
                                            case 'campos_vacios': echo "Debe ingresar email y contraseña."; break;
                                            case 'credenciales_invalidas': echo "Email o contraseña incorrectos."; break;
                                            case 'no_autenticado': echo "Debe iniciar sesión para acceder."; break;
                                            case 'acceso_denegado': echo "Su rol no tiene permisos."; break;
                                            default: echo "Ocurrió un error inesperado.";
                                        }
                                    ?>
                                </div>
                            <?php elseif (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
                                <div class="alert alert-success" role="alert">
                                    Sesión cerrada correctamente.
                                </div>
                            <?php endif; 
                        ?>
                        
                        <form action="../app/Controllers/AuthController.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>