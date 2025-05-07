<?php
include 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Datos no recibidos']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO proveedores_inv (nombre, direccion, cp, poblacion, provincia, nif, telefono, fax, www, correo_e) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", 
    $data['nombre'], 
    $data['direccion'], 
    $data['cp'], 
    $data['poblacion'], 
    $data['provincia'], 
    $data['nif'], 
    $data['telefono'], 
    $data['fax'], 
    $data['www'], 
    $data['correo_e']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Proveedor guardado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>