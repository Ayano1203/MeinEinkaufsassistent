<?php

include("config/config.php");
global $pdo;
spl_autoload_register(function (string $class) {
    include 'classes/' . $class . '.php';
});

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
            break;
    }
include ("views/$view.php");