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

    private function normalizeData($fetched) {
        $productsData = array_map(function($item) {
            $item['category'] = $this->getCategory($item['category_id']);
            $item['brand'] = $this->getBrand($item['brand_id']);
            return $item;
        }, $fetched);
        return $productsData;
    }

    public function getAllPoducts() {
        $sql = 'SELECT * FROM products';
        $fetched = $this->doQuery($sql, []);
        return $this->normalizeData($fetched);
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

    public function updateProduct($product, $id) {
        $sql = 'UPDATE products 
            SET name = :name, category_id = :category_id,
            brand_id = :brand_id, photo = :photo,
            price = :price, status = :status,
            description = :description
            WHERE id = :id';
            
        $this->doQuery($sql, [
            'name' => $product['name'],
            'category_id' => (int) $product['category'],
            'brand_id' => (int) $product['brand'],
            'photo' => $product['photo'],
            'price' => $product['price'],
            'status' => $product['status'],
            'description' => $product['description'],
            'id' => $id
        ], false);
    }

    public function deleteProduct($id) {
        $sql = 'DELETE FROM products WHERE id = :id';
        $this->doQuery($sql, ['id' => $id], false);
    }

    public function getFiltred($properties) {
        $params = [
            'name' => $properties['name'] ? $properties['name'] . '%' : '%',
            'price_to' => $properties['price_to'] ? $properties['price_to'] : 100000000,
            'price_from' => $properties['price_from'] ? $properties['price_from'] : 0
        ];
        
        $sql = 'SELECT * FROM products WHERE 
            name LIKE :name AND price < :price_to 
            AND price > :price_from ';

        if ($properties['category']) {
            $sql = $sql . 'AND category_id = :category_id ';
            $params['category_id'] = $properties['category'];
        }
        if ($properties['brand']) {
            $sql = $sql . 'AND brand_id = :brand_id';
            $params['brand_id'] = $properties['brand'];
        }
        $fetched = $this->doQuery($sql, $params);
        return $this->normalizeData($fetched);
    }

    public function getSortedBy($property) {
        switch($property) {
            case 'price':
                $sql = 'SELECT * FROM products ORDER BY price';
                break;
            case 'name':
                $sql = 'SELECT * FROM products ORDER BY name';
                break;
            case 'created_at':
                $sql = 'SELECT * FROM products ORDER BY created_at';
                break;
        }
        
        $fetched = $this->doQuery($sql, []);
        return $this->normalizeData($fetched);
    }

    public function switch($status, $id) {
        $sql = 'UPDATE products SET status = :status
            WHERE id = :id';
        $this->doQuery($sql, [
            'status' => $status, 
            'id' => $id], false);
    }
}