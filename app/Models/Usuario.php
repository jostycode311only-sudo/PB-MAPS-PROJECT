<?php
// app/Models/Usuario.php
require_once __DIR__ . '/../config/db.php';

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    // Obtener todos los usuarios (para que el admin los vea)
    public function obtenerTodos() {
        // Hacemos LEFT JOIN para traer datos de agencia si existen
        $sql = "SELECT u.id, u.nombre_usuario, u.email, u.rol, u.fecha_creacion,
                       p.nombre_agencia, p.telefono_contacto
                FROM usuario u
                LEFT JOIN perfil_operador p ON u.id = p.usuario_id
                ORDER BY u.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Promover a Operador (Crear perfil de agencia)
    public function promoverAOperador($usuarioId, $nombreAgencia, $descripcion, $telefono, $logoUrl) {
        try {
            $this->pdo->beginTransaction();

            // 1. Cambiar Rol en tabla usuario
            $sqlRol = "UPDATE usuario SET rol = 'operador' WHERE id = ?";
            $stmtRol = $this->pdo->prepare($sqlRol);
            $stmtRol->execute([$usuarioId]);

            // 2. Crear o Actualizar Perfil de Agencia
            // Usamos ON DUPLICATE KEY UPDATE por si ya existía
            $sqlPerfil = "INSERT INTO perfil_operador (usuario_id, nombre_agencia, descripcion_agencia, telefono_contacto, logo_url) 
                          VALUES (?, ?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE 
                          nombre_agencia = VALUES(nombre_agencia), 
                          descripcion_agencia = VALUES(descripcion_agencia),
                          telefono_contacto = VALUES(telefono_contacto),
                          logo_url = VALUES(logo_url)";
            
            $stmtPerfil = $this->pdo->prepare($sqlPerfil);
            $stmtPerfil->execute([$usuarioId, $nombreAgencia, $descripcion, $telefono, $logoUrl]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error promoviendo usuario: " . $e->getMessage());
            return false;
        }
    }

    // Revocar permisos (Volver a usuario normal)
    public function revocarRol($usuarioId) {
        $sql = "UPDATE usuario SET rol = 'usuario_regular' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$usuarioId]);
        // Nota: No borramos el perfil_operador por si se quiere reactivar después, 
        // pero el rol ya no le dejará acceder.
    }
    
    // Obtener solo los operadores (para la página pública)
    public function obtenerOperadoresPublicos() {
        $sql = "SELECT u.id as usuario_id, u.email, p.* FROM usuario u 
                JOIN perfil_operador p ON u.id = p.usuario_id 
                WHERE u.rol = 'operador'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}