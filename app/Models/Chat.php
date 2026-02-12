<?php
// app/Models/Chat.php
require_once __DIR__ . '/../config/db.php';

class Chat {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    // Guardar un mensaje
    public function enviarMensaje(int $emisorId, int $receptorId, string $mensaje): bool {
        $sql = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje) VALUES (?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$emisorId, $receptorId, $mensaje]);
        } catch (\PDOException $e) {
            // Guardar el error en el log de errores de PHP (no en pantalla)
            error_log("Error Chat::enviarMensaje: " . $e->getMessage());
            return false;
        }
    }

    // Leer historial entre dos personas
    public function obtenerConversacion(int $usuario1, int $usuario2): array {
        // Traer mensajes donde (Yo soy emisor Y Él receptor) O (Él emisor Y Yo receptor)
        $sql = "SELECT * FROM mensajes 
                WHERE (emisor_id = ? AND receptor_id = ?) 
                   OR (emisor_id = ? AND receptor_id = ?)
                ORDER BY fecha_envio ASC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuario1, $usuario2, $usuario2, $usuario1]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Importante: Array asociativo limpio
        } catch (\PDOException $e) {
            error_log("Error Chat::obtenerConversacion: " . $e->getMessage());
            return [];
        }
    }

    // Ver con quién he hablado (Bandeja de entrada)
    public function obtenerContactos(int $miId): array {
        $sql = "SELECT DISTINCT u.id, u.nombre_usuario, u.rol 
                FROM mensajes m
                JOIN usuario u ON (
                    CASE 
                        WHEN m.emisor_id = ? THEN m.receptor_id = u.id 
                        WHEN m.receptor_id = ? THEN m.emisor_id = u.id 
                    END
                )
                WHERE m.emisor_id = ? OR m.receptor_id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$miId, $miId, $miId, $miId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error Chat::obtenerContactos: " . $e->getMessage());
            return [];
        }
    }
}