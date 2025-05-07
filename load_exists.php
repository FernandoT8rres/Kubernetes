<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

try {
    // Consulta para obtener todas las entradas
    $query = "SELECT id, producto_id as productId, nombre_producto as productName, 
              cantidad as quantity, cliente_nombre as client, 
              fecha_entrada as exitDate, usuario_registro as remarks 
              FROM entradas_inv ORDER BY fecha_registro DESC";
    
    $result = $conn->query($query);
    
    $entries = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $entries[] = $row;
        }
    }
    
    // Devolver la lista de entradas como JSON
    echo json_encode($entries);
} catch (Exception $e) {
    // En caso de error, devolver un mensaje de error
    echo json_encode([
        'error' => true,
        'message' => 'Error al obtener entradas: ' . $e->getMessage()
    ]);
}

// Cerrar la conexión
$conn->close();
?>