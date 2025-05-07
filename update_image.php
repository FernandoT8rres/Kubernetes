<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Verificar si se ha subido un archivo
if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "No se ha proporcionado una imagen válida."]);
    exit;
}

// Obtener ID del producto
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "ID de producto inválido."]);
    exit;
}

// Leer el archivo de imagen
$imageData = file_get_contents($_FILES['image']['tmp_name']);

// Preparar consulta para actualizar solo la imagen
$sql = "UPDATE stock_inv SET img = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("bi", $imageData, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Imagen actualizada correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar la imagen: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>