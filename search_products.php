<?php
include 'db.php';

header('Content-Type: application/json');

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

// Obtener término de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Preparar consulta con búsqueda por código o nombre del producto
$sql = "SELECT id, codigo, nombre_producto, unidades_stock, stock_minimo, stock_maximo, 
               precio_compra, iva, precio_venta, fecha, almacen, Anotaciones 
        FROM stock_inv 
        WHERE codigo LIKE ? OR nombre_producto LIKE ?";

$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    // Convertir la fecha al formato yyyy-mm-dd para el input type="date"
    if (!empty($row['fecha'])) {
        $row['fecha'] = date('Y-m-d', strtotime($row['fecha']));
    }
    $products[] = $row;
}

echo json_encode(["success" => true, "products" => $products]);

$stmt->close();
$conn->close();
?>