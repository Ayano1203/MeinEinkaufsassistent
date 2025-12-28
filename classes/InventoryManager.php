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
    private ProductManager $productManager;



// 3. Den Konstruktor definieren, um die PDO-Verbindung entgegenzunehmen

    public function __construct(PDO $connection, ProductManager $productManager)
    {
        $this->pdo = $connection;
        $this->productManager = $productManager;
    }

    public function getAllInventory(): array
    {
        $sql = "SELECT 
                P.product_id,
                I.inventory_id,
                P.name AS product_name, 
                I.quantity, 
                P.minimum_stock, 
                P.unit, 
                I.expiry_date, 
                C.name AS category_name
            FROM inventory I
            LEFT JOIN product P on I.product_id = P.product_id
            LEFT JOIN category C on P.category_ID = C.category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function checkItemWarning(array $items): string
    {
        $pId = $items['product_id'];
        $totalquantity = $this->getTotalProductQuantity($pId);
        if(strtotime('now') > strtotime($items['expiry_date'])) {
            $warning = 'abgelaufen';
        } elseif(strtotime('+7days') >= strtotime($items['expiry_date'])) {
            $warning = 'bald ablaufen';
        } elseif ($totalquantity <= $items["minimum_stock"]) {
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

    public function addInventoryItem($data): void
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
//        Array
//        (
//            [product_name] => Sojamilch
//            [category_id] => 1
//    [quantity] => 2
//    [unit] => Packung
//    [expiry_date] => 2026-01-10
//    [minimum_stock] => 1
//    [storage] => 1
//)
        $cId = $data['category_id'];                                                //nimmt category name aus $data
        $pId = $data['product_id'] ?? null;                                         //prüft, ob das Produkt von option ausgewählt
        $sId = $data['storage_id'];
        if ($pId == null) {
            $pName = $data['product_name'] ?? null;
            if (!empty($pName) && $cId !== null) {                                 //Wenn Product name eingegeben wurde und product name nicht empty ist//
                $pId = $this->productManager->createProduct($data, $cId);
                if ($pId == null) {
                    $pId = $this->productManager->getProductIdByName($data['product_name']);
                }
            }
        }
            echo "DEBUG: pIdは " . ($pId ?? 'null') . " です<br>";
        if ($pId != null) {                                                        //prüft, ob $pId gültig ist
            $minimumStock = $this->productManager->getMinimumStockByProductId($pId);
            //mit lastInsertId inventory Tabelle hinzufügen
            $sql = "INSERT INTO inventory(product_id,unit, storage_id, quantity, expiry_date) 
        VALUES(:product_id,:unit, :storage_id, :quantity, :expiry_date);";
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":product_id", $pId);
            $stmt->bindValue(":quantity", $data['quantity'] ?? 0);
            $stmt->bindValue(":expiry_date", $data['expiry_date'] ?? null);
            $stmt->bindValue(":storage_id", $sId ?? null);
            $stmt->bindValue(":unit", $data['unit'] ?? null);
            $stmt->execute();
        }
        }


    public function getCategoryIdByName(string $name): ?int
    {
        $sql = "SELECT category_id FROM category WHERE LOWER(name) = LOWER(:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function createCategory(string $name): ?int
    {
        $sql = "INSERT INTO category(name) VALUES(:name);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function updateInventoryItem(int $inventory_id, int $new_quantity): void
    {
        if ($new_quantity <= 0) {
            $this->deleteInventory($inventory_id);
        } else {
            $sql = "UPDATE inventory SET quantity =:new_quantity WHERE inventory_id =:inventory_id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':inventory_id', $inventory_id);
            $stmt->bindParam(':new_quantity', $new_quantity);
            $stmt->execute();
        }
    }

    public function getTotalProductQuantity(int $product_id): ?int
    {
        $sql = "SELECT SUM(quantity) FROM inventory WHERE product_id = :product_id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }

    public function getAllCategories(): array
    {
        $sql = 'SELECT * FROM category';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStorageIdByName(string $name): ?int
    {
        $sql = "SELECT storage_id FROM storage WHERE LOWER(name) = LOWER(:name);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }
    public function getAllStorages(): array
    {
        $sql = 'SELECT * FROM storage';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}