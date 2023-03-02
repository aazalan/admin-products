<?php

use Controllers\ProductsController;
use Http\Router;
use Http\Request;
use Model\DataBase;

require __DIR__ . '/vendor/autoload.php';

session_start();

$router = new Router();

$router->get('/', function($request) {
    $controller = new ProductsController();
    $controller->start();
});

$router->post('/', function($request) {
    $controller = new ProductsController();
    $controller->create($request->getBody());
});

$router->get('/table', function($request) {
    $controller = new ProductsController();
    $controller->showList();
  });

$router->get('/table/{id}', function($request, $params) {
    $controller = new ProductsController();
    $controller->show($params[0]);
});

$router->get('/table/{id}/edit', function($request, $params) {
  $controller = new ProductsController();
  $controller->showEditPage($params[0]);
});

$router->post('/table/filter', function($request) {
  $body = $request->getBody();
  
  $controller = new ProductsController();
  $controller->showByProperty($body);
});

$router->resolve();