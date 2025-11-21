<?php
// app/Models/Lugar.php

require_once __DIR__ . '/../config/db.php';

class Lugar {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    public function insertarLugar(string $nombre, string $descripcion, string $urlImagen, int $adminId): bool {
        $sql = "INSERT INTO lugarturistico (nombre, descripcion, url_imagen, fk_admin_id) VALUES (?, ?, ?, ?)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $urlImagen, $adminId]);
        } catch (\PDOException $e) {
            error_log("Error al insertar lugar: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerLugares(): array|bool {
        $sql = "SELECT id, nombre, descripcion, url_imagen, fecha_creacion FROM lugarturistico ORDER BY nombre ASC";
        
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error al obtener lugares: " . $e->getMessage());
            return false;
        }
    }
    
    public function obtenerLugarPorId(int $id): array|bool {
        $sql = "SELECT id, nombre, descripcion, url_imagen FROM lugarturistico WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Error al obtener lugar por ID: " . $e->getMessage());
            return false;
        }
    }
    
    public function actualizarLugar(int $idLugar, string $nombre, string $descripcion, string $urlImagen): bool {
        $sql = "UPDATE lugarturistico SET nombre = ?, descripcion = ?, url_imagen = ? WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $urlImagen, $idLugar]);
        } catch (\PDOException $e) {
            error_log("Error al actualizar lugar: " . $e->getMessage());
            return false;
        }
    }
    
    public function eliminarLugar(int $idLugar): bool {
        $sql = "DELETE FROM lugarturistico WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$idLugar]);
            
        } catch (\PDOException $e) {
            error_log("Error al eliminar lugar: " . $e->getMessage());
            return false;
        }
    }
}