<?php
// htdocs_public/admin/dashboard.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../app/Controllers/CheckAuth.php'; 

// Seguridad: Solo Admin
if ($_SESSION['user_rol'] !== 'administrador') {
    // Si es operador, lo mandamos a sus mensajes
    if ($_SESSION['user_rol'] === 'operador') { header("Location: mensajes.php"); exit; }
    // Si es turista, al inicio
    header("Location: ../index.php"); exit;
}

require_once __DIR__ . '/../../app/Models/Lugar.php'; 
$lugarModel = new Lugar();
$lugares = $lugarModel->obtenerLugares();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .admin-nav .nav-link.active { background-color: #0d6efd; color: white !important; border-radius: 5px; }
        .admin-nav .nav-link { color: #ccc; }
        .admin-nav .nav-link:hover { color: white; }
    </style>
</head>
<body class="bg-light">

<!-- BARRA DE NAVEGACIÓN -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">PB-MAPS <span class="badge bg-danger">Admin</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 admin-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php"><i class="bi bi-geo-alt"></i> Lugares Turísticos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php"><i class="bi bi-people"></i> Usuarios y Operadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mensajes.php"><i class="bi bi-chat-dots"></i> Mensajería</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small d-none d-md-block">Hola, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                <a href="../../app/Controllers/LogoutController.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <!-- Alertas de Éxito o Error -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill"></i> Operación realizada correctamente. 
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-triangle-fill"></i> Ocurrió un error en la operación. Por favor revisa los datos.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Contenido</h2>
        <button class="btn btn-primary shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#lugarModal">
            <i class="bi bi-plus-lg"></i> Nuevo Lugar
        </button>
    </div>

    <!-- FILTROS DE TABLA -->
    <div class="btn-group mb-3 shadow-sm" role="group">
        <button type="button" class="btn btn-outline-primary active filter-btn" onclick="filtrarTabla('todos', this)">Todos</button>
        <button type="button" class="btn btn-outline-primary filter-btn" onclick="filtrarTabla('Hotel', this)">🏨 Hoteles</button>
        <button type="button" class="btn btn-outline-primary filter-btn" onclick="filtrarTabla('Restaurante', this)">🍴 Restaurantes</button>
        <button type="button" class="btn btn-outline-primary filter-btn" onclick="filtrarTabla('Ruta Ecológica', this)">🌿 Rutas</button>
        <button type="button" class="btn btn-outline-primary filter-btn" onclick="filtrarTabla('Sitio General', this)">🏛️ General</button>
    </div>

    <!-- Tabla de Lugares -->
    <div class="card shadow border-0 mb-5">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaLugares">
                        <?php if ($lugares): ?>
                            <?php foreach ($lugares as $lugar): ?>
                                <tr class="fila-lugar" data-categoria="<?= htmlspecialchars($lugar['categoria'], ENT_QUOTES) ?>">
                                    <td>
                                        <img src="<?= !empty($lugar['url_imagen']) ? htmlspecialchars($lugar['url_imagen'], ENT_QUOTES) : 'https://via.placeholder.com/50' ?>" 
                                             width="50" height="50" class="rounded object-fit-cover shadow-sm"
                                             onerror="this.src='https://via.placeholder.com/50?text=Error'">
                                    </td>
                                    <td class="fw-bold"><?= htmlspecialchars($lugar['nombre'], ENT_QUOTES) ?></td>
                                    <td>
                                        <?php 
                                            $badge = 'bg-secondary';
                                            if($lugar['categoria'] == 'Hotel') $badge = 'bg-warning text-dark';
                                            if($lugar['categoria'] == 'Restaurante') $badge = 'bg-danger';
                                            if($lugar['categoria'] == 'Ruta Ecológica') $badge = 'bg-success';
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= htmlspecialchars($lugar['categoria'], ENT_QUOTES) ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block text-truncate" style="max-width: 250px;">
                                            <?= htmlspecialchars($lugar['descripcion'], ENT_QUOTES) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <!-- BOTÓN EDITAR CON DATA-ATTRIBUTES PROTEGIDOS CONTRA COMILLAS -->
                                        <button class="btn btn-sm btn-outline-primary" title="Editar"
                                                data-id="<?= htmlspecialchars($lugar['id'] ?? $lugar['id_lugar'] ?? '', ENT_QUOTES) ?>"
                                                data-nombre="<?= htmlspecialchars($lugar['nombre'] ?? '', ENT_QUOTES) ?>"
                                                data-categoria="<?= htmlspecialchars($lugar['categoria'] ?? '', ENT_QUOTES) ?>"
                                                data-descripcion="<?= htmlspecialchars($lugar['descripcion'] ?? '', ENT_QUOTES) ?>"
                                                data-imagen="<?= htmlspecialchars($lugar['url_imagen'] ?? '', ENT_QUOTES) ?>"
                                                data-telefono="<?= htmlspecialchars($lugar['telefono'] ?? '', ENT_QUOTES) ?>"
                                                onclick="abrirModalEditar(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <!-- BOTÓN ELIMINAR -->
                                        <form action="../../app/Controllers/LugarController.php" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este lugar?');">
                                            <input type="hidden" name="action" value="eliminar">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($lugar['id'] ?? $lugar['id_lugar'] ?? '', ENT_QUOTES) ?>">
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center p-4">No hay lugares registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 

<!-- 1. MODAL: CREAR LUGAR -->
<div class="modal fade" id="lugarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Registrar Nuevo Lugar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../../app/Controllers/LugarController.php">
                    <input type="hidden" name="action" value="crear">
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre del Lugar / Hotel</label>
                        <input type="text" class="form-control" name="nombre" required placeholder="Ej: Hotel Campestre...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="categoria" required>
                            <option value="Sitio General">Sitio General</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Restaurante">Restaurante</option>
                            <option value="Ruta Ecológica">Ruta Ecológica</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción Detallada</label>
                        <textarea class="form-control" name="descripcion" rows="3" required placeholder="Describe los servicios..."></textarea>
                    </div>
                    <div class="mb-3">
                        <!-- CAMBIO: type="text" en lugar de type="url" para que acepte base64 u otros enlaces raros sin bloquear -->
                        <label class="form-label">URL de la Imagen</label>
                        <input type="text" class="form-control" name="url_imagen" placeholder="https://ejemplo.com/foto.jpg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono / WhatsApp (Opcional)</label>
                        <input type="text" class="form-control" name="telefono" placeholder="Ej: 573001234567">
                        <div class="form-text">Escribe el número con el código de país (Ej: 57) sin el signo +.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Guardar Lugar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 2. MODAL: EDITAR LUGAR -->
<div class="modal fade" id="modalEditarLugar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Editar Lugar Turístico</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../../app/Controllers/LugarController.php">
                    <!-- Acción para el Controlador -->
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre del Lugar / Hotel</label>
                        <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="categoria" id="edit_categoria" required>
                            <option value="Sitio General">Sitio General</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Restaurante">Restaurante</option>
                            <option value="Ruta Ecológica">Ruta Ecológica</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción Detallada</label>
                        <textarea class="form-control" name="descripcion" id="edit_descripcion" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <!-- CAMBIO: type="text" aquí también -->
                        <label class="form-label">URL de la Imagen</label>
                        <input type="text" class="form-control" name="url_imagen" id="edit_url_imagen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono / WhatsApp</label>
                        <input type="text" class="form-control" name="telefono" id="edit_telefono">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Actualizar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts Personalizados -->
<script>
    // 1. Script para Filtrar la Tabla
    function filtrarTabla(categoria, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('active', 'btn-primary');
            b.classList.add('btn-outline-primary');
        });
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('active', 'btn-primary');

        const filas = document.querySelectorAll('.fila-lugar');
        filas.forEach(fila => {
            const catFila = fila.getAttribute('data-categoria');
            if (categoria === 'todos' || catFila === categoria) {
                fila.style.display = ''; 
            } else {
                fila.style.display = 'none'; 
            }
        });
    }

    // 2. Script para abrir y llenar el Modal de Edición
    function abrirModalEditar(btn) {
        // Obtenemos los datos desde el botón que se hizo clic
        document.getElementById('edit_id').value = btn.getAttribute('data-id');
        document.getElementById('edit_nombre').value = btn.getAttribute('data-nombre');
        document.getElementById('edit_categoria').value = btn.getAttribute('data-categoria');
        document.getElementById('edit_descripcion').value = btn.getAttribute('data-descripcion');
        document.getElementById('edit_url_imagen').value = btn.getAttribute('data-imagen');
        document.getElementById('edit_telefono').value = btn.getAttribute('data-telefono');
        
        // Mostramos el modal usando Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('modalEditarLugar'));
        modal.show();
    }
</script>

</body>
</html>