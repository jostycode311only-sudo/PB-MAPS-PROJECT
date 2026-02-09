<?php
// htdocs_public/admin/usuarios.php
session_start();
require_once __DIR__ . '/../../app/Controllers/CheckAuth.php'; // Tu archivo de seguridad
require_once __DIR__ . '/../../app/Models/Usuario.php';

// Verificar rol admin
if ($_SESSION['user_rol'] !== 'administrador') {
    header("Location: dashboard.php");
    exit;
}

$userModel = new Usuario();
$usuarios = $userModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - PB MAPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">⬅ Volver al Dashboard</a>
        <span class="navbar-text">Gestión de Roles y Operadores</span>
    </div>
</nav>

<div class="container">
    
    <!-- SECCIÓN DE ALERTAS MEJORADA -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                if ($_GET['msg'] == 'promovido') {
                    echo "<strong>¡Excelente!</strong> El turista ha sido promovido a <u>Operador Turístico</u> correctamente.";
                } elseif ($_GET['msg'] == 'revocado') {
                    echo "<strong>Rol actualizado:</strong> El usuario ahora es un turista regular.";
                } else {
                    echo "Operación realizada con éxito.";
                }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> No se pudo completar la operación. Verifica los datos e intenta nuevamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <!-- FIN ALERTAS -->

    <div class="card shadow">
        <div class="card-header bg-white">
            <h5 class="mb-0">Usuarios Registrados</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol Actual</th>
                        <th>Agencia (Si aplica)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['nombre_usuario']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <?php if($u['rol'] == 'administrador'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php elseif($u['rol'] == 'operador'): ?>
                                    <span class="badge bg-success">Operador</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Turista</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['nombre_agencia']): ?>
                                    <strong><?= htmlspecialchars($u['nombre_agencia']) ?></strong><br>
                                    <small><?= htmlspecialchars($u['telefono_contacto']) ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['rol'] !== 'administrador'): ?>
                                    <button class="btn btn-sm btn-primary" 
                                            onclick="abrirModalOperador(<?= $u['id'] ?>, '<?= htmlspecialchars($u['nombre_usuario']) ?>')">
                                        <i class="bi bi-briefcase"></i> Hacer Operador
                                    </button>
                                    
                                    <?php if($u['rol'] == 'operador'): ?>
                                        <form action="../../app/Controllers/UsuarioController.php" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres quitarle el rol de operador a este usuario?')">
                                            <input type="hidden" name="action" value="revocar_rol">
                                            <input type="hidden" name="usuario_id" value="<?= $u['id'] ?>">
                                            <button class="btn btn-sm btn-outline-danger" title="Revocar permisos">
                                                <i class="bi bi-person-dash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para promover a Operador -->
<div class="modal fade" id="modalOperador" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../../app/Controllers/UsuarioController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Configurar Operador Turístico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="hacer_operador">
                    <input type="hidden" name="usuario_id" id="modal_usuario_id">
                    
                    <div class="alert alert-info">
                        Vas a promover al usuario: <strong id="modal_nombre_usuario"></strong>
                    </div>
                    
                    <div class="mb-3">
                        <label>Nombre de la Agencia / Operador</label>
                        <input type="text" name="nombre_agencia" class="form-control" required placeholder="Ej: Viajes Puerto Boyacá">
                    </div>
                    <div class="mb-3">
                        <label>Teléfono de Contacto</label>
                        <input type="text" name="telefono" class="form-control" required placeholder="+57 300...">
                    </div>
                    <div class="mb-3">
                        <label>URL del Logo (Opcional)</label>
                        <input type="url" name="logo_url" class="form-control" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label>Descripción Breve</label>
                        <textarea name="descripcion" class="form-control" rows="3" maxlength="500" placeholder="Máximo 500 caracteres..."></textarea>
                        <div class="form-text">Describe los servicios que ofrece.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar y Promover</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function abrirModalOperador(id, nombre) {
        document.getElementById('modal_usuario_id').value = id;
        document.getElementById('modal_nombre_usuario').textContent = nombre;
        var modal = new bootstrap.Modal(document.getElementById('modalOperador'));
        modal.show();
    }
</script>
</body>
</html>