<?php
ob_start();

require __DIR__ . "/../vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Framework\Support\Monolog;

header('Access-Control-Allow-Origin: *');
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization, action, email, password, token, credential");
header('Access-Control-Max-Age: 86400');
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    header("Access-Control-Allow-Headers: zX-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization, action, email, password, token, credential");
    header("HTTP/1.1 200 OK");
    return;
}


/*
* ERROR REGISTER
* **/
function __fatalHandler()
{
    $error = error_get_last();

    // Check if it's a core/fatal error, otherwise it's a normal shutdown
    if ($error !== NULL && in_array($error['type'],
            array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING,
                E_COMPILE_ERROR, E_COMPILE_WARNING,E_RECOVERABLE_ERROR))) {
    //    echo "<pre>fatal error:\n";
    //    print_r($error);
    //    echo "</pre>";
        
        if(!empty($error['line'])) {
            $Log = new Monolog("api", "{$error['message']} [{$error['file']} linha: {$error['line']}]");
            $Log->alert();
            $Log->emergency();
        }
        die;
    }
}

register_shutdown_function('__fatalHandler');
/* END ERROR REGISTER */

// $version_api = "";
// if (preg_match('~^/api/(v(?:1|\d+))/~', $_SERVER['REQUEST_URI'], $matches)) {
//     $version_api = (!empty($matches) ? "'\'v" . preg_replace('/\D/', '', $matches[1]) : "");
// }

// $version_api = (!empty($version_api) ? str_replace("'", "", $version_api) : "");

/**
 * API ROUTES
 * index
 */
$route = new Router(url(), ":");
//$route->namespace("Source\App\Api" . $version_api);
$route->namespace("Source\Infra\Controllers\Api");

// remove \
//$version = stripslashes($version_api);

$route->group("/api");
$route->post("/me", "AuthController:index");
//$route->post("/{$version}/{club}/sailor/create", "Sailors:addSailor", middleware: \Source\Framework\Middlewares\Transactions\TransactionMiddleware::class);


/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "errors" => [
            "type " => "endpoint_not_found",
            "message" => "Não foi possível processar a requisição API"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

ob_end_flush();