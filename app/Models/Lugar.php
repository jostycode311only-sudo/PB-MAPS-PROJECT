<?php
// app/Models/Lugar.php

// Incluimos la conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

class Lugar {
    private $pdo; // Objeto de conexión PDO

    // Nomenclatura de clases: PascalCase (Lugar)
    public function __construct() {
        $this->pdo = connectDB(); 
    }

    /**
     * @param string $nombre      Nombre del lugar.
     * @param string $descripcion Descripción.
     * @param float $latitud      Coordenada Latitud.
     * @param float $longitud     Coordenada Longitud.
     * @param string $urlImagen  URL de la imagen.
     * @param int $adminId       ID del administrador que lo registra.
     * @return bool
     */
    public function insertarLugar(string $nombre, string $descripcion, float $latitud, float $longitud, string $urlImagen, int $adminId): bool {
        // Nombramiento de variables y métodos: camelCase (insertarLugar)
        $sql = "INSERT INTO lugarturistico (nombre, descripcion, latitud, longitud, url_imagen, fk_admin_id) VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            // Se usan sentencias preparadas para seguridad (evitar inyección SQL)
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $latitud, $longitud, $urlImagen, $adminId]);
        } catch (\PDOException $e) {
            error_log("Error al insertar lugar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los lugares turísticos de la base de datos.
     * @return array|bool
     */
    public function obtenerLugares(): array|bool {
        $sql = "SELECT * FROM lugarturistico ORDER BY nombre ASC";
        
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(); // Retorna todos los resultados como array asociativo
        } catch (\PDOException $e) {
            error_log("Error al obtener lugares: " . $e->getMessage());
            return false;
        }
    }
    
    // Aquí se añadirán los métodos actualizarLugar() y eliminarLugar().
}
?>