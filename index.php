<?php
include("config/config.php");
global $pdo;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
spl_autoload_register(function (string $class) {
    include 'classes/' . $class . '.php';
});
$manager = new InventoryManager($pdo);

echo '<pre>';
print_r($_POST);


echo '</pre>';


$action= $_GET['action'] ?? 'dashboard';
//$entity = $_REQUEST['entity'] ?? '';

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
    }
include ("views/$view.php");