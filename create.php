<?php
/** @var array $db */

// Connect met database.
require_once 'connection.php';

// Query om de reasons op te halen uit de database.
$query = "SELECT * FROM reasons";
$result = mysqli_query($db, $query);

// Maak lege array aan en stop alle reasons er in.
$reasons = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reasons[] = $row;
}

// Maak lege variableen in op later data in te zetten.
$nameAnswer = '';
$emailAnswer = '';
$reasonAnswer = '';
$messageAnswer = '';
$dateAnswer = '';
$timeAnswer = '';

// Maak lege variableen in op later errors in te zetten.
$nameError = '';
$emailError = '';
$reasonError = '';
$dateTimeError = '';
$dateError = '';
$timeError = '';

// Als submit dan zet alle data uit de post in de variabalen.
if (isset($_POST['submit'])) {
    $nameAnswer = $_POST['name'];
    $emailAnswer = $_POST['email'];
    $reasonAnswer = $_POST['reason_id'];
    $messageAnswer = $_POST['message'];
    $dateAnswer = $_POST['date'];
    $timeAnswer = $_POST['time'];

    // Als iets leeg is dan geef error.
    if ($_POST['name'] == '') {
        $nameError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['email'] == '') {
        $emailError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['reason_id'] == '') {
        $reasonError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['date'] == '') {
        $dateError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['time'] == '') {
        $timeError = 'Dit veld mag niet leeg zijn.';
    }

    // Als alles ingevult is dan stuur door naar de dabase en stuur door naar index pagina.
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['reason_id']) && !empty($_POST['date']) && !empty($_POST['time'])) {
        $query = "INSERT INTO reservations (`name`, `email`, `reason_id`, `message`, `date`, `time`) VALUES ('$nameAnswer', '$emailAnswer', '$reasonAnswer', '$messageAnswer', '$dateAnswer', '$timeAnswer')";
        mysqli_query($db, $query);
        header('Location: home.php');
        exit;
    }
}

// Close connection met de database.
mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <!-- Include the jQuery UI CSS styles -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="icon"
          href="https://www.parrotfarm.nl/wp-content/uploads/2021/04/cropped-D31F63E9-3F1D-443D-841A-1ABC6EE3B6A3-32x32.png"
          sizes="32x32">
    <!-- Include the jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include the jQuery UI library -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <title>Form</title>
</head>

<body>
<a class="back" href="home.php">Terug naar afspraken</a>
<form action="" method="post" class="create">
    <h2>Nieuwe Afspraak</h2>
    <section class="formfield">
        <label for="naam">Naam:<p class="error">*</p></label>
        <input type="text" name="name" id="naam" placeholder="Voornaam Achternaam" value="<?= $nameAnswer ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $nameError ?></p>
    <section class="formfield">
        <label for="email">Email:<p class="error">*</p></label>
        <input type="email" name="email" id="email" placeholder="name@mail.com" value="<?= $emailAnswer ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $emailError ?></p>
    <section class="formfield">
        <label for="reason_id">Afspraak:<p class="error">*</p></label>
        <select name="reason_id" id="reason_id">
            <?php foreach ($reasons as $reason) { ?>
                <option value=""<?php if ($reasonAnswer == '') echo "selected"; ?> hidden>Maak een keuze.</option>
                <option value="<?= $reason['id']; ?>" <?php if ($reasonAnswer == $reason['id']) echo "selected"; ?>><?= $reason['name'] ?></option>
            <?php } ?>
        </select>
    </section>
    <p class="error"><?= $reasonError ?></p>
    <section class="formfield">
        <label for="message">bericht:</label>
        <textarea name="message" id="message" autocomplete="off"><?= $messageAnswer ?></textarea>
    </section>
    <section class="formfield">
        <label for="date">Datum:<p class="error">*</p></label>
        <input type="date" name="date" id="date" value="<?= $dateAnswer ?>">

        <label for="time">Tijd:<p class="error">*</p></label>
        <select name="time" id="time">
            <option value=""<?php if ($timeAnswer == '') echo "selected"; ?> hidden>Kies een tijd.</option>
            <option value="10"<?php if ($timeAnswer == 10) echo "selected"; ?>>10:00 uur</option>
            <option value="11"<?php if ($timeAnswer == 11) echo "selected"; ?>>11:00 uur</option>
            <option value="12"<?php if ($timeAnswer == 12) echo "selected"; ?>>12:00 uur</option>
            <option value="13"<?php if ($timeAnswer == 13) echo "selected"; ?>>13:00 uur</option>
            <option value="14"<?php if ($timeAnswer == 14) echo "selected"; ?>>14:00 uur</option>
            <option value="15"<?php if ($timeAnswer == 15) echo "selected"; ?>>15:00 uur</option>
            <option value="16"<?php if ($timeAnswer == 16) echo "selected"; ?>>16:00 uur</option>
        </select>
    </section>
    <p class="error"><?= $dateError ?></p>
    <p class="error"><?= $timeError ?></p>
    <section class="formfield">
        <button type="submit" name="submit">NIEUWE AFSPRAAK</button>
    </section>
</form>
<footer>
    <p>Gemaakt door Roel Hoogendoorn als project voor school.</p>
</footer>
</body>
</html>