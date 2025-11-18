<?php
// [AQUÍ SE INCLUIRÁ LA GUARDIA DE SEGURIDAD...]

// CAMBIAR POR ESTO:
require_once __DIR__ . '/../../app/Controllers/CheckAuth.php';
?> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PB-MAPS | Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css"> 
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 text-danger">PB-MAPS ADMIN</span>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#gestionLugares">Lugares Turísticos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gestionHoteles">Hoteles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#gestionAgencias">Agencias Operadoras</a> 
                    </li>
                </ul>

                <a href="../../app/Controllers/LogoutController.php" class="btn btn-outline-secondary btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>¡Éxito!</strong> 
                <?php 
                    if ($_GET['success'] == 'lugar_creado') echo "El lugar turístico se ha creado correctamente.";
                    // Aquí podrás agregar más mensajes en el futuro (ej. 'lugar_actualizado')
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong>
                <?php 
                    switch ($_GET['error']) {
                        case 'acceso_invalido': echo "Acceso no autorizado."; break;
                        case 'campos_incompletos': echo "Por favor, complete todos los campos obligatorios."; break;
                        case 'db_fallo_insert': echo "Error al guardar en la base de datos. Intente nuevamente."; break;
                        default: echo "Ocurrió un error desconocido.";
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h2 id="gestionLugares" class="mb-4">Gestión de Lugares Turísticos</h2>
    
        <h2 id="gestionLugares" class="mb-4">Gestión de Lugares Turísticos</h2>

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#lugarModal">
            Crear Nuevo Lugar
        </button>

        <div class="table-responsive">
            </div>

        <hr class="my-5">
        
        <h2 id="gestionHoteles" class="mb-4">Gestión de Hoteles</h2>

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#hotelModal">
            Crear Nuevo Hotel
        </button>

        <div class="table-responsive">
             </div>

        <hr class="my-5"> 

        <h2 id="gestionAgencias" class="mb-4">Gestión de Agencias Operadoras</h2>

        <button class="btn btn-info text-white mb-3" data-bs-toggle="modal" data-bs-target="#agenciaModal">
            Crear Nueva Agencia
        </button>

        <div class="table-responsive">
            </div>

    </div> 
    
    <div class="modal fade" id="lugarModal" tabindex="-1" aria-labelledby="lugarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lugarModalLabel">Gestión de Lugar Turístico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form action="../../app/Controllers/LugarController.php" method="POST" id="lugarForm">
                        <div class="mb-3">
                            <label for="nombreLugar" class="form-label">Nombre del Lugar</label>
                            <input type="text" class="form-control" id="nombreLugar" name="nombre_lugar" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcionLugar" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionLugar" name="descripcion" rows="3" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitud" class="form-label">Latitud (Decimal)</label>
                                <input type="text" class="form-control" id="latitud" name="latitud">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitud" class="form-label">Longitud (Decimal)</label>
                                <input type="text" class="form-control" id="longitud" name="longitud">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="urlImagen" class="form-label">URL de Imagen</label>
                            <input type="url" class="form-control" id="urlImagen" name="url_imagen">
                        </div>
                        
                        <input type="hidden" id="lugarId" name="lugar_id" value=""> 
                    </form> 
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success" form="lugarForm">Guardar Cambios</button> 
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="hotelModal" tabindex="-1" aria-labelledby="hotelModalLabel" aria-hidden="true">
        </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>