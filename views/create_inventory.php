<?php
include("config/config.php");

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
             <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                         <meta http-equiv="X-UA-Compatible" content="ie=edge">
             <title>neue Artikel</title>
    <style>
        .container {
            width: 100%;
            text-align: center;
        }

        .button {
            width: 15em;
            height: 3em;
        }
    </style>
</head>
<body>
<div class="container">
<h2>Neue Artikel zur Vorratsliste hinzufügen</h2>
<form method='post' action=''>
    <label>Produktname :</label>
    <input type= "text" list="product_list" name="product_name" id="product_input" placeholder="Produkt wählen oder eingeben...">
    <datalist id="product_list">
    <?php

    $productsList = $productManager->getAllProducts();
    foreach ($productsList as $product): ?>
        <option value="<?= htmlspecialchars($product['name']) ?>">
    <?php endforeach; ?>
<!--//        $productsList = $productManager->getAllProducts();-->
<!--//        foreach ($productsList as $product) {-->
<!--//            echo "<option value='".$product['product_id']."'>".$product['name']."</option>";-->
<!--//        }-->
<!--//        ?>-->
    </datalist><br><br>
    <label>Kategorien : </label>
    <select name='category_id'>
        <?php
        $categoryList = $manager->getAllCategories();
        foreach ($categoryList as $category) {
            echo "<option value='".$category['category_id']."'>".$category['name']."</option>";
        }
        ?>
    </select><br><br>
<!--    <a href="index.php?action=create_category"><input type='button' value='Neue Kategorie'></a><br>-->
    <label>Menge : </label>
    <input type='number' name='quantity' min="1" max = "1000" placeholder='quantity'>
    <select name='unit'>
        <option value="Stück">Stück</option>
        <option value="Liter">Liter</option>
        <option value="Packung">Packung</option>
        <option value="Gramms">Gramms</option>
    </select><br><br>
    <label>MHD : </label>
    <input type='date' name='expiry_date'><br><br>
    <label>Minimaler Bestand : </label>
    <input type='number' name='minimum_stock' min='0' value='1'>
    <br><br>
    <lebel>Standort : </lebel>
    <select name='storage_id'>
        <?php
        $storageList = $manager->getAllStorages();
        foreach ($storageList as $storage) {
            echo "<option value='".$storage['storage_id']."'>".$storage['name']."</option>";
        }
        ?>
    </select><br><br>
   <input type="submit" class="button" value="Speichern"><br><br>
    <a href="index.php?action=read_inventory"><input type="button" class="button" value="Abbrechen"></a>
    </form>
</div>
</body>

</html>

