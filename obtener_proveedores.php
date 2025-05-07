<?php
include 'db.php';

$result = $mysqli->query("SELECT * FROM proveedores_inv");
$datos = [];

while ($row = $result->fetch_assoc()) {
  $datos[] = $row;
}

echo json_encode($datos);
?>
