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
<a href="index.php?action=create_shoppingList"><input type="button" value="Neue List hinzufügen"></a><br>
<a href="index.php?action=generate_shoppingList"><input type="button" value="Bestand prüfen und Liste ergänzen"></a><br>
<form method="post" action="index.php?action=update_shoppinglist_status">
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
                <td>
                <?php if ($item['status'] === 'erledigt'): ?>
                    <a href="index.php?action=add_to_inventory&shoppinglist_id=<?php echo $item['shoppinglist_id']; ?>">
                        <input type = "button" value ="Zu Vorrat">
                    </a>
                <?php else: ?>
<!--              mit Value(id) checkt es welche Artikel von der List gecheckt wurde-->
                    <input type="checkbox" name="status[]" value="<?php echo $item['shoppinglist_id']; ?>">
                <?php endif; ?>
                </td>
                <td>
                <?php if($item['status'] === 'erledigt'): ?>
                    <s><?php echo $item['product_name']?></s>
                <?php else: ?>
                    <?php echo $item['product_name']?>
                <?php endif; ?>
                </td>
                    <td><?php echo $item['quantity']?></td>
                    <td><?php echo $item['unit']?></td>
                    <td><?php echo $item['comments']?></td>
            <td><a href="index.php?action=delete_shoppingList&shoppinglist_id=<?php echo $item['shoppinglist_id']; ?>">
            <input type = "submit" value="löschen"></a></td>
        </tr>
        <?php endforeach; ?>
    </table><br>
    <input type="submit" value="Status Aktualisieren">
</form><br>
<a href="index.php?action=dashboard"><input type="button" value="zurück"></a>
</body>
</html>
