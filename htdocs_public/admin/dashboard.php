<?php
// htdocs_public/admin/dashboard.php

// 1. Incluir la guardia de seguridad
require_once __DIR__ . '/../../app/Controllers/CheckAuth.php'; 

// 2. Cargar el Modelo para listar los lugares
require_once __DIR__ . '/../../app/Models/Lugar.php'; 
$lugarModel = new Lugar();
$lugares = $lugarModel->obtenerLugares();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">PB-MAPS Admin</a>
        <div class="d-flex">
            <span class="navbar-text me-3">
                Bienvenido, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></strong>
            </span>
            <a href="../../app/Controllers/LogoutController.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> 
            <?php 
                if ($_GET['success'] == 'lugar_creado') echo "El lugar turístico se ha creado correctamente.";
                elseif ($_GET['success'] == 'lugar_actualizado') echo "El lugar turístico se ha actualizado correctamente.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong>
            <?php 
                switch ($_GET['error']) {
                    case 'acceso_denegado_escritura': echo "Acceso denegado. Solo administradores pueden escribir."; break;
                    case 'campos_incompletos': echo "Por favor, complete todos los campos obligatorios."; break;
                    case 'db_fallo_insert': echo "Error al guardar en la base de datos. Intente nuevamente."; break;
                    case 'db_fallo_update': echo "Error al actualizar en la base de datos. Intente nuevamente."; break;
                    default: echo "Ocurrió un error desconocido.";
                }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <h2 id="gestionLugares" class="mb-4">Gestión de Lugares Turísticos</h2>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#lugarModal" id="btnCrearLugar">
        <i class="bi bi-plus-circle"></i> Crear Nuevo Lugar
    </button>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th> <!-- NUEVA COLUMNA -->
                    <th>Descripción</th>
                    <th>Imagen URL</th>
                    <th>Fecha Creación</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($lugares): ?>
                    <?php foreach ($lugares as $lugar): ?>
                        <tr>
                            <td><?= htmlspecialchars($lugar['id']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($lugar['nombre']) ?></td>
                            <!-- NUEVA COLUMNA DE DATOS -->
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= htmlspecialchars($lugar['categoria'] ?? 'Sitio General') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(substr($lugar['descripcion'], 0, 50)) . '...' ?></td>
                            <td><a href="<?= htmlspecialchars($lugar['url_imagen']) ?>" target="_blank">Ver</a></td>
                            <td><?= date('Y-m-d', strtotime($lugar['fecha_creacion'])) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning btn-edit-lugar" 
                                        data-id="<?= htmlspecialchars($lugar['id']) ?>" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#lugarModal"
                                        title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-lugar" 
                                        data-id="<?= htmlspecialchars($lugar['id']) ?>" 
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aún no hay lugares turísticos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div> 

<div class="modal fade" id="lugarModal" tabindex="-1" aria-labelledby="lugarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lugarModalLabel">Crear Nuevo Lugar Turístico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="lugarForm" method="POST" action="../../app/Controllers/LugarController.php">
                    
                    <input type="hidden" id="id_lugar" name="id_lugar">

                    <div class="mb-3">
                        <label for="nombre_lugar" class="form-label">Nombre del Lugar</label>
                        <input type="text" class="form-control" id="nombre_lugar" name="nombre_lugar" required>
                    </div>

                    <!-- NUEVO CAMPO SELECTOR DE CATEGORÍA -->
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="Sitio General">Sitio General</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Restaurante">Restaurante</option>
                            <option value="Ruta Ecológica">Ruta Ecológica</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="url_imagen" class="form-label">URL de la Imagen</label>
                        <input type="url" class="form-control" id="url_imagen" name="url_imagen">
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>