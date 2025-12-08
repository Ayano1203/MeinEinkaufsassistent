<?php
$manager = new InventoryManager($pdo);

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
    <lebel>Produkt wählen : </lebel>
    <select name='product_id'>
        <option value ="" selected disabled>---Bitte Produkt wählen---</option>
    <?php
        $productsList = $manager->getAllProducts();
        foreach ($productsList as $product) {
            echo "<option value='".$product['product_id']."'>".$product['name']."</option>";
        }
        ?>
    </select>
    <label> Oder neues Produkt : </label>
    <input type='text' name='new_product_name' placeholder='Produktname'><br>
    <label>Kategorien : </label>
    <select name='category_id'>
        <?php
        $categoryList = $manager->getAllCategories();
        foreach ($categoryList as $category) {
            echo "<option value='".$category['category_id']."'>".$category['name']."</option>";
        }
        ?>
<!--        die Option muss später von category liste nehmen-->
<!--        <option value="Milchprodukte">Milchprodukte</option>-->
<!--        <option value="Getreide & Backen">Getreide & Backen</option>-->
<!--        <option value="Obst">Obst</option>-->
<!--        <option value="Gemüse">Gemüse</option>-->
    </select><br>
<!--    <a href="index.php?action=create_category"><input type='button' value='Neue Kategorie'></a><br>-->
    <label>Menge : </label>
    <input type='number' name='quantity' min="1" max = "1000" placeholder='quantity'>
    <select name='unit'>
        <option value="Stück">Stück</option>
        <option value="Liter">Liter</option>
        <option value="Packung">Packung</option>
        <option value="Gramms">Gramms</option>
    </select><br>
    <label>MHD : </label>
    <input type='date' name='expiry_date'><br>
    <label>Minimaler Bestand : </label>
    <input type='number' name='minimum_stock' min='0' value='1'>
    <br>
    <lebel>Standort : </lebel>
    <select name='storage'>
        <option value="Kühlschrank">Kühlschrank</option>
        <option value="Vorratskammer">Vorratskammer</option>
        <option value="Gefrierschrank">Gefrierschrank</option>
    </select><br><br>
   <input type="submit" class="button" value="Speichern"><br><br>
    <a href="index.php?action=read_inventory"><input type="button" class="button" value="Abbrechen"></a>
    </form>
</div>
</body>

</html>

