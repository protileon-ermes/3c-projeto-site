<?php

require_once __DIR__ . '/../config/config.php';

//cors
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

in_array($origin, $allowedOrigins) ?
    header("Access-Control-Allow-Origin: $origin") : null;
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

//roteamento
$uri = strtok($_SERVER['REQUEST_URI'], '?');

match ($uri) {
    '/api/users' => require_once __DIR__ . '/../src/api.php',
    default => notFound()
};

function notFound()
{
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
    exit;
}
