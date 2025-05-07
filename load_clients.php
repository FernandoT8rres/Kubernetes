<?php
include 'db.php';
header('Content-Type: application/json');

try {
    // Usar $conn en lugar de $pdo
    $sql = "SELECT * FROM clientes_inv ORDER BY nombre";
    $result = $conn->query($sql);
    
    $clients = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Extraer nombre y apellido
            $nameParts = explode(' ', $row['nombre'], 2);
            $clients[] = [
                'id' => $row['id'],
                'name' => $nameParts[0],
                'last_name' => isset($nameParts[1]) ? $nameParts[1] : '',
                'position' => $row['cargo'],
                'phone' => $row['telefono']
            ];
        }
    }
    
    echo json_encode($clients);
} catch (Exception $e) {
    echo json_encode([]);
}
$conn->close();
?>
