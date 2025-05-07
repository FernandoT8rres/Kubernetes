<?php
include 'db.php';

// Verificar si se recibieron datos JSON
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Datos inválidos."]);
    exit;
}

// Obtener datos del formulario
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$name = $data['name'] ?? '';
$lastName = $data['lastName'] ?? '';

// Validar que no estén vacíos
if (empty($username) || empty($password) || empty($name) || empty($lastName)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
    exit;
}

// Verificar si el usuario ya existe
$checkSql = "SELECT id FROM admin_inv WHERE correo = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $username);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Este correo ya está registrado."]);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

// Preparar la contraseña (sin hash por limitación de VARCHAR(10))
// NOTA: Se recomienda ampliar el campo clave en la BD para permitir hash seguro
$safePassword = substr($password, 0, 10); // Limitar a 10 caracteres por restricción de la BD

// Preparar y ejecutar la consulta
$sql = "INSERT INTO admin_inv (correo, clave, nombre, apellido) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $safePassword, $name, $lastName);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Administrador registrado correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al registrar: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>