<?php
include 'db.php';
header('Content-Type: application/json');

// Activar reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Verificar que la conexión está activa
    if ($conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos: " . $conn->connect_error);
    }
    
    // Usamos la conexión mysqli que está definida en db.php
    $stmt = $conn->prepare("SELECT * FROM despachadores_inv ORDER BY id DESC");
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $dispatchers = [];
    
    while($row = $result->fetch_assoc()) {
        $dispatchers[] = $row;
    }
    
    echo json_encode($dispatchers);
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>