<?php
include "config/config.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>von Vorratsliste zu Einkaufsliste hinzufügen</title>
    <style>
        body, header{
            text-align: center;
            justify-content: center;
            align-items: center;
        }
        .add_to_inventory_list {
            width: 100%;
            justify-content: center;
        }
    </style>
</head>
<body>
<h2>Einkaufsliete hinzufügen</h2>
<form action="index.php?action=sync_to_shoppingList" method="post">
<!--    Array-->
<!--    (-->
<!--    [shoppinglist_id] => 1-->
<!--    [product_id] => 1-->
<!--    [quantity] => 1-->
<!--    [unit] => Packung-->
<!--    [added_date] => 2025-12-22-->
<!--    [status] => erledigt-->
<!--    [comments] =>-->
<!--    [difference] =>-->
<!--    [is_manual] => 1-->
<!--    )-->
<!--    hidden product_id von index - $list->getShoppingListById -->
    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
    <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_id']; ?>">
    <p>Product :
        <input type="text" name="product_name" value="<?php echo $item['product_name']; ?>">
    </p>
    <p>Gebrauchte Anzahl :
        <input type="number" name="quantity" min="0" max="100" value="0">
    <select name='unit'>
        <?php
        $unit = $item['unit'];
        echo "<option value='$unit' selected>" . $unit . "</option>";
        ?>
    </select><br><br>
    </p>
    <p>Kategorie :
            <?php
            $categoryList = $manager->getAllCategories();
            ?>

            <select name='category_id'>
    <?php
    foreach ($categoryList as $category) {
        // $category['category_ID'] & $item['category_id'], aber das Select Statement funktioniert nicht,
        //deswegen hier werden beide Möglichkeit geschrieben
        $catID = $category['category_ID'] ?? $category['category_id'];
        $itemID = $item['category_id'] ?? $item['category_ID'];

        // 判定ロジック
        $selected = ((string)$catID === (string)$itemID) ? "selected" : "";

        echo "<option value='$catID' $selected>" . $category['name'] . "</option>";
    }
    ?>
            </select><br>
    </p>
    <button type="submit">Speichern</button>

</form>
<a href="index.php?action=dashboard"><input type="button" value="zurück"></a>
</body>
</html>
