<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}

// Recibir datos
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado.'
    ]);
    exit;
}

$id = $data['id'];

// Iniciar transacción
$conn->begin_transaction();

try {
    // Primero, obtener la información de la entrada para actualizar el inventario
    $query = "SELECT producto_id, cantidad FROM entradas_inv WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Entrada no encontrada.");
    }
    
    $entry = $result->fetch_assoc();
    $productId = $entry['producto_id'];
    $quantity = $entry['cantidad'];
    
    // Actualizar el inventario (restar la cantidad)
    $updateQuery = "UPDATE stock_inv SET unidades_stock = unidades_stock - ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $quantity, $productId);
    $updateStmt->execute();
    
    // Eliminar la entrada
    $deleteQuery = "DELETE FROM entradas_inv WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $id);
    $deleteStmt->execute();
    
    // Confirmar transacción
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Entrada eliminada correctamente.'
    ]);
} catch (Exception $e) {
    // Revertir cambios en caso de error
    $conn->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar entrada: ' . $e->getMessage()
    ]);
}

// Cerrar la conexión
$stmt->close();
if (isset($updateStmt)) $updateStmt->close();
if (isset($deleteStmt)) $deleteStmt->close();
$conn->close();
?>