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
$data = json_decode(file_get_contents("php://input"), true);

// Guardar los datos recibidos para depuración
error_log("Datos recibidos para actualizar: " . json_encode($data));

if (!$data || !isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos o formato incorrecto."]);
    exit;
}

// Verificar que la conexión está activa
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . $conn->connect_error]);
    exit;
}

try {
    // Preparar consulta usando mysqli
    $stmt = $conn->prepare("UPDATE despachadores_inv 
                          SET nombre = ?, direccion = ?, cp = ?, poblacion = ?, 
                              area = ?, rpe = ?, telefono = ?, extension = ?, cargo = ? 
                          WHERE id = ?");
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    // Asegurarse de que todos los valores estén definidos
    $id = intval($data['id']);
    $nombre = isset($data['name']) ? $data['name'] : '';
    $direccion = isset($data['address']) ? $data['address'] : '';
    $cp = isset($data['cp']) ? $data['cp'] : '';
    $poblacion = isset($data['population']) ? $data['population'] : '';
    $area = isset($data['area']) ? $data['area'] : '';
    $rpe = isset($data['rpe']) ? $data['rpe'] : '';
    $telefono = isset($data['phone']) ? $data['phone'] : '';
    $extension = isset($data['extension']) ? $data['extension'] : '';
    $cargo = isset($data['position']) ? $data['position'] : '';
    
    $stmt->bind_param("sssssssssi", 
        $nombre,
        $direccion,
        $cp,
        $poblacion,
        $area,
        $rpe,
        $telefono,
        $extension,
        $cargo,
        $id
    );
    
    $result = $stmt->execute();
    
    if ($result) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "success" => true, 
                "message" => "Despachador actualizado con éxito."
            ]);
        } else {
            echo json_encode([
                "success" => true, 
                "message" => "No se realizaron cambios o el despachador no existe."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Error al actualizar despachador: " . $stmt->error
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