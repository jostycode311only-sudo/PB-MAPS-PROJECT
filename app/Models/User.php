<?php
// app/Models/User.php

require_once __DIR__ . '/../config/db.php';

class User {
    private $pdo; // Objeto de conexión a la base de datos

    public function __construct() {
        $this->pdo = connectDB(); 
    }

    // Método de Registro (Implementado anteriormente)
    public function registrarUsuario(string $nombre, string $email, string $password): bool {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (nombre_usuario, email, password_hash) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $email, $password_hash]);
        } catch (\PDOException $e) {
            error_log("Error de registro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Autentica a un usuario por email y contraseña.
     * @param string $email Correo electrónico.
     * @param string $password Contraseña sin hash.
     * @return array|null Retorna los datos del usuario (id, nombre, rol) si es exitoso, o null si falla.
     */
    public function authenticateUser(string $email, string $password): ?array {
        // 1. Consulta SQL para buscar el usuario por email
        $sql = "SELECT id_usuario, nombre_usuario, password_hash, rol FROM usuario WHERE email = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // 2. Si el usuario existe, verificar la contraseña
            if ($user && password_verify($password, $user['password_hash'])) {
                // 3. Autenticación exitosa: limpiar el hash antes de retornar los datos
                unset($user['password_hash']);
                return $user; // Retorna id_usuario, nombre_usuario y rol
            }
            
            // 4. Si el usuario no existe o la contraseña es incorrecta
            return null;
        } catch (\PDOException $e) {
            error_log("Error de autenticación: " . $e->getMessage());
            return null;
        }
    }
}
?>