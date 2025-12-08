<?php

class InventoryManager
{
// 1. Klasse definieren
    private int $inventory_id;
    private int $product_id;
    private int $quantity;
    private string $unit;
    private int $expiry_date;
    private int $storage_id;
// 2. Variable (Eigenschaft) deklarieren, um die PDO-Verbindung zu speichern
// (z.B. private $pdo;)
    private $pdo;


// 3. Den Konstruktor definieren, um die PDO-Verbindung entgegenzunehmen

    public function __construct(PDO $connection)
    {
        $this->pdo = $connection;
    }

    public function getAllInventory(): array
    {
        $sql = "SELECT I.inventory_id, P.name, I.quantity, P.minimum_stock, P.unit, I.expiry_date, P.category_id
            FROM inventory I
            INNER JOIN product P on I.product_id = P.product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function checkItemWarning(array $items): string
    {
            if (strtotime('+7days') >= strtotime($items['expiry_date'])) {
                $warning = 'bald ablaufen';
            } elseif ($items["quantity"] <= $items["minimum_stock"]) {
                $warning = 'niedriger Bestand ';
            } else {
                $warning = '⭕';
            }
        return $warning;

    }

public function deleteInventory(int $inventory_id): void
{
    $sql = "DELETE FROM inventory WHERE inventory_id = :inventory_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(":inventory_id", $inventory_id);
    $stmt->execute();
}

}