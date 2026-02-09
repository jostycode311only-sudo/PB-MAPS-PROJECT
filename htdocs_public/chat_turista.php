<?php
// htdocs_public/chat_turista.php
session_start();
require_once __DIR__ . '/../app/Controllers/CheckAuth.php'; 
require_once __DIR__ . '/../app/Models/Usuario.php'; 

// 1. Validar que haya un ID de operador en la URL
if (!isset($_GET['id'])) {
    header("Location: operadores.php");
    exit;
}

$operadorId = (int)$_GET['id'];
$miId = $_SESSION['user_id'];

// Evitar chatear con uno mismo
if ($operadorId === $miId) {
    echo "<script>alert('No puedes chatear contigo mismo.'); window.location.href='operadores.php';</script>";
    exit;
}

// Obtener datos básicos del operador (nombre) para mostrar en el título
// Usamos una consulta rápida directa o un método del modelo
// Por simplicidad, lo mostraremos genérico o podríamos agregar un método "obtenerUsuarioPorId" en User.php
// Asumiremos que carga vía JS para no complicar el backend ahora.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con Agencia - PB MAPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/chat.css">
    <style>
        body { background-color: #f0f2f5; }
        .chat-wrapper { max-width: 800px; margin: 20px auto; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .chat-header { background: #0d6efd; color: white; padding: 15px; display: flex; align-items: center; justify-content: space-between; }
        .chat-area { height: 500px; display: flex; flex-direction: column; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="operadores.php">⬅ Volver a Operadores</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="chat-wrapper">
        <div class="chat-header">
            <div>
                <h5 class="mb-0" id="chatTitle">Conectando...</h5>
                <small>Chat directo con el operador</small>
            </div>
            <div id="statusIndicator" class="badge bg-success">En línea</div>
        </div>

        <div class="chat-area">
            <!-- Área de mensajes -->
            <div class="messages-box" id="cajaMensajes">
                <div class="text-center mt-5 text-muted">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p>Cargando historial...</p>
                </div>
            </div>

            <!-- Input -->
            <form class="chat-input-area" id="formChatTurista">
                <input type="hidden" id="receptorId" value="<?= $operadorId ?>">
                <input type="text" id="inputMensaje" class="form-control" placeholder="Escribe tu consulta aquí..." autocomplete="off">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
</div>

<script src="js/chat_turista.js"></script>
</body>
</html>