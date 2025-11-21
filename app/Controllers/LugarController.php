<?php
// app/Controllers/LugarController.php

session_start();

// --- INCLUSIONES (SOLO UNA VEZ, AL PRINCIPIO) ---
require_once __DIR__ . '/../Models/Lugar.php';
require_once __DIR__ . '/../Models/Response.php'; 
// --------------------------------------------------

// Definir ruta de redirección para mensajes
$dashboardRedirect = '/PB-MAPS-PROJECT/htdocs_public/admin/dashboard.php';


// --- 1. MANEJO DE PETICIONES GET (LEER UN SOLO LUGAR PARA EDICIÓN) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
    
    $lugarModel = new Lugar();
    $id = (int)$_GET['id'];
    
    $lugar = $lugarModel->obtenerLugarPorId($id);

    if ($lugar) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['status' => 200, 'message' => 'Lugar obtenido con éxito.', 'lugar' => $lugar]);
        exit;
    } else {
        (new Response("Lugar no encontrado o ID inválido.", 404))->send();
        exit;
    }
}
// ----------------------------------------------------------------------


// --- 2. MANEJO DE PETICIONES POST (CREAR) Y PUT (ACTUALIZAR) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    
    // Comprobación de rol de seguridad para operaciones de escritura
    if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
        header("Location: {$dashboardRedirect}?error=acceso_denegado_escritura");
        exit;
    }

    $input = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : json_decode(file_get_contents('php://input'), true);

    $idLugar = $input['id_lugar'] ?? null; 
    
    $nombre = trim($input['nombre_lugar'] ?? $input['nombre'] ?? ''); 
    $descripcion = trim($input['descripcion'] ?? '');
    $urlImagen = trim($input['url_imagen'] ?? ''); 
    
    $adminId = $_SESSION['user_id'] ?? null; 

    // 3. Validación de datos obligatorios
    if (empty($nombre) || empty($descripcion) || empty($adminId)) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
             (new Response("El nombre y la descripción son campos obligatorios.", 400))->send();
        } else {
            header("Location: {$dashboardRedirect}?error=campos_incompletos");
        }
        exit;
    }

    // 4. Instanciar el modelo e iniciar variables
    $lugarModel = new Lugar();
    $resultado = false;
    $mensaje = '';
    $statusCode = 500;

    // 5. Lógica de Crear (POST) vs Actualizar (PUT)
    if ($idLugar) {
        // A) OPERACIÓN DE ACTUALIZACIÓN (PUT)
        if (!is_numeric($idLugar)) {
            (new Response("ID de lugar inválido para la actualización.", 400))->send();
            exit;
        }
        $resultado = $lugarModel->actualizarLugar((int)$idLugar, $nombre, $descripcion, $urlImagen);
        $mensaje = $resultado ? "Lugar turístico actualizado con éxito." : "Error al actualizar el lugar.";
        $statusCode = $resultado ? 200 : 500;
        
    } else {
        // B) OPERACIÓN DE CREACIÓN (POST)
        $resultado = $lugarModel->insertarLugar($nombre, $descripcion, $urlImagen, $adminId);
        $mensaje = $resultado ? "Lugar turístico creado con éxito." : "Error al guardar el lugar.";
        $statusCode = $resultado ? 201 : 500;
    }

    // 6. Respuesta final
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
         // Responder en JSON para peticiones AJAX (PUT)
        (new Response($mensaje, $statusCode))->send();
    } else {
        // Redirigir con mensaje para peticiones de formulario (POST)
        $param = ($resultado) ? 'success=lugar_creado' : 'error=db_fallo_insert';
        header("Location: {$dashboardRedirect}?{$param}");
    }
    exit;
}

// --- 3. MANEJO DE PETICIÓN DELETE (ELIMINAR) ---
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {

    if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
        (new Response("Acceso denegado para eliminar.", 403))->send();
        exit;
    }
    
    $lugarModel = new Lugar();
    $id = (int)$_GET['id'];
    
    $resultado = $lugarModel->eliminarLugar($id);

    if ($resultado) {
        (new Response("Lugar eliminado con éxito.", 200))->send();
    } else {
        (new Response("Error al eliminar el lugar.", 500))->send();
    }
    exit;
}

// Si se accede al controlador con otro método no soportado
(new Response("Método de solicitud no soportado.", 405))->send();

?>