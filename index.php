<?php
$reseveeringen = [
    [
        'name' => 'Roel Hoogendoorn',
        'email' => 'roelhoogendoorn01369@gmail.com',
        'day' => 'Maandag',
        'time' => '4:20'
    ],
    [
        'name' => 'Zhong Xina',
        'email' => 'Zhong@gmail.com',
        'day' => 'Dinsdag',
        'time' => '1:11'
    ],
    [
        'name' => 'Mr Placeholder',
        'email' => 'Placeholder@outlook.com',
        'day' => 'Woensdag',
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

<table>
    <tr>
        <th>Naam</th>
        <th>Contact</th>
        <th>Dag</th>
        <th>Tijd</th>
    </tr>
    <?php foreach ($reseveeringen as $klant) { ?>
        <tr>
            <td><?= $klant['name']; ?></td>
            <td><?= $klant['email']; ?></td>
            <td><?= $klant['day']; ?></td>
            <td><?= $klant['time']; ?></td>
        </tr>
    <?php } ?>
</table>
</body>

</html>