<?php
include 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$stmt = $conn->prepare("UPDATE proveedores_inv SET nombre=?, direccion=?, cp=?, poblacion=?, provincia=?, nif=?, telefono=?, fax=?, www=?, correo_e=? WHERE id=?");
$stmt->bind_param("ssssssssssi", 
    $data['nombre'], 
    $data['direccion'], 
    $data['cp'], 
    $data['poblacion'], 
    $data['provincia'], 
    $data['nif'], 
    $data['telefono'], 
    $data['fax'], 
    $data['www'], 
    $data['correo_e'],
    $data['id']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Proveedor actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>