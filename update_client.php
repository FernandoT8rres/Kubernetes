<?php
include 'db.php';
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        throw new Exception("ID de cliente no válido");
    }
    
    $stmt = $pdo->prepare("UPDATE clientes_inv SET 
                          nombre = :nombre,
                          direccion = :direccion,
                          cp = :cp,
                          poblacion = :poblacion,
                          provincia = :provincia,
                          rpe = :rpe,
                          telefono = :telefono,
                          fax = :fax,
                          cargo = :cargo
                          WHERE id = :id");
    
    $result = $stmt->execute([
        'id' => $data['id'],
        'nombre' => $data['name'] . ' ' . $data['lastName'],
        'direccion' => isset($data['address']) ? $data['address'] : '',
        'cp' => $data['cp'],
        'poblacion' => $data['population'],
        'provincia' => $data['province'],
        'rpe' => $data['rpe'],
        'telefono' => $data['phone'],
        'fax' => $data['fax'],
        'cargo' => $data['position']
    ]);
    
    if ($result) {
        echo json_encode(["success" => true, "message" => "Cliente actualizado con éxito."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar cliente."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>