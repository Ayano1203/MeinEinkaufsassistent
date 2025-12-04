<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require "../views/create_inventory.php";
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lastInsertedID = $_POST["id"];
}