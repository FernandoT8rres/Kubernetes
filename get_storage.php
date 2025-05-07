<?php
include 'db.php';

// Verificar si se proporciona un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'ID de almacén no proporcionado']);
    exit;
}

$id = $_GET['id'];

// Verificar si la tabla de almacenes existe
$table_check = $conn->query("SHOW TABLES LIKE 'almacenes'");
if ($table_check->num_rows == 0) {
    // La tabla no existe, obtener información del campo almacen en stock_inv
    $sql = "SELECT almacen AS id, almacen AS nombre_almacen, '' AS ubicacion, '' AS descripcion 
            FROM stock_inv 
            WHERE almacen = ? 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
} else {
    // La tabla existe, obtener de almacenes
    $sql = "SELECT * FROM almacenes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $storage = $result->fetch_assoc();
    echo json_encode($storage);
} else {
    echo json_encode(['error' => 'Almacén no encontrado']);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>