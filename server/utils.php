<?php
// utils.php

// Función para generar un código corto único (Base62: a-z, A-Z, 0-9)
function generateShortCode($length = 7) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $short_code = '';
    for ($i = 0; $i < $length; $i++) {
        $short_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $short_code;
}

// Función para verificar si un código corto ya existe en la base de datos
function isShortCodeUnique($conn, $short_code) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM freetable_urls WHERE short_code = ?");
    $stmt->bind_param("s", $short_code);
    $stmt->execute();
    
    // Vincular el resultado a una variable
    $stmt->bind_result($count);
    $stmt->fetch(); // Obtener el resultado
    
    $stmt->close();
    return $count == 0; // Devuelve true si el código es único (count == 0)
}

function getUrl($url) {
    $LEN = 32;

    if (strlen($url) > $LEN) {
        return substr($url, 0, $LEN) . "...";
    } else {
        return $url;
    }
}

?>