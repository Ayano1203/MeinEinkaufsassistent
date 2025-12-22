<?php

class ShoppingListManager
{
    private $pdo;
    private int $id;
    private int $new_status;

    private InventoryManager $inventoryManager;               //InventoryManagerのオブジェクトである$inventoryManager という名前のプロパティを持つことを宣言
    private ProductManager $productManager;

    //ShoppingListManager の新しいオブジェクトを作成するときに、PDO接続だけでなく、既に作成されている InventoryManager のインスタンスも一緒に渡す
    public function __construct(PDO $connection, InventoryManager $inventoryManager, productManager $productManager)
    {
        $this->pdo = $connection;
        $this->inventoryManager = $inventoryManager;           //コンストラクタで受け取った InventoryManager を、クラスの所有物（$this->inventoryManager）として保存
        //これにより、ShoppingListManager 内の他のどのメソッドでも、$this->inventoryManager を使って InventoryManager の機能（例えば getProductsBelowMinimumStock()）を呼び出せる
        $this->productManager = $productManager;

    }

    public function getProductsBelowMinimumStock(): array
    {
        $sql = "SELECT P.product_id, P.name AS product_name , P.minimum_stock, P.unit, sum(I.quantity) AS total_stock
                FROM inventory I
                JOIN product P ON P.product_id = I.product_id
                GROUP BY product_id
                HAVING P.minimum_stock > sum(I.quantity)
               ;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addShoppingListItem($data): void
    {
//        Array
//        (
//            [product_name] => Äpfel
//            [category_id] => 3
//    [minimum_stock] => 1
//    [unit] => Liter
//    [button] => Speichern
//)
////
        $cId = $data['category_id'];                      //nimmt category name aus $data
        // checkt es ob product_name key gibt und nicht null ist, wenn true ist trimmt leerzeichen und
        // setzt den als pName rein , wenn false ist pName = ''
        $pName = isset($data['product_name']) ? trim($data['product_name']) : '';
        $pId = null;
        //wenn da kein Name gibt, macht es nichts
        if ($pName === '') {
            return;
        }

        //wenn der Benutzer Produktname eingibt
        if ($pName !== null) {
            $pName = $data['product_name'];
            //checkt mit pName ob die ID schon existiert
            $pId = $this->productManager->getProductIdByName($pName);

        }
        if ($pId == null && $pName !== null) {
            $pName = $data['product_name'];
            //wenn noch nicht existiert, erstellt neues Produkt mit category_id
            $pId = $this->productManager->createProduct($data, $cId);
        }
        //wenn quantity eingegeben wurde, füge ich es als mustHaveQuantity in die Einkaufsliste
        $currentStock = $this->getCurrentStockByProductId($pId);
        $minimum_stock = $data['minimum_stock'] ?? 0;
        //Wenn min_stock größer als aktuellen Bestand ist, fügt die Differenz in Shoppinglist ein
        if ($currentStock < $minimum_stock) {
            $mustHaveQuantity = $minimum_stock - $currentStock;
            // Wenn nicht, addiert 1 (es soll mindestens 1 sein)
        } else {
            $mustHaveQuantity = 1;
        }

        if ($pId != null) {                                             //prüft, ob $pId gültig ist
            //Wenn das Produkt schon existiert und Status noch offen ist, die quantity soll übergeschrieben werden
            //Wenn status noch offen ist, List updaten
            if ($this->isItemAlreadyPending($pId) !== null) {
                $sql = "UPDATE shoppinglist SET quantity = :mustHaveQuantity, is_manual = 1 WHERE product_id = :product_id AND status = 'offen'";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':product_id', $pId);
                $stmt->bindParam(':mustHaveQuantity', $mustHaveQuantity);
                $stmt->execute();
                //Wenn da noch keine List mit diesem Produkt gibt
            } else {
                //$minimumStock = $this->getMinimumStockByProductId($pId);
                //mit lastInsertId inventory Tabelle hinzufügen
                $today = date("Y-m-d");
                $difference = $data['minimum_stock'] - $currentStock;
                $comment = $this->getCommentByStock($currentStock, $minimum_stock, true);
                $sql = "INSERT INTO shoppinglist(product_id,unit, quantity, added_date, status, comments, difference, is_manual) 
                        VALUES(:product_id,:unit,:quantity, :added_date,:status, :comments,:difference,:is_manual);";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":product_id", $pId);
                $stmt->bindValue(":quantity", $mustHaveQuantity ?? 0);
                $stmt->bindValue(":added_date", $today);
                $stmt->bindValue(":unit", $data['unit'] ?? null);
                $stmt->bindValue(":status", $data['status'] ?? 'offen');
                $stmt->bindValue(":comments", $comment ?? null);
                $stmt->bindValue(":difference", $difference ?? 0);
                //manual = true(1) und  diese Spalte enthält Integer-Werte
                $stmt->bindValue(":is_manual", true, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }

    public function getCommentByStock(int $currentStock, int $minimum_stock, bool $is_manual): string
    {
        if ($minimum_stock > $currentStock) {
            $comment = 'weniger';
        } elseif ($is_manual === true) {
            $comment = 'ausreichend';
        } else {
            $comment = '';
        }
        return $comment;
    }

    public function getAllShoppingListItems(): array
    {
        $sql = "SELECT
               S.shoppinglist_id,
               S.status,
               P.product_id,
               P.name AS product_name,
               S.quantity,
               P.unit,
               S.comments
               FROM shoppinglist S
               LEFT JOIN product P ON P.product_id = S.product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteShoppingList(int $shoppinglist_id): void
    {
        print_r($shoppinglist_id);
        $sql = "DELETE FROM shoppinglist WHERE shoppinglist_id = :shoppinglist_id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":shoppinglist_id", $shoppinglist_id);
        $stmt->execute();
    }


    public function generateShoppingListItems(): array
    {
        $shoppingListItems = [];
        $lowStockItems = $this->getProductsBelowMinimumStock();
        foreach ($lowStockItems as $item) {
            $minimum_stock = $item['minimum_stock'] ?? 0;
            $current_stock = $this->getCurrentStockByProductId($item['product_id']);
            $difference = $minimum_stock - $item['total_stock'];
            if ($difference > 0) {
                $comment = $this->getCommentByStock($current_stock, $minimum_stock, false);
                $shoppingListItems[] = [
                    'product_id' => $item['product_id'],
                    'product_name ' => $item['product_name'],
                    'quantity' => $item['total_stock'],
                    'unit' => $item['unit'],
                    'difference' => $difference,
                    'comment' => $comment
                ];
            }
        }
        return $shoppingListItems;
    }

    // wenn der Bestand des Produkts in Inventory weniger als Mindestbestand ist,
    // fügt es automatisch in die Shoppingliste
    public function createShoppingListFromInventory(): void
    {
        $data = $this->generateShoppingListItems();
        $today = date("Y-m-d");
        $status = 'offen';
        foreach ($data as $item) {
            $pending = $this->isItemAlreadyPending($item['product_id']);
            //wenn das Product in der Liste schon existiert und manuel ist, überspring diese Funktion
            //weil ich eingegebene quantity als Fehlbestand zeigen möchte
            if ($pending === 1) {
                continue;
                //wenn es noch nicht existiert, addiert die Datei in der Shoppinglist mit is_manuel= 0(automatisch)
            } elseif ($pending === null) {
                $sql = "INSERT INTO shoppinglist (product_id,unit, quantity, added_date, status, comments,difference, is_manual) 
                        VALUES(:product_id,:unit,:quantity, :added_date,:status, :comment, :difference, :is_manual);";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":product_id", $item['product_id']);
                $stmt->bindValue(":unit", $item['unit']);
                $stmt->bindValue(":quantity", $item['quantity']);
                $stmt->bindValue(":added_date", $today);
                $stmt->bindValue(":status", $status);
                $stmt->bindValue(":comment", $item['comment']);
                $stmt->bindValue(":difference", $item['difference']);
                $stmt->bindValue(":is_manual", 0, PDO::PARAM_INT);
                $stmt->execute();
                //wenn das Product schon existiert und es war automatisch eingegeben worden, überschreibt die quantity und
                // is_manuel bleibt 0 *automatisch
            } elseif ($pending === 0) {
                $sql = "UPDATE shoppinglist SET quantity = :quantity, difference = :difference, is_manual = 0 
                        WHERE product_id = :product_id AND status = 'offen';";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":product_id", $item['product_id']);
                $stmt->bindValue(":quantity", $item['quantity']);
                $stmt->bindValue(":difference", $item['difference']);
                $stmt->execute();
            }
        }
    }

    //es checkt ob es schon in der Shoppingliste existiert und manuel / automatisch ist.
    //noch nicht existiert = null, manuel =1, automatisch =0
    public function isItemAlreadyPending(int $product_id)
    {
        //$sql = "SELECT is_manual FROM shoppinglist WHERE product_id = :product_id AND status = 'offen';";
        $sql = "SELECT * FROM shoppinglist WHERE product_id = :product_id AND status = 'offen';";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":product_id", $product_id);
        $stmt->execute();
        $result = $stmt->fetch(pdo::FETCH_ASSOC);

        //wenn es nicht in der Liste ist, return null
        if (!$result) {
            return null;
        }
        // wenn es in der Liste existiert, return is_manual 1 oder 0
        return (int)$result['is_manual'];
    }

    public function getCurrentStockByProductId(int $product_id): int
    {
        $sql = "SELECT P.name AS product_name , P.unit, sum(I.quantity) AS total_stock
                FROM inventory I
                JOIN product P ON P.product_id = I.product_id
                WHERE I.product_id = :product_id
                GROUP BY P.product_id
               ;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":product_id", $product_id);
        $stmt->execute();
        $totalStock = $stmt->fetchColumn();
        return $totalStock ? (int)$totalStock : 0;
    }


}




