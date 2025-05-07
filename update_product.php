<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Recibir datos
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
    exit;
}

// Extraer valores
$id = $data['id'];
$codigo = $data['codigo'];
$nombre_producto = $data['nombre_producto'];
$unidades_stock = $data['unidades_stock'];
$stock_minimo = $data['stock_minimo'];
$stock_maximo = $data['stock_maximo'];
$precio_compra = $data['precio_compra'];
$iva = $data['iva'];
$precio_venta = $data['precio_venta'];
$fecha = $data['fecha'];
$almacen = $data['almacen'];
$anotaciones = $data['anotaciones'];

// Preparar consulta sin la imagen por ahora
$sql = "UPDATE stock_inv SET 
        codigo = ?, 
        nombre_producto = ?, 
        unidades_stock = ?, 
        stock_minimo = ?, 
        stock_maximo = ?, 
        precio_compra = ?, 
        iva = ?, 
        precio_venta = ?, 
        fecha = ?, 
        almacen = ?, 
        Anotaciones = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssiiddsdsssi", 
    $codigo, 
    $nombre_producto, 
    $unidades_stock, 
    $stock_minimo, 
    $stock_maximo, 
    $precio_compra, 
    $iva, 
    $precio_venta, 
    $fecha, 
    $almacen, 
    $anotaciones, 
    $id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Producto actualizado correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar el producto: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>