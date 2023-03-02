<?php

use Controllers\ProductsController;
use Http\Router;
use Http\Request;
use Model\DataBase;

require __DIR__ . '/vendor/autoload.php';

session_start();

//$_SESSION['dbstate'] = !isset($_SESSION['new']) ? (new DataBase())->migrate() : 'set';


$router = new Router();

// Router::route('/', function() {
//     echo 'Hello';
// });

// Router::route('/table', function() {
//     $controller = new ProductsController();
//     $controller->showList();
// });

// Router::route('/table/filter', function($request, $params) {
//     echo $request->getBody();
// });

// Router::route('/table/(\d+)', function($id) {
//     $controller = new ProductsController();
//     $controller->show($id);
// });

// Router::execute($_SERVER['REQUEST_URI']);

$router->get('/', function($request) {
    $controller = new ProductsController();
    $controller->start();
});

$router->get('/table', function($request) {
    $controller = new ProductsController();
    $controller->showList();
  });

$router->post('/table/filter', function($request) {
    $body = $request->getBody();
    
    $controller = new ProductsController();
    $controller->showByProperty($body);
  });

$router->get('/table/{id}', function($request, $params) {
    $controller = new ProductsController();
    $controller->show($params[0]);
});

$router->resolve();