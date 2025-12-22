<?php
spl_autoload_register(function (string $class) {
    include 'classes/' . $class . '.php';
});
// Feste Zugangsdaten
use MongoDB\Driver\Manager;

$host = '127.0.0.1';
$db   = 'mein_einkaufsassistent';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;port=3307;charset=$charset";
$options = [ //wie PDO sich verhält
    // Wenn etwas in der Datenbank schiefgeht(z.B. falsche Syntax),
    // soll PHP sofort laut Alarm schlagen (eine Exception werfen), anstatt still weiterzumachen.
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    //Wenn man Daten aus der Datenbank holt, bekommt man die
    // Ergebnisse als assoziatives Array (mit den Spaltennamen als Schlüssel).
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Daten als assoziatives Array holen

    // Sie zwingt die Datenbank ($MariaDB$) dazu, die Sicherheitsarbeit selbst zu machen,
    // anstatt PHP diese Arbeit emulieren zu lassen.
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Die eigentliche Verbindung
    $pdo = new PDO($dsn, $user, $pass, $options);
    $productManager = new ProductManager($pdo);
    $manager = new InventoryManager($pdo, $productManager);
    $list = new ShoppingListManager($pdo, $manager, $productManager);
} catch (\PDOException $e) {
    // Fehlerbehandlung, falls die Verbindung fehlschlägt
    error_log("DB-Verbindugsfehler: ".$e->getMessage());
    die("Fehler: ".$e -> getMessage());
    //die("Achtung: Es konnte keine Verbindung zur Datenbank hergestellt werden.");
}
