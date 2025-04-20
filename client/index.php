<?php
// Inicia el búfer para evitar problemas con headers
ob_start();

$request_uri = $_SERVER['REQUEST_URI'];

// Limpia la URL para seguridad
$request_uri = filter_var($request_uri, FILTER_SANITIZE_URL);

$SHORT_CODE_LEN = 7;
$HOME = "https://home";
$NOT_FOUND = "/404.html";
$REDIRECT_CODE = "redirect.php?short_code=";

if ($request_uri == '/') {
	// Redirige a Home
	header("Location: $HOME");
    exit();
} else {
	// Verificar si el short_code está presente
    $short_code = basename($request_uri); 
       
	if (!empty($short_code) && strlen($short_code) == $SHORT_CODE_LEN) {
        header("Location: $REDIRECT_CODE".$short_code);        
        exit();
	} else {
        // Intentar redireccionar si existe el archivo o ruta
        $local_path = __DIR__ . $request_uri;
            
        if (file_exists($local_path) && !is_dir($local_path)) {
        	header("Location: .".$request_uri);
        	exit();    
        } else {
            http_response_code(404);
        	header("Location: $NOT_FOUND");
            exit();
        }
	}
}

ob_end_flush();
?>