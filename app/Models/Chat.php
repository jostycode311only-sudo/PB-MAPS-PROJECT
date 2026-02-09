<?php
// app/Models/Chat.php

require_once __DIR__ . '/../config/db.php';

class Chat {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    // Enviar un mensaje nuevo
    public function enviarMensaje(int $emisorId, int $receptorId, string $mensaje): bool {
        $sql = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje) VALUES (?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$emisorId, $receptorId, $mensaje]);
        } catch (\PDOException $e) {
            error_log("Error al enviar mensaje: " . $e->getMessage());
            return false;
        }
    }

    // Obtener conversación entre dos usuarios (historial)
    public function obtenerConversacion(int $usuario1, int $usuario2): array {
        // Selecciona mensajes donde yo soy emisor y el otro receptor, O viceversa.
        $sql = "SELECT m.*, u.nombre_usuario as nombre_emisor 
                FROM mensajes m
                JOIN usuario u ON m.emisor_id = u.id
                WHERE (m.emisor_id = ? AND m.receptor_id = ?) 
                   OR (m.emisor_id = ? AND m.receptor_id = ?)
                ORDER BY m.fecha_envio ASC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuario1, $usuario2, $usuario2, $usuario1]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error al leer chat: " . $e->getMessage());
            return [];
        }
    }

    // Obtener lista de usuarios con los que he hablado (Bandeja de entrada)
    public function obtenerContactos(int $miId): array {
        $sql = "SELECT DISTINCT u.id, u.nombre_usuario, u.rol 
                FROM mensajes m
                JOIN usuario u ON (m.emisor_id = u.id OR m.receptor_id = u.id)
                WHERE (m.emisor_id = ? OR m.receptor_id = ?) AND u.id != ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$miId, $miId, $miId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }
}