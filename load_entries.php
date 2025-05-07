<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM entries"); // Asegúrate de tener una tabla de entradas
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($entries);
?>