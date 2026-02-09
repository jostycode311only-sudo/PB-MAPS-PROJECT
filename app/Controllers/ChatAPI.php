<?php
// app/Controllers/ChatAPI.php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../Models/Chat.php';

// Verificar seguridad: El usuario debe estar logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$chatModel = new Chat();
$miId = $_SESSION['user_id'];
$accion = $_GET['action'] ?? '';

switch ($accion) {
    case 'enviar':
        // Recibe datos JSON o POST
        $data = json_decode(file_get_contents('php://input'), true);
        $receptorId = $data['receptor_id'] ?? 0;
        $mensaje = $data['mensaje'] ?? '';

        if ($receptorId && !empty($mensaje)) {
            $resultado = $chatModel->enviarMensaje($miId, $receptorId, $mensaje);
            echo json_encode(['success' => $resultado]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        }
        break;

    case 'leer':
        // Obtener historial con un usuario específico
        $contactoId = $_GET['contacto_id'] ?? 0;
        if ($contactoId) {
            $mensajes = $chatModel->obtenerConversacion($miId, $contactoId);
            echo json_encode(['success' => true, 'mensajes' => $mensajes]);
        }
        break;

    case 'contactos':
        // Ver con quién he hablado
        $contactos = $chatModel->obtenerContactos($miId);
        echo json_encode(['success' => true, 'contactos' => $contactos]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        break;
}
?>
