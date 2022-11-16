<?php
$reseveeringen = [
    [
        'name' => 'Roel Hoogendoorn',
        'email' => 'roelhoogendoorn01369@gmail.com',
        'date' => 'Woensdag 16-11',
        'time' => '4:20'
    ],
    [
        'name' => 'Zhong Xina',
        'email' => 'Zhong@gmail.com',
        'date' => 'Woensdag 20-4',
        'time' => '1:11'
    ],
    [
        'name' => 'Mr Placeholder',
        'email' => 'Placeholder@outlook.com',
        'date' => 'Maandag 14-11',
        'time' => '2:30'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reseveer</title>
</head>

<body>
<h1>Reseveerings-syssteem</h1>
<a class="button" href="https://www.parrotfarm.nl/contact/">Terug</a>

<table>
    <tr>
        <th>Naam</th>
        <th>Contact</th>
        <th>Datum</th>
        <th>Tijd</th>
    </tr>
    <?php foreach ($reseveeringen as $klant) { ?>
        <tr>
            <td><?= $klant['name']; ?></td>
            <td><?= $klant['email']; ?></td>
            <td><?= $klant['date']; ?></td>
            <td><?= $klant['time']; ?></td>
        </tr>
    <?php } ?>
</table>
</body>

</html>