<?php
// Configuración de credenciales del servidor local
$host = 'localhost';
$db   = 'sistema_stock';
$user = 'root';     // Cambiar si tu usuario de MySQL es distinto
$pass = '';         // Cambiar si le pusiste contraseña a tu MySQL local
$charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Manejo de errores con excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devolver datos como arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Consultas preparadas reales para evitar SQL Injection
];

try {
    // Instancia de la conexión
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Si la conexión falla, se detiene el script y muestra el mensaje
    die("Error crítico de conexión a la base de datos: " . $e->getMessage());
}
?>