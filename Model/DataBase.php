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

    private function tableExists($table) {
        try {
            $result = $this->connection->query("SELECT 1 FROM {$table} LIMIT 1");
        } catch (\PDOException $e) {
            return false;
        }
    
        return $result !== false;
    }

    public function migrate() {
        if ($this->tableExists('products')) {
            $this->connection->exec('DROP TABLE products');
            $this->connection->exec('DROP TABLE product_categories');
            $this->connection->exec('DROP TABLE product_brands');
        }
        

        $this->connection->exec('CREATE TABLE product_categories(
            id int PRIMARY KEY AUTO_INCREMENT,
            category varchar(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

        )');

        $this->connection->exec('CREATE TABLE product_brands(
            id int PRIMARY KEY AUTO_INCREMENT,
            brand varchar(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )');

        $this->connection->exec('CREATE TABLE products(
            id int PRIMARY KEY AUTO_INCREMENT,
            name varchar(255),
            category_id int,
            brand_id int,
            photo varchar(1000),
            price int,
            status bool,
            description text,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES product_categories (id),
            FOREIGN KEY (brand_id) REFERENCES product_brands (id)
        )');

        $this->connection->exec("INSERT INTO product_categories (category) 
            VALUES ('phone'), ('laptop'), ('headphones')");

        $this->connection->exec("INSERT INTO product_brands (brand) 
            VALUES ('apple'), ('samsung'), ('Huawei')");
    }
}