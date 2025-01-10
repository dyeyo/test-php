<?php

require_once __DIR__ . '/../controllers/UserController.php';

header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");  

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case ($uri === '/user/index' && $method === 'GET'):
        (new UserController())->index();
        break;

    case ($uri === '/user/create' && $method === 'POST'):
        (new UserController())->create();
        break;

    case (preg_match('/\/user\/show\/(\d+)/', $uri, $matches) && $method === 'GET'):
        $userId = $matches[1];
        (new UserController())->show($userId);
        break;

    case (preg_match('/\/user\/edit\/(\d+)/', $uri, $matches) && $method === 'POST'):
        $userId = $matches[1];
        (new UserController())->edit($userId);
        break;

    case (preg_match('/\/user\/delete\/(\d+)/', $uri, $matches) && $method === 'DELETE'):
            $userId = $matches[1];
            (new UserController())->delete($userId);
            break;

            
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}
