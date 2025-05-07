<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Recibir datos
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['username']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Datos de inicio de sesión incompletos."]);
    exit;
}

$username = $data['username'];
$password = $data['password'];

// Preparar consulta
$sql = "SELECT id, nombre, apellido, clave FROM admin_inv WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verificar contraseña (comparación directa ya que no usamos hash)
    if ($password === $user['clave']) {
        // Inicio de sesión exitoso
        echo json_encode([
            "success" => true, 
            "message" => "Inicio de sesión exitoso",
            "user" => [
                "id" => $user['id'],
                "name" => $user['nombre'] . ' ' . $user['apellido']
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
}

$stmt->close();
$conn->close();
?>