<?php
$list_manager = new ShoppingListManager($pdo);
$manager = new InventoryManager($pdo);
$lists = $list_manager->getProductsBelowMinimumStock();
echo '<pre>';
print_r($lists);
echo '</pre>';
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
        }
    </style>
</head>
<body>
<h1>Einkafsliste</h1>
    <form><input type="button" value="Neue List hinzufügen"></form>
    <table class="shoppingListTable">
        <tr>
            <th>Status</th>
            <th>Produktname</th>
            <th>Bestand</th>
            <th>Kommentar</th>
            <th>Löschen</th>
        </tr>
        <?php foreach ($lists as $item): ?>
        <tr>
            <td><input type="checkbox" name="status"></td>
            <td><?php echo $item['product_name']?></td>
            <td><?php echo $item['total_stock']?></td>
            <td><?php  ?></td>
            <td><input type = "submit" value="löschen"></td>
        </tr>
        <?php endforeach; ?>
    </table>
<a href="index.php?action=dashboard"><input type="button" value="zurück"></a>
</body>
</html>
