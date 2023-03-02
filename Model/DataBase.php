<?php

namespace Model;
use PDO;

class DataBase
{
    private $connection;

    public function __construct()
    {
        $host = '127.0.0.1';
        $port = 3306;
        $dataBase = 'admin';
        $user = 'root';
        $password = 'topi1409';
        $dsn = "mysql:host={$host};port={$port};dbname={$dataBase};";
        $this->connection = new PDO($dsn, $user, $password);
    }

    private function doQuery($sql, $data, $isNeedToFetch = true)
    {
        $query = $this->connection->prepare($sql);
        $query->execute($data);
        if ($isNeedToFetch) {
            return $query->fetchAll();
        }
    }

    private function getCategory($id) {
        return $this->doQuery(
            'SELECT (category) FROM product_categories; WHERE id = :id', 
            ['id' => $id])[0]['category'];
    }

    private function getBrand($id) {
        return $this->doQuery(
            'SELECT (brand) FROM product_brands WHERE id = :id', 
            ['id' => $id])[0]['brand'];
    }

    public function getAllPoducts() {
        $sql = 'SELECT * FROM products';
        $fetched = $this->doQuery($sql, []);
        $productsData = array_map(function($item) {
            $item['category'] = $this->getCategory($item['category_id']);
            $item['brand'] = $this->getBrand($item['brand_id']);
            return $item;
        }, $fetched);
        return $productsData;
    }

    public function isInBase($id) {
        $sql = 'SELECT COUNT(name)
            FROM products
            WHERE id = :id';
        [$count] = $this->doQuery($sql, ['id' => $id]);
        return $count['COUNT(name)'] > 0;
    }

    public function getProduct($id) {
        $sql = 'SELECT * FROM products WHERE id = :id';
        [$productData] = $this->doQuery($sql, ['id' => $id]);
        $productData['category'] = $this->getCategory($productData['category_id']);
        $productData['brand'] = $this->getBrand($productData['brand_id']);
        return $productData;
    }

    public function addProduct($product) {
        $sql = 'INSERT INTO products (name, category_id, brand_id, photo, price, status, description) 
            VALUES (:name, :category_id, :brand_id, :photo, :price, :status, :description)';
        $this->doQuery($sql, [
            'name' => $product['name'],
            'category_id' => $product['category'],
            'brand_id' => $product['brand'],
            'photo' => $product['photo'],
            'price' => $product['price'],
            'status' => $product['status'],
            'description' => $product['description']
        ], false);
    }
}