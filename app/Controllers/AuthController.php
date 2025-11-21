<?php
// app/Controllers/AuthController.php

session_start();
require_once __DIR__ . '/../Models/User.php';

$dashboardPath = '/PB-MAPS-PROJECT/htdocs_public/admin/dashboard.php';
$loginPath = '/PB-MAPS-PROJECT/htdocs_public/login.html';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $loginPath?error=metodo_invalido");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: $loginPath?error=campos_vacios");
    exit;
}

$userModel = new User();
$user = $userModel->findUserByEmail($email, $password);

if ($user) {
    $_SESSION['is_logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nombre_usuario'];
    $_SESSION['user_rol'] = $user['rol']; 

    header("Location: $dashboardPath");
    exit;

} else {
    header("Location: $loginPath?error=credenciales_invalidas");
    exit;
}
?>