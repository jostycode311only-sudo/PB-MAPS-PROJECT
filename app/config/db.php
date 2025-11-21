<?php
// app/config/db.php

function connectDB(): PDO {
    $host = 'localhost';
    $db   = 'pb_maps_db'; // Asegúrate que este sea el nombre de tu DB
    $user = 'root'; 
    $pass = '';     
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        error_log("Error de conexión a la DB: " . $e->getMessage());
        die("Error de conexión a la base de datos.");
    }
}