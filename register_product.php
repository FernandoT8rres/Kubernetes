<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));

$stmt = $pdo->prepare("INSERT INTO stock_inv 
(stock_minimo, stock_maximo, unidades_stock, codigo, nombre_producto, precio_compra, iva, precio_venta, img, fecha, almacen, Anotaciones) 
VALUES (:stock_minimo, :stock_maximo, :unidades_stock, :codigo, :nombre_producto, :precio_compra, :iva, :precio_venta, :img, :fecha, :almacen, :Anotaciones)");

$result = $stmt->execute([
    'stock_minimo' => $data->stock_minimo,
    'stock_maximo' => $data->stock_maximo,
    'unidades_stock' => $data->unidades_stock,
    'codigo' => $data->codigo,
    'nombre_producto' => $data->nombre_producto,
    'precio_compra' => $data->precio_compra,
    'iva' => $data->iva,
    'precio_venta' => $data->precio_venta,
    'img' => base64_decode($data->img), // Suponiendo imagen como base64
    'fecha' => $data->fecha,
    'almacen' => $data->almacen,
    'Anotaciones' => $data->Anotaciones
]);

echo json_encode(['success' => $result]);
?>
