<?php
// Incluir archivo de conexión a la base de datos
include 'db.php';

// Configurar encabezados para la respuesta JSON
header('Content-Type: application/json');

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido."
    ]);
    exit;
}

try {
    // Obtener datos del formulario
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;
    $nombre_producto = isset($_POST['nombre_producto']) ? $_POST['nombre_producto'] : null;
    $unidades_stock = isset($_POST['unidades_stock']) ? intval($_POST['unidades_stock']) : 0;
    $stock_minimo = isset($_POST['stock_minimo']) ? intval($_POST['stock_minimo']) : 0;
    $stock_maximo = isset($_POST['stock_maximo']) ? intval($_POST['stock_maximo']) : 0;
    $precio_compra = isset($_POST['precio_compra']) ? floatval($_POST['precio_compra']) : 0.00;
    $iva = isset($_POST['iva']) ? $_POST['iva'] : '0';
    $precio_venta = isset($_POST['precio_venta']) ? floatval($_POST['precio_venta']) : 0.00;
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $almacen = isset($_POST['almacen']) ? $_POST['almacen'] : '';
    $anotaciones = isset($_POST['anotaciones']) ? $_POST['anotaciones'] : '';
    
    // Procesar imagen si se subió una
    $imgData = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['size'] > 0) {
        $imgData = file_get_contents($_FILES['imagen']['tmp_name']);
    }
    
    // Preparar la consulta SQL
    // Si se proporcionó un ID específico, usarlo; de lo contrario, dejar que MySQL asigne uno automáticamente
    if (!empty($productId)) {
        $sql = "INSERT INTO stock_inv (id, codigo, nombre_producto, unidades_stock, stock_minimo, stock_maximo, 
                precio_compra, iva, precio_venta, img, fecha, almacen, Anotaciones) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issiiddssssss", $productId, $codigo, $nombre_producto, $unidades_stock, $stock_minimo, 
                       $stock_maximo, $precio_compra, $iva, $precio_venta, $imgData, $fecha, $almacen, $anotaciones);
    } else {
        $sql = "INSERT INTO stock_inv (codigo, nombre_producto, unidades_stock, stock_minimo, stock_maximo, 
                precio_compra, iva, precio_venta, img, fecha, almacen, Anotaciones) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiddssssss", $codigo, $nombre_producto, $unidades_stock, $stock_minimo, 
                       $stock_maximo, $precio_compra, $iva, $precio_venta, $imgData, $fecha, $almacen, $anotaciones);
    }
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Producto guardado exitosamente.",
            "productId" => $conn->insert_id
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al guardar el producto: " . $stmt->error
        ]);
    }
    
    // Cerrar la consulta
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}

// Cerrar la conexión
$conn->close();
?>