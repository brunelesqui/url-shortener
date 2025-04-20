<?php
header("Content-Type: application/json");

require_once 'db_conn.php'; // Configuración de la base de datos
require_once 'url_model.php'; // Modelo para la tabla urls

try {
    $conn = getDBConnection();
        
    $shortener = new UrlModel($conn);
    $urls = $shortener->getAllUrls();

    echo json_encode([
        "success" => true,
        "data" => $urls
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
