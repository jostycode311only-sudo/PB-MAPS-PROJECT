<?php
// app/Controllers/RegisterController.php

// 1. Incluir el Modelo User.php para acceder a la lógica de negocio y la DB.
require_once __DIR__ . '/../Models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Saneamiento de Entradas (Sanitization)
    $nombre = filter_var($_POST['nombre'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 3. Validación Inicial de Datos
    if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: ../../htdocs_public/registro.html?error=campos_vacios");
        exit;
    }
    
    if ($password !== $confirm_password) {
        header("Location: ../../htdocs_public/registro.html?error=contraseñas_no_coinciden");
        exit;
    }

    // 4. Lógica del Modelo: Registro en la Base de Datos
    
    // Instanciar el Modelo (Crea la conexión a la DB)
    $userModel = new User();
    
    // Llamar al método del Modelo para intentar registrar al usuario
    $registroExitoso = $userModel->registrarUsuario($nombre, $email, $password);

    // 5. Redirección Basada en el Resultado
    if ($registroExitoso) {
        // Éxito: Redirigir al Login
        header("Location: ../../htdocs_public/login.html?success=registro_exitoso");
        exit;
    } else {
        // Fallo: Redirigir al Registro (Ej. el email ya está registrado, lo que causaría una excepción en el Modelo)
        header("Location: ../../htdocs_public/registro.html?error=email_existente");
        exit;
    }

} else {
    // Si la solicitud no es POST, redirigir al inicio.
    header("Location: ../../htdocs_public/index.html");
    exit;
}

?>