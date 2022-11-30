<?php
/** @var array $db */
// Setup connection with database
require_once 'connection.php';

$query = "SELECT * FROM reservations";
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
//         een associatieve array. De namen van de index corresponderen met de
//         kolomnamen (velden) van de tabel
//         Als er geen rijen meer zijn in het resultaat geeft mysqli_fetch_assoc
//         'false' terug en stopt de while loop.
while ($row = mysqli_fetch_assoc($result)) {
// elke rij (dit is een album) wordt aan de array 'albums' toegevoegd.
    $reservations[] = $row;
}

// Stap 7: Sluit de verbinding met de db. Deze is niet meer nodig. Al het
//         resultaat zit in de variabele $albums
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon"
          href="https://www.parrotfarm.nl/wp-content/uploads/2021/04/cropped-D31F63E9-3F1D-443D-841A-1ABC6EE3B6A3-32x32.png"
          sizes="32x32">
    <title>Reseveer</title>
</head>

<body>
<a class="back" href="https://www.parrotfarm.nl/contact/">Terug naar contact</a>
<h1>Reseveerings-syssteem</h1>
<section class="system">
    <a class="new" href="form.php">Nieuwe afspraak maken.</a>
    <table>
        <tr>
            <th>Naam</th>
            <th>Email</th>
            <th>Afspraak</th>
            <th>Bericht</th>
            <th>Datum</th>
            <th>Tijd</th>
            <th>Details</th>
        </tr>
        <?php foreach ($reservations as $klant) { ?>
            <tr>
                <td><?= $klant['name']; ?></td>
                <td><?= $klant['email']; ?></td>
                <td><?= $klant['reason']; ?></td>
                <td><?= $klant['message']; ?></td>
                <td><?= $klant['date']; ?></td>
                <td><?= $klant['time']; ?></td>
                <td><a href="detail.php?id=<?= $klant['id']; ?>">Details</a></td>
            </tr>
        <?php } ?>
    </table>
</section>
</body>

</html>