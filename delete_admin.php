<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['id']) || empty($data['id'])) {
        echo json_encode(["success" => false, "message" => "ID no proporcionado."]);
        exit;
    }
    
    $id = intval($data['id']);
    
    // Preparar y ejecutar la consulta
    $sql = "DELETE FROM admin_inv WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Administrador eliminado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar: " . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}

$conn->close();
?>