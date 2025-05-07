<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

try {
    // Consulta para obtener productos con unidades en stock mayor a 0
    $query = "SELECT id, codigo, nombre_producto, unidades_stock FROM stock_inv WHERE unidades_stock > 0";
    $result = $conn->query($query);
    
    $products = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    
    // Devolver la lista de productos como JSON
    echo json_encode($products);
} catch (Exception $e) {
    // En caso de error, devolver un mensaje de error
    echo json_encode([
        'error' => true,
        'message' => 'Error al obtener productos: ' . $e->getMessage()
    ]);
}

// Cerrar la conexión
$conn->close();
?>