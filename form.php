<?php
/** @var array $db */
require_once 'connection.php';

$nameAnswer = '';
$emailAnswer = '';
$reasonAnswer = '';
$messageAnswer = '';
$dateAnswer = '';
$timeAnswer = '';

$nameError = '';
$emailError = '';
$reasonError = '';
$dateError = '';
$timeError = '';

if(isset($_POST['submit'])) {
    $nameAnswer = $_POST['name'];
    $emailAnswer = $_POST['email'];
    $reasonAnswer = $_POST['reason'];
    $messageAnswer = $_POST['message'];
    $dateAnswer = $_POST['date'];
    $timeAnswer = $_POST['time'];

    if($_POST['name'] == '') {
        $nameError = 'Dit veld mag niet leeg zijn.';
    }
    if($_POST['email'] == '') {
        $emailError = 'Dit veld mag niet leeg zijn.';
    }
    if($_POST['reason'] == '') {
        $reasonError = 'Dit veld mag niet leeg zijn.';
    }
    if($_POST['date'] == '') {
        $dateError = 'Dit veld mag niet leeg zijn.';
    }
    if($_POST['time'] == '') {
        $timeError = 'Dit veld mag niet leeg zijn.';
    }
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['reason']) && !empty($_POST['date']) && !empty($_POST['time'])) {
        $query = "INSERT INTO reservations (`name`, `email`, `reason`, `message`, `date`, `time`) VALUES ('$nameAnswer', '$emailAnswer', '$reasonAnswer', '$messageAnswer', '$dateAnswer', '$timeAnswer')";
        mysqli_query($db, $query);
        header('Location: index.php');
        exit;
    }
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
    <title>Form</title>
</head>

<body>
<a class="back" href="index.php">Terug naar afspraken</a>
<form action="" method="post" class="create">
    <section class="formfield">
        <label for="naam">Naam:</label>
        <input type="text" name="name" id="naam" placeholder="Voornaam Achternaam" value="<?= $nameAnswer ?>" autocomplete="off">
    </section>
    <p class="error"><?= $nameError ?></p>
    <section class="formfield">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="name@mail.com" value="<?= $emailAnswer ?>" autocomplete="off">
    </section>
    <p class="error"><?= $emailError ?></p>
    <section class="formfield">
        <label for="reason">Afspraak:</label>
        <select name="reason" id="reason">
            <option value="" selected hidden>Maak een keuze.</option>
            <option value="kijken">Komen kijken naar vogels.</option>
            <option value="bloed">Bloed afnemen van mijn vogel.</option>
            <option value="nagels">Nagels laten knippen.</option>
            <option value="veren">Veren laten knippen.</option>
            <option value="opvang">Opvang voor mijn vogel regelen.</option>
            <option value="other">Anders. (Geef reden aan in bericht)</option>
        </select>
    </section>
    <p class="error"><?= $reasonError ?></p>
    <section class="formfield">
        <label for="message">bericht:</label>
        <textarea name="message" id="message" value="<?= $messageAnswer ?>" autocomplete="off"></textarea>
    </section>
    <section class="formfield">
        <label for="date">Datum:</label>
        <input type="date" name="date" id="date" value="<?= $dateAnswer ?>">
    </section>
    <p class="error"><?= $dateError ?></p>
    <section class="formfield">
        <label for="time">Tijd:</label>
        <input type="time" name="time" id="time" value="<?= $timeAnswer ?>">
    </section>
    <p class="error"><?= $timeError ?></p>
    <section class="formfield">
        <button type="submit" name="submit">Submit</button>
        <input id="reset" type="reset">
    </section>
</form>
</body>
</html>