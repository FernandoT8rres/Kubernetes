<?php
include 'db.php';
header('Content-Type: application/json');

try {
    // Recibir datos en formato JSON
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        throw new Exception("Datos no recibidos o formato incorrecto");
    }
    
    // Preparar consulta para usar $conn en lugar de $pdo
    $stmt = $conn->prepare("INSERT INTO clientes_inv 
                          (nombre, direccion, cp, poblacion, provincia, rpe, telefono, fax, cargo) 
                          VALUES 
                          (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Vincular parámetros usando MySQLi
    $nombre = $data['nombre'] . ' ' . $data['apellido'];
    $direccion = isset($data['direccion']) ? $data['direccion'] : '';
    $cp = $data['cp'];
    $poblacion = $data['poblacion'];
    $provincia = $data['provincia'];
    $rpe = $data['rpe'];
    $telefono = $data['telefono'];
    $fax = $data['fax'];
    $cargo = $data['cargo'];
    
    $stmt->bind_param("sssssssss", $nombre, $direccion, $cp, $poblacion, $provincia, $rpe, $telefono, $fax, $cargo);
    
    // Ejecutar consulta
    $result = $stmt->execute();
    
    if ($result) {
        echo json_encode(["success" => true, "message" => "Cliente registrado con éxito."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al registrar cliente."]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
$conn->close();
?>