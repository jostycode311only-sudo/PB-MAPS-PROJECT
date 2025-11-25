<?php
// app/Models/User.php

require_once __DIR__ . '/../config/db.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    // Función de Login (Ya la tenías bien)
    public function findUserByEmail(string $email, string $password): array|bool {
        $sql = "SELECT id, nombre_usuario, email, password_hash, rol FROM usuario WHERE email = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']);
                return $user;
            }
            
            return false;

        } catch (\PDOException $e) {
            error_log("Error de DB en findUserByEmail: " . $e->getMessage());
            return false;
        }
    }

    // ---------------------------------------------------------
    //  AQUÍ ESTÁ LA CORRECCIÓN: EL CÓDIGO REAL DE REGISTRO
    // ---------------------------------------------------------
    public function registrarUsuario(string $nombre, string $email, string $password): bool {
        // 1. Encriptar contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // 2. Definir rol por defecto (Usamos 'admin' según tus capturas de base de datos)
        $rol = 'administrador'; 

        // 3. Consulta SQL
        $sql = "INSERT INTO usuario (nombre_usuario, email, password_hash, rol) VALUES (?, ?, ?, ?)";

        try {
            $stmt = $this->pdo->prepare($sql);
            
            // 4. Ejecutar la inserción
            if ($stmt->execute([$nombre, $email, $passwordHash, $rol])) {
                return true; // ¡Éxito!
            }
            
            return false; // Falló sin lanzar excepción

        } catch (\PDOException $e) {
            // MODO DEPURACIÓN: Esto mostrará el error en pantalla si falla
            die("ERROR SQL CRÍTICO AL REGISTRAR: " . $e->getMessage()); 
        }
    }
}