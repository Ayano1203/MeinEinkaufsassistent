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
        $sql = "SELECT P.product_id, P.name AS product_name , P.minimum_stock, P.unit, sum(I.quantity) AS total_stock
                FROM inventory I
                JOIN product P ON P.product_id = I.product_id
                GROUP BY product_id
                HAVING P.minimum_stock <  sum(I.quantity)
               ;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}