<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar si se proporcionó un ID
if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "ID de despachador no proporcionado."]);
    exit;
}

$id = $_GET['id'];

try {
    // Preparar consulta usando mysqli
    $stmt = $conn->prepare("SELECT * FROM despachadores_inv WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dispatcher = $result->fetch_assoc();
    
    if ($dispatcher) {
        echo json_encode(["success" => true, "data" => $dispatcher]);
    } else {
        echo json_encode(["success" => false, "message" => "Despachador no encontrado."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>