<?php
include 'db.php';

header('Content-Type: application/json');

// Activar reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Recibir y validar datos
$data = json_decode(file_get_contents("php://input"), true);

// Guardar los datos recibidos para depuración
error_log("Datos recibidos para eliminar: " . json_encode($data));

if (!isset($data['id']) || empty($data['id'])) {
    echo json_encode(["success" => false, "message" => "ID no proporcionado."]);
    exit;
}

// Convertir a entero para seguridad
$id = intval($data['id']);

try {
    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("DELETE FROM despachadores_inv WHERE id = ?");
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    
    // Ejecutar la consulta y verificar si fue exitosa
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    // Verificar si alguna fila fue afectada
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Despachador eliminado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "No se encontró el despachador con ID: " . $id]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}

$conn->close();
?>