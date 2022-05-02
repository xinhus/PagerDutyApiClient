<?php


use GuzzleHttp\Psr7\Response;

include_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->load();

$response = match ($_SERVER['REDIRECT_URL'] ?? '') {
    '/env' => new Response('200', [], '<pre>' . print_r($_ENV,1) . '</pre>'),
    default => new Response('404', [], 'Not Found.'),
};

$headers = $response->getHeaders();
foreach ($headers as $name => $header) {
    header("{$name}: {$header[0]}");
}
header("Access-Control-Allow-Origin: *");
http_response_code($response->getStatusCode());
echo $response->getBody();