
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mein Einkaufsassistent</title>
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
    <div class="header">
        <h1>Mein Einkaufsassistent</h1>
        <h2>Dashboard</h2>
    </div>
    <div class= "content">
        <a href="index.php?action=read_inventory"><input type="button"  class= "button" value="Vorratsliste"></a>
        <a href="index.php?action=read_shoppingList"><input type="button"  class= "button" value="Einkaufsliste"></a>
        <br><br>
        <a href="index.php?action=create_inventory"><input type="button"  class= "button" value="Vorratsliste hinzufügen"></a>
        <a href="index.php?action=create_shoppingList"><input type="button"  class= "button" value="Einkaufsliste hinzufügen"></a>
    </div>
</div>
