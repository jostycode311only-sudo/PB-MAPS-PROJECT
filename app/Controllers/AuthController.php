<?php
// app/Controllers/AuthController.php

// 1. Iniciar sesión PHP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recibir datos del formulario
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validación básica
    if (empty($email) || empty($password)) {
        header("Location: ../../htdocs_public/login.php?error=campos_vacios");
        exit;
    }

    // Buscar usuario en la base de datos
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Verificar contraseña
    if ($user && password_verify($password, $user['password_hash'])) {
        
        // GUARDAR DATOS EN LA SESIÓN 
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre_usuario'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['is_logged_in'] = true;

        // AQUÍ ESTÁ LA LÓGICA DE REDIRECCIÓN (SEMÁFORO) 
        
        if ($user['rol'] === 'administrador') {
            // Si es ADMIN, lo mandamos al panel de control
            header("Location: ../../htdocs_public/admin/dashboard.php");
            
        } elseif ($user['rol'] === 'operador') {
            // Si es OPERADOR, lo mandamos a ver sus mensajes
            header("Location: ../../htdocs_public/admin/mensajes.php");
            
        } else {
            // CASO 3: SI ES USUARIO NORMAL (TURISTA)
            // Lo mandamos a la página de inicio para que vea hoteles y sitios
            header("Location: ../../htdocs_public/index.php");
        }
        exit;

    } else {
        // Contraseña incorrecta
        header("Location: ../../htdocs_public/login.php?error=credenciales_invalidas");
        exit;
    }
} else {
    // Si intentan entrar directo al archivo sin enviar formulario
    header("Location: ../../htdocs_public/login.php");
    exit;
}
?>