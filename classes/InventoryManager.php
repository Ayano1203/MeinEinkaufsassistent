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
        $pId=$items['product_id'];
        $totalquantity = $this->getTotalProductQuantity($pId);
            if (strtotime('+7days') >= strtotime($items['expiry_date'])) {
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
    public function addInventoryItem($data):void
    {
        $cName = $data['category'];                                                //nimmt category name aus $data
        $cId = $this->getCategoryIdByName($cName);
        $pId = $data['product_id'] ?? null;                                         //prüft, ob das Produkt von option ausgewählt
        if ($pId == null) {
            $pName = $data['new_product_name'] ?? null;
            if (!empty($pName) && $cId !== null) {                                 //Wenn Product name eingegeben wurde und product name nicht empty ist//
                $pId = $this->createProduct($data, $cId);
                }
            }
        echo '<pre>';
        print_r($pId);
        echo '</pre>';
        if ($pId != null) {                                                        //prüft, ob $pId gültig ist
            //$minimumStock = $this->getMinimumStockByProductId($pId);
            //mit lastInsertId inventory Tabelle hinzufügen
            $sql = "INSERT INTO inventory(product_id, quantity, expiry_date) VALUES(:product_id, :quantity, :expiry_date);";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":product_id", $pId);
            $stmt->bindValue(":quantity", $data['quantity'] ?? 0);
            $stmt->bindValue(":expiry_date", $data['expiry_date'] ?? null);
            $stmt->execute();
        }
    }

    public function getCategoryIdByName(string $name) : ?int
        {
            $sql = "SELECT category_id FROM category WHERE LOWER(name) = LOWER(:name)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $stmt->fetchColumn();
        }

    public function createCategory(string $name) : ?int
        {
            $sql = "INSERT INTO category(name) VALUES(:name);";
            $stmt = $this ->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        }

    public function getProductIdByName(string $name): ?int
        {
            $sql="SELECT product_id FROM product 
                  WHERE LOWER(name) = LOWER(:name) 
                  AND name IS NOT NULL 
                  AND name != '';";                                    //ignoriert Null und empty
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result !== false ? (int) $result : null;
        }


        //prüft, ob der product_name schon vorhanden ist.
    public function uniqueProduct(array $data) :bool
    {
        //false = nicht unique
        if ($this->getProductIdByName($data['new_product_name']) !== null) {
            echo 'Dieses Produkt existiert bereits';
            return false;
        }
        return true;
    }



//product Tabelle hinzufügen
    public function createProduct(array $data ,int $cId) : ?int
    {
        //wenn da schon gleiches Product gibt:
        if ($this->uniqueProduct($data) === false) {
            return null;                                                           //wenn es nicht unique ist, bricht die CreateProduct ab
        }
        //die Variable min_stock definieren
            $min_stock = !empty($data['minimum_stock']) ? $data['minimum_stock'] : 1;
            $clean_name = trim($data['new_product_name']);
            $sql = "INSERT INTO product(name, category_ID, minimum_stock, unit) VALUES (:name, :category_ID, :minimum_stock, :unit);";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $clean_name);
            $stmt->bindValue(':category_ID', $cId);                         //$cId kann getCategoryNameId und createCategory abrufen
            $stmt->bindValue(':minimum_stock', $min_stock);
            $stmt->bindValue(':unit', $data['unit']);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        }


    public function getMinimumStockByProductId(int $pId): ?int
    {
        $sql = "SELECT minimum_stock FROM product WHERE product_id = :product_id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $pId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getAllProducts(): array
    {
    $sql = 'SELECT * FROM product';
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function updateInventoryItem(int $inventory_id, int $new_quantity) :void
 {
     if ($new_quantity <= 0) {
         $this->deleteInventory($inventory_id);
     }else {
         $sql = "UPDATE inventory SET quantity =:new_quantity WHERE inventory_id =:inventory_id;";
         $stmt = $this->pdo->prepare($sql);
         $stmt->bindParam(':inventory_id', $inventory_id);
         $stmt->bindParam(':new_quantity', $new_quantity);
         $stmt->execute();
     }
 }

 public function getTotalProductQuantity(int $product_id) :?int
 {
     $sql ="SELECT SUM(quantity) FROM inventory WHERE product_id = :product_id;";
     $stmt = $this->pdo->prepare($sql);
     $stmt->bindParam(':product_id', $product_id);
     $stmt->execute();
     $result = $stmt->fetchColumn();
     return $result;
 }

}