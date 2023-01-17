<?php

session_start();

/** @var array $db */
// Conect met database.
require_once 'connection.php';

if (!isset($_SESSION['loggedInUser'])) {
    header('location: login.php');
}

// Query die naar database word gestuurd. Levert de hele reservations tabel + name regel uit reasons tabel. De reason_id word gekoppeld aan reasons_id.
$query = "
SELECT reservations.*, reasons.name AS reason_name 
FROM reservations
LEFT JOIN reasons ON reasons.id = reservations.reason_id";
$result = mysqli_query($db, $query);

// Maak lege array aan en zet alle resultaten er in.
$reservations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reservations[] = $row;
}

$reservations = array_map(function ($innerArray) {
    return array_map('htmlentities', $innerArray);
}, $reservations);

usort($reservations, function ($a, $b) {
    $a_datetime = $a['date'] . ' ' . $a['time_start'];
    $b_datetime = $b['date'] . ' ' . $b['time_start'];
    $a_timestamp = strtotime($a_datetime);
    $b_timestamp = strtotime($b_datetime);
    return $a_timestamp - $b_timestamp;
});

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
<a class="back" href="logout.php">Uitloggen</a>
<h1>Parrotfarm afspraken systeem</h1>
<h3><?= "Welkom, " . htmlentities($_SESSION['loggedInUser']['name']) . "!"; ?></h3>
<section class="system">
    <a class="button" href="create.php">Nieuwe afspraak maken</a>
    <table>
        <tr>
            <th id="table_name">Naam</th>
            <th id="table_email">Email</th>
            <th id="table_phone">Telefoon</th>
            <th id="table_reason">Afspraak</th>
            <th id="table_message">Bericht</th>
            <th id="table_date">Datum/Tijd</th>
            <th id="table_edit">Edit</th>
        </tr>
        <?php $found = false;
        foreach ($reservations as $klant) {
            // Check if the user's ID matches the user ID associated with the data being displayed
            if ($klant['user_id'] == $_SESSION['loggedInUser']['id'] || $_SESSION['loggedInUser']['admin'] == 1) {
                $found = true;
                ?>
                <tr>
                    <?php $text = htmlentities($klant['name']);
                    if (strlen($text) > 15) {
                        $text = substr($text, 0, 15) . "...";
                    }
                    echo "<td>$text</td>";
                    $text = $klant['email'];
                    if (strlen($text) > 15) {
                        $text = substr($text, 0, 15) . "...";
                    }
                    echo "<td>$text</td>"; ?>
                    <td><?= $klant['phone'] ?></td>
                    <td><?= $klant['reason_name']; ?></td>
                    <?php $text = $klant['message'];
                    if (strlen($text) > 15) {
                        $text = substr($text, 0, 15) . "...";
                    }
                    echo "<td>$text</td>"; ?>
                    <td><?= date('d M Y', strtotime($klant['date'])) ?>
                        | <?= date("H:i", strtotime($klant['time_start'])) ?>
                        - <?= date("H:i", strtotime($klant['time_end'])) ?>
                    </td>
                    <td><a href="edit.php?id=<?= $klant['id']; ?>">
                            <img src="images/edit-icon.png" alt="Edit" width="25px">
                        </a></td>
                </tr>
                <?php
            }
        }
        if (!$found) {
            $empty = 'Er zijn nog geen afspraken gemaakt.';
        }
        ?>
    </table>
    <p><?= $empty ?? '' ?></p>
</section>
<footer>
    <p>Gemaakt door Roel Hoogendoorn als project voor school.</p>
</footer>
</body>
</html>