<?php
/** @var array $db */
// Setup connection with database
require_once 'connection.php';
$id = $_GET['id'];
$query = "SELECT * FROM reservations WHERE id = '$id'";
// Stap 4: Query uitvoeren op de database. Als dit goed gaat, geeft
//         mysqli_query een mysqli_result terug. Let op, dit is een tabel.
// Stap 5: Foutafhandeling. Als de query niet uitgevoerd kan worden treedt
//         er een foutmelding op via "or die". Ook de query, met ingevulde
//         variabelen, wordt op het scherm getoond. Deze kan je kopieren
//         en plakken in PhpMyAdmin om te kijken waarom het fout gaat.
$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

// Stap 6: Resultaat verwerken. Er wordt een nieuwe array gemaakt waarin alle
//         rijen uit de db komen. In dit geval is een rij een album.
$reservations = [];
//         mysqli_fetch_assoc haalt een rij uit de db en zet deze om naar
$row = mysqli_fetch_assoc($result);
$reservations[] = $row;
// Stap 7: Sluit de verbinding met de db. Deze is niet meer nodig. Al het
//         resultaat zit in de variabele $albums
mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon"
          href="https://www.parrotfarm.nl/wp-content/uploads/2021/04/cropped-D31F63E9-3F1D-443D-841A-1ABC6EE3B6A3-32x32.png"
          sizes="32x32">
    <title>Details</title>
</head>
<body>
<a class="back" href="index.php">Terug naar afspraken</a>
<section class="doosje">
    <ul>
        <li>Naam: <?= $row['name']; ?></li>
        <li>Email: <?= $row['email']; ?></li>
        <li>Afspraak: <?= $row['reason']; ?></li>
        <li>Bericht: <?= $row['message']; ?></li>
        <li>Datum: <?= $row['date']; ?></li>
        <li>Tijd: <?= $row['time']; ?></li>
    </ul>
</section>
</body>
</html>
