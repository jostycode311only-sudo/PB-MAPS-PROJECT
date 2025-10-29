<?php
// app/Controllers/AuthController.php

// 1. Iniciar la sesión ANTES de cualquier salida al navegador
session_start();

// 2. Incluir el Modelo User.php
require_once __DIR__ . '/../Models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Saneamiento de Entradas
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    // 4. Validación de campos vacíos
    if (empty($email) || empty($password)) {
        header("Location: ../../public/login.html?error=campos_vacios");
        exit;
    }

    // 5. Lógica de Autenticación
    
    $userModel = new User();
    // Intenta autenticar al usuario y recupera sus datos (incluyendo el rol)
    $authenticatedUser = $userModel->authenticateUser($email, $password);

    // 6. Redirección Basada en la Autenticación y el Rol
    
    if ($authenticatedUser) {
        // Autenticación Exitosa: Guardar datos esenciales en la sesión
        $_SESSION['user_id'] = $authenticatedUser['id_usuario'];
        $_SESSION['user_name'] = $authenticatedUser['nombre_usuario'];
        $_SESSION['user_rol'] = $authenticatedUser['rol']; // 'administrador' o 'publico'

        // Redirección por Rol
        if ($authenticatedUser['rol'] === 'administrador') {
            // Rol: Administrador - Redirigir al Panel CRUD
            header("Location: ../../public/admin/dashboard.html");
            exit;
        } else {
            // Rol: Público (Default) - Redirigir a la página principal
            header("Location: ../../public/index.html?login=success");
            exit;
        }

    } else {
        // Fallo de Autenticación
        header("Location: ../../public/login.html?error=credenciales_invalidas");
        exit;
    }

} else {
    // Si la solicitud no es POST, redirigir al inicio.
    header("Location: ../../public/index.html");
    exit;
}

?>