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

if (!$data || !isset($data['items']) || !isset($data['user']) || !isset($data['date'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos.'
    ]);
    exit;
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // Crear tabla de entradas si no existe
    $createTableQuery = "CREATE TABLE IF NOT EXISTS entradas_inv (
        id INT AUTO_INCREMENT PRIMARY KEY,
        producto_id INT NOT NULL,
        nombre_producto VARCHAR(255) NOT NULL,
        cantidad INT NOT NULL,
        cliente_id INT NOT NULL,
        cliente_nombre VARCHAR(255) NOT NULL,
        usuario_registro VARCHAR(255) NOT NULL,
        fecha_entrada DATE NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->query($createTableQuery);
    
    // Preparar consulta para insertar entrada
    $stmt = $conn->prepare("INSERT INTO entradas_inv (producto_id, nombre_producto, cantidad, cliente_id, cliente_nombre, usuario_registro, fecha_entrada) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Preparar consulta para actualizar stock
    $updateStockStmt = $conn->prepare("UPDATE stock_inv SET unidades_stock = unidades_stock + ? WHERE id = ?");
    
    // Insertar cada producto y actualizar stock
    foreach ($data['items'] as $item) {
        // Insertar entrada
        $stmt->bind_param(
            "isisiss",
            $item['productId'],
            $item['productName'],
            $item['quantity'],
            $item['clientId'],
            $item['clientName'],
            $data['user'],
            $data['date']
        );
        
        $stmt->execute();
        
        // Actualizar stock
        $updateStockStmt->bind_param("ii", $item['quantity'], $item['productId']);
        $updateStockStmt->execute();
    }
    
    // Confirmar transacción
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Entradas registradas correctamente.'
    ]);
} catch (Exception $e) {
    // Revertir cambios en caso de error
    $conn->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar entradas: ' . $e->getMessage()
    ]);
}

// Cerrar la conexión
$stmt->close();
$updateStockStmt->close();
$conn->close();
?>