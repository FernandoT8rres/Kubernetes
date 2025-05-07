<?php
include 'db.php';

// Establecer cabecera para respuesta JSON
header('Content-Type: application/json');

// Obtener el ID del almacén desde la solicitud
$storageId = isset($_GET['storage_id']) ? $_GET['storage_id'] : 'all';

// Preparar la consulta SQL según el almacén seleccionado
if ($storageId === 'all') {
    // Obtener todos los productos
    $sql = "SELECT * FROM stock_inv ORDER BY nombre_producto ASC";
    $stmt = $conn->prepare($sql);
} elseif ($storageId === 'sin_almacen') {
    // Obtener productos sin almacén asignado
    $sql = "SELECT * FROM stock_inv WHERE almacen IS NULL OR almacen = '' ORDER BY nombre_producto ASC";
    $stmt = $conn->prepare($sql);
} else {
    // Obtener productos de un almacén específico
    $sql = "SELECT s.* FROM stock_inv s 
            INNER JOIN almacenes a ON s.almacen = a.nombre_almacen 
            WHERE a.id = ? 
            ORDER BY s.nombre_producto ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $storageId);
}

try {
    // Ejecutar la consulta
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Obtener todos los productos como un array
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Formatear valores para mejor visualización
        $row['precio_compra'] = number_format((float)$row['precio_compra'], 2, '.', '');
        $row['precio_venta'] = number_format((float)$row['precio_venta'], 2, '.', '');
        
        // Añadir a la lista de productos
        $products[] = $row;
    }
    
    // Devolver productos en formato JSON
    echo json_encode($products);
    
} catch (Exception $e) {
    // Devolver error en formato JSON
    echo json_encode(['error' => 'Error al obtener productos: ' . $e->getMessage()]);
}

// Cerrar la consulta y conexión
$stmt->close();
$conn->close();
?>