<?php
require __DIR__ . '/../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

Flight::path(__DIR__ . '/../app/Controllers');
Flight::path(__DIR__ . '/../app/Models');

// Load routes
require __DIR__ . '/../app/Routes/api.php';

Flight::start();
