<?php
// app/Controllers/LugarController.php

// 1. Iniciar Sesión y Cargar Modelo
session_start();
require_once __DIR__ . '/../Models/Lugar.php'; 

// 2. Control de Acceso (Seguridad)
// Un controlador debe ser accedido solo mediante POST (formulario).
// También verificamos el Rol (aunque la guardia en el dashboard ya debería haberlo hecho).
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SESSION['user_rol'] !== 'administrador') {
    // Si no es un POST o el rol es incorrecto, lo sacamos.
    header("Location: ../../public/dashboard.php?error=acceso_invalido");
    exit;
}

// 3. Extracción y Validación de Datos
// Verifica si los campos obligatorios existen y no están vacíos.
if (empty($_POST['nombre_lugar']) || empty($_POST['descripcion']) || empty($_SESSION['user_id'])) {
    header("Location: ../../public/dashboard.php?error=campos_incompletos");
    exit;
}

// 4. Sanitización y Preparación de Datos
// Se limpian y convierten los datos antes de enviarlos al Modelo.
$nombre      = htmlspecialchars($_POST['nombre_lugar']);
$descripcion = htmlspecialchars($_POST['descripcion']);
$latitud     = (float)$_POST['latitud']; // Conversión forzada a decimal
$longitud    = (float)$_POST['longitud']; // Conversión forzada a decimal
$urlImagen   = filter_var($_POST['url_imagen'], FILTER_SANITIZE_URL); // Limpia la URL

$adminId = (int)$_SESSION['user_id']; // ID del admin que está creando el lugar

// 5. Interacción con el Modelo (Lógica de Negocio)
$lugarModel = new Lugar();
$resultado = $lugarModel->insertarLugar($nombre, $descripcion, $latitud, $longitud, $urlImagen, $adminId);

// 6. Respuesta y Redirección
if ($resultado) {
    // Éxito: Redirigir con mensaje de confirmación
    header("Location: ../../public/dashboard.php?success=lugar_creado");
} else {
    // Error: Redirigir con mensaje de error
    header("Location: ../../public/dashboard.php?error=db_fallo_insert");
}
exit;
?>