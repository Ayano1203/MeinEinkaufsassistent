<?php
include("config/config.php");
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
        <input type= "text" list="product_list" name="product_name" id="product_input" placeholder="Produkt wählen oder eingeben...">
        <datalist id="product_list">
            <?php
            $productsList = $productManager->getAllProducts();
//            Array
//            (
//                    [0] => Array
//                    (
//                            [product_id] => 1
//            [name] => Milch
//            [category_ID] => 1
//            [minimum_stock] => 1
//            [unit] => Packung
//        )
            foreach ($productsList as $product): ?>
                <option value="<?= htmlspecialchars($product['name']) ?>">
            <?php endforeach; ?>
        </datalist><br><br>
<!--        <select name="product_id" id="product_select">-->
<!--            <option value="" selected disabled>---Bitte Produkt wählen---</option>-->
<!--            --><?php
//            $productsList = $manager->getAllProducts();
//            foreach ($productsList as $product) {
//                echo "<option value='".$product['product_id']."'>".$product['name']."</option>";
//
//            }
//            ?>
<!--        </select><br>-->
        <label>Kategorie :</label>
        <select name='category_id'>
            <?php
            $categoryList = $manager->getAllCategories();
            foreach ($categoryList as $category) {
                echo "<option value='".$category['category_id']."'>".$category['name']."</option>";
            }
            ?>
        </select><br><br>
        <label>Bedarf : </label>
        <input type='number' name='minimum_stock' min='0' value='1'>
        <br><br>
        <label>Unit :</label>
        <select name='unit'>
            <option value="Stück">Stück</option>
            <option value="Liter">Liter</option>
            <option value="Packung">Packung</option>
            <option value="Gramms">Gramms</option>
        </select><br><br>
        <input type="submit" name="button" value="Speichern">
    </fieldset>
</form><br><br>
<a href="index.php?action=dashboard"><input type="button" value="zurück"></a>
<!--<script>-->
<!--    // Steuerung der Formularkonsistenz (Steuert die Logik "Entweder NEUES Produkt ODER ein VORHANDENES")-->
<!--    // Elemente im HTML anhand ihrer ID suchen-->
<!--    const productSelect = document.getElementById('product_select');-->
<!--    const newProductInput = document.getElementById('new_product_input');-->
<!---->
<!--    //1. Wenn ein VORHANDENES Produkt ausgewählt wird-->
<!--    // Einen "Event-Listener" hinzufügen: Wenn der Wert im Dropdown geändert wird...-->
<!--    productSelect.addEventListener('change', function(){-->
<!--        if (this.value !==""){-->
<!--            // Überprüfen, ob ein gültiger Wert (nicht die leere Anfangsoption) ausgewählt wurde-->
<!--            // Wenn etwas ausgewählt wurde:-->
<!--            newProductInput.value = ''; // 1. Das Feld für NEUE Produkte leeren-->
<!--            newProductInput.disabled = true;  // 2. Das Feld für NEUE Produkte deaktivieren (damit nichts eingegeben werden kann)-->
<!--            newProductInput.placeholder = 'Produkt ist schon ausgewählt';-->
<!--        }else {-->
<!--            // Wenn die Auswahl wieder auf die Anfangsoption (z.B. "---Bitte Produkt wählen---") zurückgesetzt wird:-->
<!--            newProductInput.disabled = false;-->
<!--            newProductInput.placeholder ='Produktname';-->
<!--        }-->
<!--    });-->
<!--    // 2. Wenn ein NEUER Produktname eingegeben wird (newProductInput)-->
<!--    // Einen "Event-Listener" hinzufügen: Jedes Mal, wenn der Benutzer etwas eingibt (input-Ereignis)...-->
<!--    newProductInput.addEventListener('input', function(){-->
<!--        // Überprüfen, ob das Textfeld nicht leer ist (nach Entfernung von Leerzeichen am Rand)-->
<!--        if(this.value.trim !==''){-->
<!--            // Wenn Text eingegeben wurde:-->
<!--            // 1. Die Auswahl des Dropdowns zurücksetzen (auf die erste Option)-->
<!--            productSelect.selectIndex = 0;-->
<!--            // 2. Das Dropdown für VORHANDENE Produkte deaktivieren-->
<!--            productSelect.disabled = true;-->
<!--        } else {-->
<!--            // Wenn das Textfeld wieder leer ist:-->
<!--            // 1. Das Dropdown für VORHANDENE Produkte wieder aktivieren-->
<!--            productSelect.disabled =false;-->
<!--        }-->
<!--        })-->
<!--    })-->
<!--</script>-->
</body>

</html>

