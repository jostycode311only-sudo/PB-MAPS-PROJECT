<?php
// app/Models/Lugar.php
require_once __DIR__ . '/../config/db.php';

class Lugar {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    // 1. Insertar nuevo lugar (Actualizado con Teléfono)
    public function insertarLugar($nombre, $descripcion, $categoria, $url_imagen, $telefono, $admin_id) {
        $sql = "INSERT INTO lugarturistico (nombre, descripcion, categoria, url_imagen, telefono, fk_admin_id, fecha_creacion) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $categoria, $url_imagen, $telefono, $admin_id]);
        } catch (PDOException $e) {
            error_log("Error Lugar::insertarLugar: " . $e->getMessage());
            return false;
        }
    }

    // 2. Obtener TODOS los lugares
    public function obtenerLugares() {
        $sql = "SELECT * FROM lugarturistico ORDER BY fecha_creacion DESC";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error Lugar::obtenerLugares: " . $e->getMessage());
            return [];
        }
    }

    // 3. Obtener Lugares POR CATEGORÍA
    public function obtenerLugaresPorCategoria($categoria) {
        $sql = "SELECT * FROM lugarturistico WHERE categoria = ? ORDER BY fecha_creacion DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$categoria]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error Lugar::obtenerLugaresPorCategoria: " . $e->getMessage());
            return [];
        }
    }

    // 4. Obtener un solo lugar por ID
    public function obtenerLugarPorId($id) {
        $sql = "SELECT * FROM lugarturistico WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // 5. Actualizar lugar (Actualizado con Teléfono)
    public function actualizarLugar($id, $nombre, $descripcion, $categoria, $url_imagen, $telefono) {
        $sql = "UPDATE lugarturistico SET nombre = ?, descripcion = ?, categoria = ?, url_imagen = ?, telefono = ? WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $categoria, $url_imagen, $telefono, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // 6. Eliminar lugar
    public function eliminarLugar($id) {
        $sql = "DELETE FROM lugarturistico WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}