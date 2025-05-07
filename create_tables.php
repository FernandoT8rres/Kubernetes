<?php
// Este archivo crea la tabla de almacenes si no existe
include 'db.php';

// SQL para crear tabla de almacenes
$sql_create_storage = "
CREATE TABLE IF NOT EXISTS `almacenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_almacen` varchar(100) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_almacen` (`nombre_almacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    // Crear tabla de almacenes
    if ($conn->query($sql_create_storage) === TRUE) {
        echo "Tabla 'almacenes' verificada/creada correctamente.<br>";
    } else {
        echo "Error al crear tabla 'almacenes': " . $conn->error . "<br>";
    }
    
    echo "La configuración de la base de datos se ha completado correctamente.";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>