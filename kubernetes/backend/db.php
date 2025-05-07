<?php
// Activar reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos para Kubernetes
$host = getenv('DB_HOST') ?: 'mysql-service';
$db = getenv('DB_NAME') ?: 'sci_db';
$user = getenv('DB_USER') ?: 'inventario_user';
$pass = getenv('DB_PASSWORD') ?: 'inventario_password';

// Crear conexión usando MySQLi
try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    // Verificar si hay error en la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    // Establecer charset para evitar problemas con caracteres especiales
    $conn->set_charset("utf8");
    
    // También crear conexión PDO para scripts que la utilicen
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>