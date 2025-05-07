<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));

$clientName = $data->clientName;
$productId = $data->productId;
$quantity = $data->quantity;


$stmt = $pdo->prepare("INSERT INTO orders (client_name, product_id, quantity) VALUES (:clientName, :productId, :quantity)");
$result = $stmt->execute(['clientName' => $clientName, 'productId' => $productId, 'quantity' => $quantity]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar la venta.']);
}
?>