<?php
include("config/config.php");

$allitems = $manager->getAllInventory();


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vorratsliste</title>
    <style>
        body, header{
            text-align: center;
            justify-content: center;
            align-items: center;
        }
        .inventoryList {
            width: 100%;
            justify-content: center;
        }
    </style>
</head>
<body>
    <h1>Vorratsliste</h1>
    <a href="index.php?action=create_inventory"><input type="button"  class= "button" value="Neu hinzufügen"></a>
    <br><br>
    <table class="inventoryList" border="1" cellpadding="4" cellspacing="0" >
        <tr>
            <th>Warnung</th>
            <th>Produktname</th>
            <th>Aktueller Bestand</th>
            <th>Minimaler Bestand</th>
            <th>Unit</th>
            <th>Ablaufdatum</th>
            <th>Category</th>
            <th>Zur Einkaufsliste</th>
            <th>Löschen</th>
        </tr>
        <?php foreach ($allitems as $inventory):  ?>
        <tr>
            <td><?php echo $warning = $manager -> checkItemWarning($inventory); ?></td>
            <td><?php echo $inventory['product_name']; ?></td>
            <td><form action ="index.php?action=update_quantity" method="POST">
                    <input type="hidden" name ="inventory_id" value="<?php echo $inventory['inventory_id']; ?>">
                    <input type="number" name="new_quantity" value="<?php echo $inventory['quantity']; ?>">
                    <input type="submit" value="Aktualisieren">
                </form>
            </td>
            <td><?php echo $inventory['minimum_stock']; ?></td>
            <td><?php echo $inventory['unit']; ?></td>
            <td><?php echo $inventory['expiry_date']; ?></td>
            <td><?php echo $inventory['category_name']; ?></td>
            <td><a><button>addieren</button></a></td>
            <td><a href="index.php?action=delete_inventory&inventory_id=<?php echo $inventory['inventory_id']; ?>">Löschen</a></td>
        <tr>
        <?php endforeach; ?>
    </table><br>
    <a href="index.php?action=dashboard"><input type="button" value="zurück"></a>
</body>
</html>
