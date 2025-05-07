<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

try {
    // Consulta para obtener todos los clientes
    $query = "SELECT id, nombre FROM clientes_inv ORDER BY nombre";
    $result = $conn->query($query);
    
    $clients = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
    }
    
    // Devolver la lista de clientes como JSON
    echo json_encode($clients);
} catch (Exception $e) {
    // En caso de error, devolver un mensaje de error
    echo json_encode([
        'error' => true,
        'message' => 'Error al obtener clientes: ' . $e->getMessage()
    ]);
}

// Cerrar la conexión
$conn->close();
?>