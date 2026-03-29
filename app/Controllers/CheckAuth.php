<?php
// app/Controllers/CheckAuth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificar si hay sesión iniciada (Usamos user_id, que es lo que guarda el AuthController)
if (!isset($_SESSION['user_id'])) {
    // Si no está logueado, mandar al login
    // Ajusta la ruta si es necesario según tu estructura
    header("Location: /PB-MAPS-PROJECT/htdocs_public/login.php?error=no_autenticado");
    exit;
}

// NOTA: Aquí NO verificamos roles. Dejamos pasar a los turistas para que puedan chatear.
?>