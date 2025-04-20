<?php
require_once 'db_credentials.php';

// Función para obtener la conexión a la base de datos
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    // Configurar codificación UTF-8
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>