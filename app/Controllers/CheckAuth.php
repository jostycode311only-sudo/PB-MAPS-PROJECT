<?php
// app/Controllers/CheckAuth.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loginPath = '/PB-MAPS-PROJECT/htdocs_public/login.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: $loginPath?error=no_autenticado");
    exit;
}

if ($_SESSION['user_rol'] !== 'administrador') {
    header("Location: $loginPath?error=acceso_denegado");
    exit;
}
?>