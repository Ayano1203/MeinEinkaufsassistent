<?php
include("config/config.php");
//$manager = new InventoryManager($pdo, $productManager);
//
//$list_manager = new ShoppingListManager($pdo,$manager);
//$listFromInventory = $list_manager->createShoppingListFromInventory();
$lists = $list->getAllShoppingListItems();
$difference = $list->generateShoppingListItems();

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Einkaufsliste</title>
    <style>
        body, header{
            text-align: center;
            justify-content: center;
            align-items: center;
        }
        .shoppingListTable {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
    </style>
</head>
<body>
<h1>Einkafsliste</h1>
    <a href="index.php?action=create_shoppingList"><form><input type="button" value="Neue List hinzufügen"></form></a><br>
<a href="index.php?action=generate_shoppingList"><form><input type="button" value="Bestand prüfen und Liste ergänzen"></form></a><br>
    <table class="shoppingListTable" border="1" cellpadding="4" cellspacing="0">
        <tr>
            <th>Status</th>
            <th>Produktname</th>
            <th>Bedarf</th>
            <th>Unit</th>
            <th>Kommentar</th>
            <th>Löschen</th>
        </tr>
        <?php foreach ($lists as $item): ?>
            <tr>
            <td><input type="checkbox" name="status"></td>
            <td><?php echo $item['product_name']?></td>
            <td><?php echo $item['quantity']?></td>
            <td><?php echo $item['unit']?></td>
            <td><?php echo $item['comments']?></td>
            <td><a href="index.php?action=delete_shoppingList&shoppinglist_id=<?php echo $item['shoppinglist_id']; ?>"><input type = "submit" value="löschen"></a></td>
        </tr>
        <?php endforeach; ?>
    </table><br>
<a href="index.php?action=dashboard"><input type="button" value="zurück"></a>
</body>
</html>
