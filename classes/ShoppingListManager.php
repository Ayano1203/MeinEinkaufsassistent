<?php

class ShoppingListManager
{
    private $pdo;

    public function __construct(PDO $connection)
    {
        $this->pdo = $connection;
    }
    public function getProductsBelowMinimumStock() :array
    {
        $sql = "SELECT P.product_id, P.name AS product_name , P.minimum_stock, P.unit
                FROM inventory I
                JOIN product P ON P.product_id = I.product_id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        return $stmt->fetchAll();
    }
    ////                GROUP BY P.product_id
    //HAVING total_stock < P.minimum_stock"; SUM(I.quantity) AS total_stock
}