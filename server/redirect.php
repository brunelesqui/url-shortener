<?php
// redirect.php

require_once 'db_conn.php'; // Configuración de la base de datos
require_once 'url_model.php'; // Modelo para la tabla urls

$NOT_FOUND = "/404.html";
$short_code = $_GET['short_code'];

// Verificar si el short_code está presente
if (!empty($short_code)) {
    // Obtener la conexión a la base de datos
    $conn = getDBConnection();

    // Instanciar el modelo
    $urlModel = new UrlModel($conn);

    try {
        // Obtener la URL larga y aumentar el contador de clics
        $long_url = $urlModel->getLongUrl($short_code);

        // Redirigir al usuario (HTTP 301 para redirección permanente)
        header("Location: $long_url", true, 301);
        exit;
    } catch (Exception $e) {
        // Manejo de errores (ej. código no encontrado)
        http_response_code(404); // Not Found
        header('Content-Type: text/plain');
        echo "Error: " . $e->getMessage();
        
        //http_response_code(404);
        //header("Location: $NOT_FOUND");
        //$conn->close();
            
        exit();
    }

    // Cerrar la conexión
    $conn->close();
} 
?>