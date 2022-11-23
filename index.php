<?php
/** @var array $reseveeringen */
require_once 'data.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Reseveer</title>
</head>

<body>
<a class="back" href="https://www.parrotfarm.nl/contact/">Terug naar contact</a>
<h1>Reseveerings-syssteem</h1>
<section class="system">
    <input class="new" type="button" value="Nieuwe afspraak">
    <table>
        <tr>
            <th>Naam</th>
            <th>Contact</th>
            <th>Afspraak</th>
            <th>Datum</th>
            <th>Tijd</th>
            <th>Details</th>
        </tr>
        <?php foreach ($reseveeringen as $klant) { ?>
            <tr>
                <td><?= $klant['name']; ?></td>
                <td><?= $klant['mail']; ?></td>
                <td><?= $klant['type']; ?></td>
                <td><?= $klant['date']; ?></td>
                <td><?= $klant['time']; ?></td>
                <td><a href="detail.php?index=">Details</a></td>
            </tr>
        <?php } ?>
    </table>
</section>
</body>

</html>