<?php
include 'db.php';

header('Content-Type: application/json');

// Activar reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Recibir datos
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Guardar los datos recibidos para depuración
error_log("Datos recibidos: " . $json);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Datos no recibidos o formato incorrecto. " . json_last_error_msg()]);
    exit;
}

// Verificar que la conexión está activa
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . $conn->connect_error]);
    exit;
}

try {
    // Preparar consulta usando mysqli
    $stmt = $conn->prepare("INSERT INTO despachadores_inv 
            (nombre, direccion, cp, poblacion, area, rpe, telefono, extension, cargo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    // Asegurarse de que todos los valores estén definidos
    $nombre = isset($data['name']) ? $data['name'] : '';
    $direccion = isset($data['address']) ? $data['address'] : '';
    $cp = isset($data['cp']) ? $data['cp'] : '';
    $poblacion = isset($data['population']) ? $data['population'] : '';
    $area = isset($data['area']) ? $data['area'] : '';
    $rpe = isset($data['rpe']) ? $data['rpe'] : '';
    $telefono = isset($data['phone']) ? $data['phone'] : '';
    $extension = isset($data['extension']) ? $data['extension'] : '';
    $cargo = isset($data['position']) ? $data['position'] : '';
    
    $stmt->bind_param("sssssssss", 
        $nombre,
        $direccion,
        $cp,
        $poblacion,
        $area,
        $rpe,
        $telefono,
        $extension,
        $cargo
    );
    
    // Ejecutar la consulta y manejar errores específicamente
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    $id = $conn->insert_id;
    
    if ($id > 0) {
        echo json_encode([
            "success" => true, 
            "message" => "Despachador registrado con éxito.",
            "id" => $id
        ]);
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Error al obtener ID del registro insertado."
        ]);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false, 
        "message" => "Error: " . $e->getMessage()
    ]);
}

$conn->close();
?>