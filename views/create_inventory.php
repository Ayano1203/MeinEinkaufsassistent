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
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
             <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                         <meta http-equiv="X-UA-Compatible" content="ie=edge">
             <title>neue Artikel</title>
</head>
<body>
<div class="container">
<h2>Neue Artikel zur Vorratsliste hinzufügen</h2>
<form method='post' action=''>
    <lebel>Produktname : </lebel>
    <input type='text' name='product_name' placeholder='Produktname'><br>
    <label>Kategorien : </label>
    <select name='category'>
        //die Option muss später von category liste nehmen
        <option value="Milchprodukte">Milchprodukte</option>
        <option value="Getreide & Backen">Getreide & Backen</option>
        <option value="Obst">Obst</option>
        <option value="Gemüse">Gemüse</option>
    </select><br>
    <label>Menge : </label>
    <input type='number' name='quantity' min="1" max = "1000" placeholder='quantity'>
    <select name='unit'>
        <option value="Stück">Stück</option>
        <option value="Liter">Liter</option>
        <option value="Packung">Packung</option>
        <option value="Gramms">Gramms</option>
    </select><br>
    <lebel>Standort : </lebel>
    <select name='storage'>
        <option value="Kühlschrank">Kühlschrank</option>
        <option value="Vorratskammer">Vorratskammer</option>
        <option value="Gefrierschrank">Gefrierschrank</option>
    </select><br><br>
   <input type="submit" class="button" value="Speichern"><br><br>
    <a href="read_inventory.php"><input type="button" class="button" value="Abbrechen"></a>
    </form>
</div>
</body>

</html>

