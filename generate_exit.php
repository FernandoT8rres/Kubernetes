<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));

$productId = $data->productId;
$quantity = $data->quantity;

$stmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :productId AND stock >= :quantity");
$result = $stmt->execute(['quantity' => $quantity, 'productId' => $productId]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al generar la salida o stock insuficiente.']);
}
?>