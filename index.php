<?php

include("config/config.php");
global $pdo;


spl_autoload_register(function (string $class) {
    include 'classes/' . $class . '.php';
});

//$pending = $list->isItemAlreadyPending('2');


$view='';
    $action= $_GET['action'] ?? 'dashboard';
    switch ($action) {
        case 'dashboard':
            $view = 'dashboard';
            break;
        case 'read_inventory':
            $view = 'read_inventory';
            break;
        case 'read_shoppingList':
            $view = 'read_shoppingList';
            break;
        case 'generate_shoppingList':
            $list->createShoppingListFromInventory();
            header('Location: index.php?action=read_shoppingList');
            exit;
//             break;
        case 'create_shoppingList':
            $view = 'create_shoppingList';
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
               $list->addShoppingListItem($_POST);
                header('Location: index.php?action=read_shoppingList');
                exit;
            }
            break;
        case 'add_to_inventory':
            $shoppingList_id = $_GET['shoppinglist_id'];
            $item = $list->getShoppingListById($shoppingList_id);
            $existingProduct = $productManager->getProductById($item['product_id']);

            if($existingProduct) {
                $item['product_id'] = $existingProduct['product_id'];
                $item['product_name'] = $existingProduct['name'];
                $item['category_id'] = $existingProduct['category_ID'];
                $item['minimum_stock'] = $existingProduct['minimum_stock'];
                $item['unit'] = $existingProduct['unit'];
            } else {
                $item['product_id'] = null;
                $item['minimum_stock'] = 1;
            }
            $view = 'add_to_inventory';
            break;
        //wenn Speichern gedrückt wird
        case 'create_inventory':
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                   $manager->addInventoryItem($_POST);
                   header('Location: index.php?action=read_inventory');
                   exit;
                }
            $view = 'create_inventory';
            break;
        case 'sync_to_inventory':
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Daten sind angekommen, speichere sie
                $list->syncToInventory($_POST);
                if(isset($_POST['shoppinglist_id'])) {
                    $list->deleteShoppingList($_POST['shoppinglist_id']);
                }
                header('Location: index.php?action=read_inventory');
                exit;
            }
            break;
//            $shoppingList_id = $_POST['shoppinglist_id'];
//            $bought_quantity = $_POST['bought_quantity'];
//            $expiry_date = $_POST['expiry_date'];
//            $category_id = $_POST['category_id'];
//            $storage_id = $_POST['storage_id'];
//            $list->syncToInventory($shoppingList_id, $bought_quantity, $expiry_date,$category_id, $storage_id);

        case 'update_shoppinglist_status':
            $selected_ids = $_POST['status'] ??[];
            //erhält $_POST Array, wenn es nichts gibt leerere Array
            foreach ($selected_ids as $id) {
                $list->updatedStatusToCompleted($id);
            }
            header('Location: index.php?action=read_shoppingList');
            exit;
        case 'delete_inventory':
            $inventoryId = $_GET['inventory_id'] ?? null;
            if ($inventoryId != null) {
                $manager->deleteInventory((int)$inventoryId);
            }
            header('Location: index.php?action=read_inventory');
            exit;
        case 'delete_shoppingList':
            $shoppingListId = $_GET['shoppinglist_id'] ?? null;
            if ($shoppingListId != null) {
                $list->deleteShoppingList((int)$shoppingListId);
            }
            header('Location: index.php?action=read_shoppingList');
            exit;
        case 'update_quantity':
            $inventoryId = $_POST['inventory_id'] ?? null;
            if ($inventoryId != null) {
                $manager->updateInventoryItem((int)$inventoryId, (int)$_POST['new_quantity']);
            }
            header('Location: index.php?action=read_inventory');
            exit;
        case 'add_to_shoppingList':
            $shoppingList_id = $_GET['inventory_id'];
            $item = $manager->getInventoryById($shoppingList_id);
            $existingProduct = $productManager->getProductById($item['product_id']);
            if($existingProduct) {
                $item['product_id'] = $existingProduct['product_id'];
                $item['product_name'] = $existingProduct['name'];
                $item['category_id'] = $existingProduct['category_ID'];
                $item['unit'] = $existingProduct['unit'];
            } else {
                $item['product_id'] = null;
            }
            $view = 'add_to_shoppingList';
            break;
        case 'sync_to_shoppingList':
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Daten sind angekommen, speichere sie
                $manager->syncToShoppingList($_POST, $list);
                if(isset($_POST['inventory_id'])) {
                    $list->deleteShoppingList($_POST['inventory_id']);
                }
                header('Location: index.php?action=read_shoppingList');
                exit;
            }
            break;
    }

include ("views/$view.php");