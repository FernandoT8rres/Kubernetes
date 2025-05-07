<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['id']) || empty($data['id'])) {
        echo json_encode(["success" => false, "message" => "ID no proporcionado."]);
        exit;
    }
    
    $id = intval($data['id']);
    $username = $data['username'] ?? '';
    $name = $data['name'] ?? '';
    $lastName = $data['lastName'] ?? '';
    $password = $data['password'] ?? '';
    
    // Validar datos
    if (empty($username) || empty($name) || empty($lastName)) {
        echo json_encode(["success" => false, "message" => "Los campos nombre, apellido y correo son obligatorios."]);
        exit;
    }
    
    // Si se proporcionó una nueva contraseña
    if (!empty($password)) {
        // Limitar a 10 caracteres por restricción de la BD
        $safePassword = substr($password, 0, 10);
        
        $sql = "UPDATE admin_inv SET correo = ?, nombre = ?, apellido = ?, clave = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $name, $lastName, $safePassword, $id);
    } else {
        // Si no se proporcionó contraseña, actualizar solo los otros campos
        $sql = "UPDATE admin_inv SET correo = ?, nombre = ?, apellido = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $name, $lastName, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Administrador actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar: " . $conn->error]);
    }
    
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Obtener información de un administrador específico
    $id = intval($_GET['id']);
    
    $sql = "SELECT id, correo, nombre, apellido FROM admin_inv WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $admin]);
    } else {
        echo json_encode(["success" => false, "message" => "Administrador no encontrado."]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}

$conn->close();
?>