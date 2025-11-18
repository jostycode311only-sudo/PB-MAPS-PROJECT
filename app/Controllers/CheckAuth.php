<?php
// app/Controllers/check_auth.php

// 1. Iniciar la sesión de forma segura
// Verifica si la sesión no ha sido iniciada antes para evitar errores
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Definir el rol requerido para acceder a esta página (Siguiendo tu modelo de DB)
$ROL_REQUERIDO = 'administrador';

// 3. Comprobación de Seguridad: ¿Tiene sesión? Y ¿Es el rol correcto?
// !isset($_SESSION['user_id']): No hay ID de usuario (no ha iniciado sesión)
// $_SESSION['user_rol'] !== $ROL_REQUERIDO: El usuario NO es un administrador
if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] !== $ROL_REQUERIDO)) {
    
    // Si la comprobación falla, forzar el cierre de cualquier sesión parcial por seguridad
    if (isset($_SESSION['user_id'])) {
        session_unset();
        session_destroy();
    }
    
    // 4. Redirigir al login con acceso denegado
    // La ruta es ../../public/login.html ya que estamos en /app/Controllers/
    header("Location: ../../public/login.html?error=acceso_denegado");
    exit;
}

// Si el script llega a este punto, significa que el usuario tiene el rol 'administrador'
// y la página (dashboard.php) se cargará normalmente.

?>