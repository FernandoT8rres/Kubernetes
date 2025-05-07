<?php
include 'db.php';

$result = $conn->query("SELECT * FROM proveedores_inv ORDER BY id DESC");

$providers = [];

while ($row = $result->fetch_assoc()) {
    $providers[] = $row;
}

echo json_encode($providers);
?>