<?php
// app/Controllers/LugarController.php

// Activar errores temporalmente
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Incluir el modelo de Lugar
require_once __DIR__ . '/../Models/Lugar.php';

// Validar que se ha enviado una acción
$action = $_POST['action'] ?? '';


// ELIMINAR UN LUGAR

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'eliminar') {
    
    // Seguridad: Solo admin
    if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
        header("Location: ../../htdocs_public/index.php");
        exit;
    }

    $id = $_POST['id'] ?? null;
    
    if (empty($id)) {
        header("Location: ../../htdocs_public/admin/dashboard.php?error=campos_incompletos_eliminar");
        exit;
    }

    // Ejecutar el borrado
    $lugarModel = new Lugar();
    $lugarModel->eliminarLugar((int)$id);
    
    header("Location: ../../htdocs_public/admin/dashboard.php?success=eliminado");
    exit;
}


// CREAR UN NUEVO LUGAR

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'crear') {
    
    if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
        header("Location: ../../htdocs_public/index.php");
        exit;
    }

    // Capturar datos del formulario (Usando los 'name' del HTML)
    $nombre = trim($_POST['nombre'] ?? ''); 
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? 'Sitio General');
    $urlImagen = trim($_POST['url_imagen'] ?? ''); 
    $telefono = trim($_POST['telefono'] ?? '');
    
    $adminId = $_SESSION['user_id'] ?? null; 

    if (empty($nombre) || empty($descripcion)) {
        header("Location: ../../htdocs_public/admin/dashboard.php?error=campos_incompletos_crear");
        exit;
    }

    $lugarModel = new Lugar();
    $resultado = $lugarModel->insertarLugar($nombre, $descripcion, $categoria, $urlImagen, $telefono, $adminId);

    if ($resultado) {
        header("Location: ../../htdocs_public/admin/dashboard.php?success=lugar_creado");
    } else {
        header("Location: ../../htdocs_public/admin/dashboard.php?error=db_fallo_insert");
    }
    exit;
}


// C. EDITAR UN LUGAR EXISTENTE 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'editar') {
    
    if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
        header("Location: ../../htdocs_public/index.php");
        exit;
    }

    // Usamos los nombres correctos (name="id", name="nombre")
    $id = $_POST['id'] ?? null;
    $nombre = trim($_POST['nombre'] ?? ''); 
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? 'Sitio General');
    $urlImagen = trim($_POST['url_imagen'] ?? ''); 
    $telefono = trim($_POST['telefono'] ?? '');
    
    if (empty($id) || empty($nombre) || empty($descripcion)) {
        header("Location: ../../htdocs_public/admin/dashboard.php?error=campos_incompletos_editar");
        exit;
    }

    // CORRECCIÓN 2: Se usa la función actualizarLugar() que sí existe en tu Modelo
    $lugarModel = new Lugar();
    $resultado = $lugarModel->actualizarLugar((int)$id, $nombre, $descripcion, $categoria, $urlImagen, $telefono);
    
    if ($resultado) {
        header("Location: ../../htdocs_public/admin/dashboard.php?success=actualizado");
    } else {
        header("Location: ../../htdocs_public/admin/dashboard.php?error=db_fallo_actualizar");
    }
    exit;
}

// Si no entra a ningún bloque, devolver al dashboard
header("Location: ../../htdocs_public/admin/dashboard.php");
exit;
?>