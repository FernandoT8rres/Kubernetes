<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Obtener ID del producto
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "ID de producto inválido."]);
    exit;
}

// Preparar consulta
$sql = "SELECT id, codigo, nombre_producto, unidades_stock, stock_minimo, stock_maximo, 
               precio_compra, iva, precio_venta, fecha, almacen, Anotaciones 
        FROM stock_inv 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $product = $result->fetch_assoc();
    
    // Formatear fecha para el input type="date"
    if (!empty($product['fecha'])) {
        $product['fecha'] = date('Y-m-d', strtotime($product['fecha']));
    }
    
    echo json_encode(["success" => true, "product" => $product]);
} else {
    echo json_encode(["success" => false, "message" => "Producto no encontrado."]);
}

$stmt->close();
$conn->close();
?>