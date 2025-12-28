<?php

class ProductManager
{
    private $pdo;
    public function __construct(PDO $connection)
    {
        $this->pdo = $connection;
    }

    public function getProductIdByName(string $name): ?int
    {
        $sql = "SELECT product_id FROM product 
                  WHERE LOWER(name) = LOWER(:name) 
                  AND name IS NOT NULL 
                  AND name != '';";                                    //ignoriert Null und empty
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result !== false ? (int)$result : null;
    }


    //prüft, ob der product_name schon vorhanden ist.
    public function uniqueProduct(array $data): bool
    {
        //false = nicht unique
        if ($this->getProductIdByName($data['product_name']) !== null) {
            return false;
        }
        return true;
    }

//product Tabelle hinzufügen
    public function createProduct(array $data, int $cId): ?int
    {
        //wenn da schon gleiches Product gibt:
        if ($this->uniqueProduct($data) === false) {
            return null;                                                           //wenn es nicht unique ist, bricht die CreateProduct ab
        }
        //die Variable min_stock definieren
        $min_stock = !empty($data['minimum_stock']) ? $data['minimum_stock'] : 1;
        $clean_name = trim($data['product_name']);
        $sql = "INSERT INTO product(name, category_ID, minimum_stock, unit) VALUES (:name, :category_ID, :minimum_stock, :unit);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $clean_name);
        $stmt->bindValue(':category_ID', $cId);                         //$cId kann getCategoryNameId und createCategory abrufen
        $stmt->bindValue(':minimum_stock', $min_stock);
        $stmt->bindValue(':unit', $data['unit']);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function getAllProducts(): array
    {
        $sql = 'SELECT * FROM product';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMinimumStockByProductId(int $pId): ?int
    {
        $sql = "SELECT minimum_stock FROM product WHERE product_id = :product_id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $pId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getProductById(string $id):array
    {
//        Array
//        (
//            [0] => Array    ---> 1件だけの結果でもfetch AllだとArrayのなかのArrayという形で出てきてしまう
//            (
//                [product_id] => 1
//            [name] => Milch
//    [category_ID] => 1
//            [minimum_stock] => 1
//            [unit] => Packung
//        )
//
//)

        $sql ="SELECT * FROM product WHERE product_id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);   // -> nur fetch!
    }

    public function getProductNameById($id):string
    {
        $sql = "SELECT name FROM product WHERE product_id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn();

    }
}