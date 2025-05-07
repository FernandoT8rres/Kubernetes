<?php
include 'db.php';

header('Content-Type: application/json');

// Consultar todos los administradores
$sql = "SELECT id, correo, nombre, apellido FROM admin_inv";
$result = $conn->query($sql);

$admins = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}

echo json_encode($admins);
$conn->close();
?>