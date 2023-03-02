<?php

namespace Controllers;

use Model\DataBase;
use Http\Router;

class ProductsController 
{
    private $connection;
    private $router;
    public function __construct() {
        $this->connection = new DataBase();
        
    }

    public function start() {
        require_once __DIR__ . '../../templates/add_product.phtml';
    }


    public function showList() {
        $products = $this->connection->getAllPoducts();
        require_once __DIR__ . '../../templates/list.phtml';
    }

    public function show($id) {
        if (!$this->connection->isInBase($id)) {
            echo <<<HTML
            <h1>Нет товара с таким ID</h1>
            HTML;
            return "404";
        }
        $product = $this->connection->getProduct($id);
        if ($product['status'] !== 1) {
            echo <<<HTML
            <h1>Товар выключен</h1>
            HTML;
            return "404";
        }
        require_once __DIR__ . '../../templates/product_card.phtml';
    }

    public function create($data) {
        $emptyFields = array_filter($data, function($item) {
            return strlen($item) == 0;
        });

        if (!empty($emptyFields)) {
            $flash = 'Заполните все поля';
            require_once __DIR__ . '../../templates/add_product.phtml';
            return;
        }
        $this->connection->addProduct($data);
        echo <<<HTML
            <h3>Товар был добавлен</h3>
            <a href="/table">К таблице товаров</a>
            HTML;
            return "404";
    }

    public function showEditPage() {

    }


    public function edit($id) {

    }

    public function delete($id) {

    }

    public function showByProperty($properties) {

    }

    // public function showByStatus($status) {

    // }

    // public function showByName($name) {

    // }

    // public function showByBrand($brand) {

    // }

    // public function showByCategory($category) {

    // }

    // public function showByPrice($min, $max) {

    // }
}