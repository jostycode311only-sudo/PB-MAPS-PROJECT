<?php
// app/Controllers/RegisterController.php
session_start();
// Usamos la conexión directa para tener control total del SQL y el ROL
require_once __DIR__ . '/../config/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Recoger y Sanear datos
    // Usamos filter_var como tenías, es buena práctica
    $nombre = filter_var(trim($_POST['nombre'] ?? ''), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // 2. Validaciones Básicas
    if (empty($nombre) || empty($email) || empty($password)) {
        header("Location: ../../htdocs_public/registro.html?error=campos_vacios");
        exit;
    }

    // Validación: Contraseñas coinciden (Manteniendo tu lógica)
    if ($password !== $confirm_password) {
        header("Location: ../../htdocs_public/registro.html?error=contraseñas_no_coinciden");
        exit;
    }

    // 3. Conexión a Base de Datos
    $pdo = connectDB();
    
    // 4. Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header("Location: ../../htdocs_public/registro.html?error=email_existente");
        exit;
    }

    // 5. Encriptar contraseña y DEFINIR ROL TURISTA
    // Aquí está la clave: forzamos 'usuario_regular' sin importar qué diga el modelo
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $rol = 'usuario_regular'; 

    // 6. Insertar en la Base de Datos
    $sql = "INSERT INTO usuario (nombre_usuario, email, password_hash, rol) VALUES (?, ?, ?, ?)";
    $stmtInsert = $pdo->prepare($sql);
    
    if ($stmtInsert->execute([$nombre, $email, $passwordHash, $rol])) {
        // Éxito: Redirigir al Login
        header("Location: ../../htdocs_public/login.php?success=registro_exitoso");
        exit;
    } else {
        // Fallo técnico
        header("Location: ../../htdocs_public/registro.html?error=fallo_registro");
        exit;
    }

} else {
    // Si intentan entrar por GET, mandar al home
    header("Location: ../../htdocs_public/index.html");
    exit;
}
?>