<?php

namespace Controllers;

use Model\DataBase;

class ProductsController 
{
    public function start() {
        $connection = new DataBase();
        $connection->getAllPoducts();
        require_once __DIR__ . '../../templates/add_product.phtml';
    }


    public function showList() {
        
        require_once __DIR__ . '../../templates/list.phtml';
    }

    public function show($id) {
        
    }

    public function create($data) {

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