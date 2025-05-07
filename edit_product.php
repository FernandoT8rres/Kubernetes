<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['product-id'];
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
    $fecha = $_POST['productDate'];

    // Si se sube una nueva imagen, manejarla
    if ($_FILES['productImage']['name']) {
        $img = $_FILES['productImage']['name'];
        move_uploaded_file($_FILES['productImage']['tmp_name'], "uploads/" . $img);
        $sql = "UPDATE stock_inv SET codigo=?, nombre_producto=?, unidades_stock=?, stock_minimo=?, stock_maximo=?, precio_compra=?, iva=?, precio_venta=?, img=?, fecha=?, almacen=?, Anotaciones=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiissdssssi", $codigo, $nombre_producto, $unidades_stock, $stock_minimo, $stock_maximo, $precio_compra, $iva, $precio_venta, $img, $fecha, $almacen, $anotaciones, $id);
    } else {
        $sql = "UPDATE stock_inv SET codigo=?, nombre_producto=?, unidades_stock=?, stock_minimo=?, stock_maximo=?, precio_compra=?, iva=?, precio_venta=?, fecha=?, almacen=?, Anotaciones=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiissdsssi", $codigo, $nombre_producto, $unidades_stock, $stock_minimo, $stock_maximo, $precio_compra, $iva, $precio_venta, $fecha, $almacen, $anotaciones, $id);
    }

    if ($stmt->execute()) {
        echo "Producto actualizado con éxito!";
    } else {
        echo "Error al actualizar el producto: " . $stmt->error;
    }
}
?>