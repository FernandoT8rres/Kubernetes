<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));

$nombre_almacen = $data->nombre_almacen;

$stmt = $pdo->prepare("INSERT INTO almacen_inv (nombre_almacen) VALUES (:nombre_almacen)");
$result = $stmt->execute(['nombre_almacen' => $nombre_almacen]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el almacén.']);
}
?>
