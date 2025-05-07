<?php
include 'db.php';
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        throw new Exception("ID de cliente no válido");
    }
    
    // Usar $conn en lugar de $pdo
    $sql = "DELETE FROM clientes_inv WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $data['id']);
    $result = $stmt->execute();
    
    if ($result) {
        echo json_encode(["success" => true, "message" => "Cliente eliminado con éxito."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar cliente."]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
$conn->close();
?>
