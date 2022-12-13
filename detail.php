<?php
/** @var array $db */

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

require_once 'connection.php';
$id = $_GET['id'];
$query = "SELECT * FROM reservations WHERE id = '$id'";

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$row = mysqli_fetch_assoc($result);

$nameAnswer = '';
$emailAnswer = '';
$reasonAnswer = '';
$messageAnswer = '';
$dateTimeAnswer = '';

$nameError = '';
$emailError = '';
$reasonError = '';
$dateTimeError = '';

if (isset($_POST['submit'])) {
    $nameAnswer = $_POST['name'];
    $emailAnswer = $_POST['email'];
    $reasonAnswer = $_POST['reason'];
    $messageAnswer = $_POST['message'];
    $dateTimeAnswer = date('Y-m-d H:i', strtotime($_POST['dateTime']));

    if ($_POST['name'] == '') {
        $nameError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['email'] == '') {
        $emailError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['reason'] == '') {
        $reasonError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['dateTime'] == '') {
        $dateTimeError = 'Dit veld mag niet leeg zijn.';
    }
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['reason']) && !empty($_POST['dateTime'])) {
        $query = "UPDATE `reservations` SET `name`='$nameAnswer',`email`='$emailAnswer',`reason`='$reasonAnswer',`message`='$messageAnswer',`dateTime`='$dateTimeAnswer' WHERE id = '$_POST[id]'";
        mysqli_query($db, $query);
        header('Location: index.php');
        exit;
    }
}

if (isset($_POST['delete_button'])) {
    $query = "DELETE FROM reservations WHERE id = '$id'";
    mysqli_query($db, $query);
    header('Location: index.php');
    exit;
}
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
        <li>Datum/Tijd: <?= date('d-m-Y | H:i', strtotime($row['dateTime'])); ?></li>
    </ul>
</section>
<form action="" method="post" class="create">
    <section class="formfield">
        <label for="naam">Naam:</label>
        <input type="text" name="name" id="naam" placeholder="Voornaam Achternaam" value="<?= $row['name']; ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $nameError ?></p>
    <section class="formfield">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="name@mail.com" value="<?= $row['email']; ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $emailError ?></p>
    <section class="formfield">
        <label for="reason">Afspraak:</label>
        <select name="reason" id="reason">
            <option value=""<?php if ($row['reason'] == '') echo "selected"; ?> hidden>Maak een keuze.</option>
            <option value="kijken"<?php if ($row['reason'] == 'kijken') echo "selected"; ?>>Komen kijken naar vogels.
            </option>
            <option value="bloed"<?php if ($row['reason'] == 'bloed') echo "selected"; ?>>Bloed afnemen van mijn
                vogel.
            </option>
            <option value="nagels"<?php if ($row['reason'] == 'nagels') echo "selected"; ?>>Nagels laten knippen.
            </option>
            <option value="veren"<?php if ($row['reason'] == 'veren') echo "selected"; ?>>Veren laten knippen.</option>
            <option value="opvang"<?php if ($row['reason'] == 'opvang') echo "selected"; ?>>Opvang voor mijn vogel
                regelen.
            </option>
            <option value="other"<?php if ($row['reason'] == 'other') echo "selected"; ?>>Anders. (Geef reden aan in
                bericht)
            </option>
        </select>
    </section>
    <p class="error"><?= $reasonError ?></p>
    <section class="formfield">
        <label for="message">bericht:</label>
        <textarea name="message" id="message" autocomplete="off"><?= $row['message']; ?></textarea>
    </section>
    <section class="formfield">
        <label for="dateTime">Datum:</label>
        <input type="datetime-local" name="dateTime" id="dateTime" value="<?= $row['dateTime']; ?>">
    </section>
    <p class="error"><?= $dateTimeError ?></p>
    <input type="hidden" name="id" value="<?= $id ?>">
    <section class="formfield">
        <button type="submit" name="submit">Submit</button>
    </section>
</form>
<form action="" method="post" class="delete">
    <label for="delete_button">Delete</label>
    <input type="checkbox" name="delete_button" id="delete_button" value="DELETE">
    <input type="submit" value="DELETE">
</form>

</body>
</html>
