<?php
// app/Controllers/ChatAPI.php

// 1. CONFIGURACIÓN DE ERRORES (CRUCIAL)
// Desactivar impresión de errores en pantalla para no romper el JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Forzar cabecera JSON
header('Content-Type: application/json; charset=utf-8');

session_start();
require_once __DIR__ . '/../Models/Chat.php';

// Función para responder y salir limpiamente
function responderJSON($data) {
    echo json_encode($data);
    exit;
}

try {
    // Verificar sesión
    if (!isset($_SESSION['user_id'])) {
        responderJSON(['success' => false, 'error' => 'No autorizado. Inicia sesión.']);
    }

    $chatModel = new Chat();
    $miId = $_SESSION['user_id'];
    $accion = $_GET['action'] ?? '';

    switch ($accion) {
        case 'enviar':
            // Leer JSON de entrada
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
            
            $receptorId = isset($data['receptor_id']) ? (int)$data['receptor_id'] : 0;
            $mensaje = isset($data['mensaje']) ? trim($data['mensaje']) : '';

            if ($receptorId > 0 && !empty($mensaje)) {
                $resultado = $chatModel->enviarMensaje($miId, $receptorId, $mensaje);
                if ($resultado) {
                    responderJSON(['success' => true]);
                } else {
                    responderJSON(['success' => false, 'error' => 'Error BD al guardar.']);
                }
            } else {
                responderJSON(['success' => false, 'error' => 'Datos incompletos (ID o Mensaje vacíos).']);
            }
            break;

        case 'leer':
            $contactoId = isset($_GET['contacto_id']) ? (int)$_GET['contacto_id'] : 0;
            if ($contactoId > 0) {
                $mensajes = $chatModel->obtenerConversacion($miId, $contactoId);
                responderJSON(['success' => true, 'mensajes' => $mensajes]);
            } else {
                responderJSON(['success' => false, 'error' => 'Falta ID contacto.']);
            }
            break;

        case 'contactos':
            $contactos = $chatModel->obtenerContactos($miId);
            responderJSON(['success' => true, 'contactos' => $contactos]);
            break;

        default:
            responderJSON(['success' => false, 'error' => 'Acción no válida: ' . $accion]);
            break;
    }

} catch (Exception $e) {
    // Si hay error fatal, capturarlo y enviarlo como JSON
    responderJSON(['success' => false, 'error' => 'Error Interno: ' . $e->getMessage()]);
}
?>