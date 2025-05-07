<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM exits"); // Asegúrate de tener una tabla de salidas
$exits = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($exits);
?>