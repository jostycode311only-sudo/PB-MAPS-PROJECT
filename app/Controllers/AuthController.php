<?php
// app/Controllers/AuthController.php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Recibir datos
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // 2. Validar vacíos
    if (empty($email) || empty($password)) {
        header("Location: ../../htdocs_public/login.php?error=campos_vacios");
        exit;
    }

    // 3. Buscar usuario en BD
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Verificar contraseña y Redirigir según ROL
    if ($user && password_verify($password, $user['password_hash'])) {
        
        // Guardar datos en sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre_usuario'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rol'] = $user['rol']; // Importante: Aquí guardamos quién es

        // --- SEMÁFORO DE REDIRECCIÓN ---
        
        if ($user['rol'] === 'administrador') {
            // El jefe va al panel de control
            header("Location: ../../htdocs_public/admin/dashboard.php");
            
        } elseif ($user['rol'] === 'operador') {
            // El operador va directo a ver sus mensajes (o dashboard si tuviera)
            header("Location: ../../htdocs_public/admin/mensajes.php");
            
        } else {
            // El turista (usuario_regular) va a buscar agencias
            header("Location: ../../htdocs_public/operadores.php");
        }
        exit;

    } else {
        // Contraseña incorrecta
        header("Location: ../../htdocs_public/login.php?error=credenciales_incorrectas");
        exit;
    }
} else {
    header("Location: ../../htdocs_public/login.php");
    exit;
}
?>