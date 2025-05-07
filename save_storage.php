<?php
include 'db.php';

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y validar los datos del formulario
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$id = isset($_POST['id']) ? trim($_POST['id']) : '';

// Validar que el nombre no esté vacío
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'El nombre del almacén es obligatorio']);
    exit;
}

// Crear tabla de almacenes si no existe
$create_table_sql = "CREATE TABLE IF NOT EXISTS almacenes (
    id VARCHAR(36) PRIMARY KEY,
    nombre_almacen VARCHAR(100) NOT NULL,
    ubicacion VARCHAR(200),
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($create_table_sql)) {
    echo json_encode(['success' => false, 'message' => 'Error al crear tabla de almacenes: ' . $conn->error]);
    exit;
}

// Generar un nuevo ID si es un nuevo almacén
if (empty($id)) {
    $id = uniqid();
}

// Verificar si ya existe un almacén con ese nombre (excepto si es el mismo que estamos editando)
$check_sql = "SELECT id FROM almacenes WHERE nombre_almacen = ? AND id != ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $name, $id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Ya existe un almacén con ese nombre']);
    exit;
}

// Preparar la consulta SQL para insertar o actualizar
$sql = "INSERT INTO almacenes (id, nombre_almacen, ubicacion, descripcion) 
        VALUES (?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
        nombre_almacen = VALUES(nombre_almacen), 
        ubicacion = VALUES(ubicacion), 
        descripcion = VALUES(descripcion)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $id, $name, $location, $description);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Almacén guardado correctamente', 'id' => $id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el almacén: ' . $stmt->error]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>