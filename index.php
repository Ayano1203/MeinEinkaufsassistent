<?php

include("config/config.php");
global $pdo;


spl_autoload_register(function (string $class) {
    include 'classes/' . $class . '.php';
});
$manager = new InventoryManager($pdo);
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
        case 'create_shoppingList':
            $view = 'create_shoppingList';
            break;
        case 'create_inventory':
            $view = 'create_inventory';
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Daten sind angekommen, speichere sie
                $manager->addInventoryItem($_POST);
                $view = 'read_inventory';
            }
            break;
        case 'delete_inventory':
            $inventoryId = $_GET['inventory_id'] ?? null;
            if ($inventoryId != null) {
                $manager->deleteInventory((int)$inventoryId);
            }
            header('Location: index.php?action=read_inventory');
            exit;
        case 'update_quantity':
            $inventoryId = $_POST['inventory_id'] ?? null;
            if ($inventoryId != null) {
                $manager->updateInventoryItem((int)$inventoryId, (int)$_POST['new_quantity']);
            }
            header('Location: index.php?action=read_inventory');
            exit;
    }

include ("views/$view.php");