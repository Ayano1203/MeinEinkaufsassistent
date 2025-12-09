<?php
$manager = new InventoryManager($pdo);


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>neue Einkaufslist</title>
    <style>
        body, header{
            text-align: center;
            justify-content: center;
            align-items: center;
        }
        .inventoryList {
            width: 80%;
            justify-content: center;
        }
    </style>
</head>
<body>
<form method="POST" action="">
    <fieldset>
        <legend><h2>Einkaufsliste hinzufügen</h2></legend>
        <label>Produktname :</label>
        <select name="product_id" id="product_id">
            <option value="" selected disabled>---Bitte Produkt wählen---</option>
            <?php
            $productsList = $manager->getAllProducts();
            foreach ($productsList as $product) {
                echo "<option value='".$product['product_id']."'>".$product['name']."</option>";
            }
            ?>
        </select><br>
        <label> Oder neues Produkt : </label>
        <input type='text' name='new_product_name' placeholder='Produktname'><br><br>
        <label>Kategorie :</label>
        <select name='category_id'>
            <?php
            $categoryList = $manager->getAllCategories();
            foreach ($categoryList as $category) {
                echo "<option value='".$category['category_id']."'>".$category['name']."</option>";
            }
            ?>
        </select><br><br>
        <label>Menge :</label>
        <input type='number' name='quantity' min="1" max = "1000" placeholder='1'><br><br>
        <label>Unit :</label>
        <select name='unit'>
            <option value="Stück">Stück</option>
            <option value="Liter">Liter</option>
            <option value="Packung">Packung</option>
            <option value="Gramms">Gramms</option>
        </select><br><br>
        <label>Bestand :</label>
        <select name='bestandStatus'>
            <option value="nicht vorrätig">nicht vorrätig</option>
            <option value="weniger">weniger</option>
            <option value="ausreichend">ausreichend</option>
        </select><br><br>
        <input type="submit" name="submit" value="Speichern">
    </fieldset>
</form><br><br>
<a href="index.php?action=dashboard"><input type="button" value="zurück"></a>
</body>
</html>
