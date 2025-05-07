<?php
include 'db.php';

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y validar el ID
$id = isset($_POST['id']) ? trim($_POST['id']) : '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID de almacén no proporcionado']);
    exit;
}

// Verificar si la tabla almacenes existe
$table_check = $conn->query("SHOW TABLES LIKE 'almacenes'");
if ($table_check->num_rows > 0) {
    // Eliminar el almacén de la tabla almacenes
    $sql = "DELETE FROM almacenes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    
    // Verificar si se eliminó algo
    if ($stmt->affected_rows > 0) {
        // Eliminar la referencia del almacén en los productos
        $update_sql = "UPDATE stock_inv SET almacen = NULL WHERE almacen = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $id);
        $update_stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Almacén eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el almacén']);
    }
    
    $stmt->close();
} else {
    // Si la tabla no existe, actualizar directamente en stock_inv
    $sql = "UPDATE stock_inv SET almacen = NULL WHERE almacen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Almacén eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el almacén o ya estaba eliminado']);
    }
    
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>