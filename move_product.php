<?php
include 'db.php';

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y validar los datos
$productId = isset($_POST['product_id']) ? trim($_POST['product_id']) : '';
$storageId = isset($_POST['storage_id']) ? trim($_POST['storage_id']) : '';

// Validar datos
if (empty($productId)) {
    echo json_encode(['success' => false, 'message' => 'ID de producto no proporcionado']);
    exit;
}

// Si storageId está vacío, significa que queremos quitar el producto del almacén
$newStorage = $storageId === 'sin_almacen' ? NULL : $storageId;

// Actualizar el almacén del producto
$sql = "UPDATE stock_inv SET almacen = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

// Si newStorage es NULL, usamos bind_param diferente
if (is_null($newStorage)) {
    $stmt->bind_param("is", $newStorage, $productId);
} else {
    $stmt->bind_param("ss", $newStorage, $productId);
}

// Ejecutar la consulta
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $message = is_null($newStorage) ? 
            "Producto removido del almacén correctamente" : 
            "Producto movido al almacén correctamente";
        echo json_encode(['success' => true, 'message' => $message]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el producto o ya estaba en ese almacén']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al mover el producto: ' . $stmt->error]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
