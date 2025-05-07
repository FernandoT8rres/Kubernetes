<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $_POST['productCode'];
    $nombre_producto = $_POST['productName'];
    $unidades_stock = $_POST['productStock'];
    $stock_minimo = $_POST['productMinStock'];
    $stock_maximo = $_POST['productMaxStock'];
    $precio_compra = $_POST['productPricePurchase'];
    $iva = $_POST['productIVA'];
    $precio_venta = $_POST['productPriceSale'];
    $almacen = $_POST['storage'];
    $anotaciones = $_POST['productRemarks'];
    $img = $_FILES['productImage']['name'];
    $fecha = $_POST['productDate'];

    // Mover la imagen a la carpeta deseada
    move_uploaded_file($_FILES['productImage']['tmp_name'], "uploads/" . $img);

    $sql = "INSERT INTO stock_inv (codigo, nombre_producto, unidades_stock, stock_minimo, stock_maximo, precio_compra, iva, precio_venta, img, fecha, almacen, Anotaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiissdsssss", $codigo, $nombre_producto, $unidades_stock, $stock_minimo, $stock_maximo, $precio_compra, $iva, $precio_venta, $img, $fecha, $almacen, $anotaciones);

    if ($stmt->execute()) {
        echo "Producto registrado con éxito!";
    } else {
        echo "Error al registrar el producto: " . $stmt->error;
    }
}
?>