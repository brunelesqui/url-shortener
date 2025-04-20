<?php
// add_url.php

require_once 'db_conn.php'; // Configuración de la base de datos
require_once 'url_model.php'; // Modelo para la tabla urls

// Procesar la solicitud GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['url'])) {
    // Obtener la conexión a la base de datos
    $conn = getDBConnection();

    // Instanciar el modelo
    $urlModel = new UrlModel($conn);

    try {
        // Agregar la URL y obtener el código corto
        $short_code = $urlModel->addUrl($_GET['url']);
        
        // Respuesta exitosa
        //header('Content-Type: text/plain');
        //echo "URL shortened successfully. Shortcode: " . $short_code;
        
        // Opcional: devolver JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'short_code' => $short_code]);
    } catch (Exception $e) {
        // Manejo de errores
        http_response_code(400); // Bad Request o 500 según el caso
        
        //header('Content-Type: text/plain');
        //echo "Error: " . $e->getMessage();
        
        // Opcional: devolver JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    // Cerrar la conexión
    $conn->close();
} else {
    http_response_code(400); // Bad Request
    header('Content-Type: text/plain');
    echo "Error: The 'url' parameter is required in the GET request.";
}
?>