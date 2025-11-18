<?php
// app/config/db.php

// Define las constantes de conexión a la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '');     
define('DB_NAME', 'pb_maps_db'); // Nombre de DB confirmado

/**
 * Función para establecer la conexión a la base de datos MySQL usando PDO.
 * @return PDO|null Retorna el objeto de conexión PDO o null si falla.
 */
function connectDB() {
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
        return $pdo;
    } catch (\PDOException $e) {
        error_log("Error de conexión a la BD: " . $e->getMessage()); 
        die("Error de conexión a la base de datos. Por favor, inténtelo más tarde.");
        return null;
    }
}
?>