<?php
include 'db.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("ID de cliente no válido");
    }
    
    // Usar $conn en lugar de $pdo
    $sql = "SELECT * FROM clientes_inv WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $client = $result->fetch_assoc();
        
        // Extraer nombre y apellido de nombre completo
        $nameParts = explode(' ', $client['nombre'], 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        
        $formattedClient = [
            'id' => $client['id'],
            'name' => $firstName,
            'lastName' => $lastName,
            'address' => $client['direccion'],
            'cp' => $client['cp'],
            'population' => $client['poblacion'],
            'province' => $client['provincia'],
            'rpe' => $client['rpe'],
            'phone' => $client['telefono'],
            'fax' => $client['fax'],
            'position' => $client['cargo']
        ];
        
        echo json_encode(["success" => true, "client" => $formattedClient]);
    } else {
        echo json_encode(["success" => false, "message" => "Cliente no encontrado."]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
$conn->close();
?>