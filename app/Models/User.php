<?php
// app/Models/User.php

require_once __DIR__ . '/../config/db.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

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
}