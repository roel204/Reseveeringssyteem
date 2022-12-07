<?php
/** @var array $db */
require_once 'connection.php';

$query = "SELECT * FROM reservations";

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$reservations = [];

while ($row = mysqli_fetch_assoc($result)) {

    $reservations[] = $row;
}

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
            <th>Datum/Tijd</th>
            <th>Details</th>
        </tr>
        <?php foreach ($reservations as $klant) { ?>
            <tr>
                <td><?= $klant['name']; ?></td>
                <td><?= $klant['email']; ?></td>
                <td><?= $klant['reason']; ?></td>
                <td><?= $klant['message']; ?></td>
                <td><?= date('d-m-Y | H:i', strtotime($klant['dateTime'])); ?></td>
                <td><a href="detail.php?id=<?= $klant['id']; ?>">Details</a></td>
            </tr>
        <?php } ?>
    </table>
</section>
</body>

</html>