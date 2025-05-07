<?php
include 'db.php';

// Verificar si la tabla almacenes existe
$table_check = $conn->query("SHOW TABLES LIKE 'almacenes'");

if ($table_check->num_rows > 0) {
    // La tabla existe, obtener de almacenes
    $sql = "SELECT id, nombre_almacen, ubicacion, descripcion FROM almacenes ORDER BY nombre_almacen";
    $result = $conn->query($sql);
} else {
    // La tabla no existe, obtener del campo almacen en stock_inv
    $sql = "SELECT DISTINCT almacen AS id, almacen AS nombre_almacen, '' AS ubicacion, '' AS descripcion 
            FROM stock_inv 
            WHERE almacen IS NOT NULL AND almacen != '' 
            ORDER BY almacen";
    $result = $conn->query($sql);
}

// Verificar si hay error en la consulta
if (!$result) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    exit;
}

// Obtener los almacenes
$storages = [];
while ($row = $result->fetch_assoc()) {
    $storages[] = $row;
}

// Devolver los almacenes en formato JSON
header('Content-Type: application/json');
echo json_encode($storages);

// Cerrar la conexión
$conn->close();
?>