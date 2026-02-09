<?php
// htdocs_public/admin/mensajes.php
session_start();
require_once __DIR__ . '/../../app/Controllers/CheckAuth.php'; 

// Seguridad extra: Solo admin
if ($_SESSION['user_rol'] !== 'administrador') {
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
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">⬅ Volver al Dashboard</a>
        <span class="navbar-text text-white">Mensajería Interna</span>
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