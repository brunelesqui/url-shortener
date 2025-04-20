<?php
// url_model.php

require_once 'utils.php'; // Incluir funciones de utilidad

class UrlModel {
    private $conn;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Validar y agregar una URL a la tabla
    public function addUrl($long_url) {
        // Sanitizar y validar la URL
        $long_url = filter_var($long_url, FILTER_SANITIZE_URL);
        if (!filter_var($long_url, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL");
        }

        // Generar un código corto único
        $max_attempts = 5;
        $short_code = '';
        for ($i = 0; $i < $max_attempts; $i++) {
            $short_code = generateShortCode();
            if (isShortCodeUnique($this->conn, $short_code)) {
                break;
            }
            if ($i == $max_attempts - 1) {
                throw new Exception("Could not generate a unique shortcode");
            }
        }

        // Insertar la URL en la base de datos
        $stmt = $this->conn->prepare("INSERT INTO freetable_urls (long_url, short_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $long_url, $short_code);
        if (!$stmt->execute()) {
            throw new Exception("Error: " . $this->conn->error);
        }
        $stmt->close();

        return $short_code;
    }
    
    // Obtener todos los registros
    public function getAllUrls() {
    	try {
                $stmt = $this->conn->prepare("SELECT short_code, long_url FROM freetable_urls ORDER BY id DESC");
                if ($stmt === false) {
                    throw new Exception("Error preparing query: " . $this->conn->error);
                }

                $stmt->execute();
                //$result = $stmt->get_result();
                $stmt->bind_result($short_code, $long_url);

                $urls = [];
                while ($stmt->fetch()) {
                    $urls[] = [
                        'short_code' => $short_code,
                        'long_url' => $long_url
                    ];
                }

                $stmt->close();
                return $urls;
            } catch (Exception $e) {
                throw $e;
            }
	}

        
    // Obtener la URL larga y aumentar el contador de clics
    public function getLongUrl($short_code) {
        // Validar el código corto (básica sanitización)
        $short_code = trim($short_code);
        if (empty($short_code) || strlen($short_code) > 10) {
            throw new Exception("Invalid shortcode.");
        }

        try {
            // Buscar la URL larga
            $stmt = $this->conn->prepare("SELECT long_url FROM freetable_urls WHERE short_code = ?");
            if ($stmt === false) {
                throw new Exception("Error preparing search query: " . $this->conn->error);
            }

            $stmt->bind_param("s", $short_code);
            $stmt->execute();
            $stmt->bind_result($long_url);
            $fetch_result = $stmt->fetch();
            $stmt->close();

            if (!$fetch_result) {
                throw new Exception("Shortcode not found.");
            }

            return $long_url;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>