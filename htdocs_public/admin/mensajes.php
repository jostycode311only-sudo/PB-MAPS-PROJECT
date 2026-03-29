<?php
// htdocs_public/admin/mensajes.php
session_start();
require_once __DIR__ . '/../../app/Controllers/CheckAuth.php'; 

// Seguridad extra: Solo admin
if (!isset($_SESSION['user_rol']) || ($_SESSION['user_rol'] !== 'administrador' && $_SESSION['user_rol'] !== 'operador')) {
    header("Location: ../index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de Mensajes - PB MAPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/chat.css">
    <style>
        .admin-nav .nav-link.active { background-color: #0d6efd; color: white !important; border-radius: 5px; }
        .admin-nav .nav-link { color: #ccc; }
        .admin-nav .nav-link:hover { color: white; }
    </style>
</head>
<body class="bg-light">

<!-- BARRA DE NAVEGACIÓN ADMINISTRADOR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">PB-MAPS <span class="badge bg-danger">Operador</span></a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 admin-nav">
                
                <li class="nav-item">
                    <a class="nav-link active" href="mensajes.php"><i class="bi bi-chat-dots"></i> Mensajería</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small d-none d-md-block">Hola, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="../../app/Controllers/LogoutController.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-3">Atención al Turista</h3>
            
            <div class="chat-container shadow">
                <!-- Barra lateral: Lista de Usuarios -->
                <div class="chat-sidebar" id="listaContactos">
                    <div class="p-3 text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p>Cargando chats...</p>
                    </div>
                </div>

                <!-- Área Principal: Chat -->
                <div class="chat-area">
                    <div class="chat-header">
                        <span id="chatHeaderName">Selecciona un chat...</span>
                    </div>

                    <div class="messages-box" id="cajaMensajes">
                        <!-- Aquí se inyectan los mensajes con JS -->
                        <div class="text-center text-muted mt-5">
                            <p>Selecciona un usuario de la izquierda para comenzar a hablar.</p>
                        </div>
                    </div>

                    <form class="chat-input-area" id="formEnviarMensaje">
                        <input type="text" id="inputMensaje" class="form-control" placeholder="Escribe un mensaje..." disabled autocomplete="off">
                        <button type="submit" id="btnEnviar" class="btn btn-primary" disabled>
                            Enviar ➤
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="../js/chat_admin.js"></script>
</body>
</html>