<?php


function migrate() {
    $host = '127.0.0.1';
    $port = 3306;
    $dataBase = 'admin';
    $user = 'root';
    $password = 'topi1409';
    $dsn = "mysql:host={$host};port={$port};dbname={$dataBase};";
    $connection = new PDO($dsn, $user, $password);

    if (tableExists('products', $connection)) {
        $connection->exec('DROP TABLE products');
        $connection->exec('DROP TABLE product_categories');
        $connection->exec('DROP TABLE product_brands');
    }

    $connection->exec('CREATE TABLE product_categories(
        id int PRIMARY KEY AUTO_INCREMENT,
        category varchar(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

    )');

    $connection->exec('CREATE TABLE product_brands(
        id int PRIMARY KEY AUTO_INCREMENT,
        brand varchar(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )');

    $connection->exec('CREATE TABLE products(
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

    $connection->exec("INSERT INTO product_categories (category) 
    VALUES ('phone'), ('laptop'), ('headphones')");

    $connection->exec("INSERT INTO product_brands (brand) 
    VALUES ('apple'), ('samsung'), ('Huawei')");

    require('Model/products_table.php');

    foreach ($products as $product) {
        $sql = 'INSERT INTO products (name, category_id, brand_id, photo, price, status, description) 
            VALUES (:name, :category_id, :brand_id, :photo, :price, :status, :description)';
        $query = $connection->prepare($sql);
        $query->execute([
            'name' => $product['name'],
            'category_id' => $product['category_id'],
            'brand_id' => $product['brand_id'],
            'photo' => $product['photo'],
            'price' => $product['price'],
            'status' => $product['status'],
            'description' => $product['description']
    ]);
    }
}

function tableExists($table, $connection) {
    try {
        $result = $connection->query("SELECT 1 FROM {$table} LIMIT 1");
    } catch (\PDOException $e) {
        return false;
    }

    return $result !== false;
}

migrate();