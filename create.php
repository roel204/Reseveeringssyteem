<?php

session_start();

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

$date = false;
$reasonAnswer = '';
$timeAnswer = '';
$user_id = $_SESSION['loggedInUser']['id'];

// Als submit dan zet alle data uit de post in de variabalen.
if (isset($_POST['submit'])) {
    $nameAnswer = mysqli_real_escape_string($db, $_POST['name']);
    $emailAnswer = mysqli_real_escape_string($db, $_POST['email']);
    $phoneAnswer = mysqli_real_escape_string($db, $_POST['phone']);
    $reasonAnswer = mysqli_real_escape_string($db, $_POST['reason_id']);
    $messageAnswer = mysqli_real_escape_string($db, $_POST['message']);
    $dateAnswer = mysqli_real_escape_string($db, $_POST['date']);
    $timeAnswer = mysqli_real_escape_string($db, $_POST['time']);

    // Als iets leeg is dan geef error.
    if ($_POST['name'] == '') {
        $nameError = 'Vul een naam in.';
    }
    if ($_POST['email'] == '') {
        $emailError = 'Vul een email in.';
    }
    if ($_POST['reason_id'] == '') {
        $reasonError = 'Kies het soort afspraak.';
    }
    if (isset($_POST['date'])) {
        $inputDate = strtotime(mysqli_real_escape_string($db, $_POST['date']));
        $currentDate = strtotime(date('Y-m-d'));
        if ($inputDate <= $currentDate) {
            $dateError = 'Deze datum is in het verleden.';
        }
    }
    if ($_POST['time'] == '') {
        $timeError = 'Kies een tijd.';
    }

    $selectedDate = $dateAnswer;

    $dayOfWeek = date("l", strtotime($selectedDate));

    if ($dayOfWeek == "Monday" || $dayOfWeek == "Tuesday" || $dayOfWeek == "Wednesday") {
        $date = true;
    } else {
        $dateError = 'Selecteer een datum die op Maandag, Dinsdag of Woensdag valt.';
    }

    // Als alles ingevult is dan stuur door naar de dabase en stuur door naar index pagina.
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['reason_id']) && !empty($_POST['date']) && !empty($_POST['time']) && $date && $inputDate > $currentDate) {
        $query = "INSERT INTO reservations (`user_id`, `name`, `email`, `phone`, `reason_id`, `message`, `date`, `time`) VALUES ('$user_id', '$nameAnswer', '$emailAnswer', '$phoneAnswer', '$reasonAnswer', '$messageAnswer', '$dateAnswer', '$timeAnswer')";
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
    <link rel="icon"
          href="https://www.parrotfarm.nl/wp-content/uploads/2021/04/cropped-D31F63E9-3F1D-443D-841A-1ABC6EE3B6A3-32x32.png"
          sizes="32x32">
    <title>Form</title>
</head>

<body>
<a class="back" href="home.php">Terug naar afspraken</a>
<form action="" method="post" class="create">
    <h2>Nieuwe Afspraak</h2>
    <section class="formfield">
        <!--        <label for="naam">Naam:<p class="error">*</p></label>-->
        <input type="text" name="name" id="naam" placeholder="Voornaam Achternaam"
               value="<?= $nameAnswer ?? $_SESSION['loggedInUser']['name'] ?>" hidden
               autocomplete="off">
    </section>
    <p class="error"><?= $nameError ?? '' ?></p>
    <section class="formfield">
        <!--        <label for="email">Email:<p class="error">*</p></label>-->
        <input type="email" name="email" id="email" placeholder="name@mail.com"
               value="<?= $emailAnswer ?? $_SESSION['loggedInUser']['email'] ?>" hidden
               autocomplete="off">
    </section>
    <p class="error"><?= $emailError ?? '' ?></p>
    <section class="formfield">
        <label for="phone">Telefoon:</label>
        <input type="tel" name="phone" id="phone" pattern="\d{2} \d{8}" placeholder="06 12345678"
               value="<?= $phoneAnswer ?? '' ?>" autocomplete="off">
        <script>
            document.getElementById('phone').addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,8})/);
                e.target.value = !x[2] ? x[1] : x[1] + ' ' + x[2] + (x[3] ? '-' + x[3] : '');

            });
        </script>
    </section>
    <section class="formfield">
        <label for="reason_id">Afspraak:<p class="error">*</p></label>
        <select name="reason_id" id="reason_id">
            <?php foreach ($reasons as $reason) { ?>
                <option value=""<?php if ($reasonAnswer == '') echo "selected"; ?> hidden>Maak een keuze.</option>
                <option value="<?= $reason['id']; ?>" <?php if ($reasonAnswer == $reason['id']) echo "selected"; ?>><?= $reason['name'] ?></option>
            <?php } ?>
        </select>
    </section>
    <p class="error"><?= $reasonError ?? '' ?></p>
    <section class="formfield">
        <label for="message">bericht:</label>
        <textarea name="message" id="message" autocomplete="off"><?= $messageAnswer ?? '' ?></textarea>
    </section>
    <section class="formfield">
        <label for="date">Datum:<p class="error">*</p></label>
        <input type="date" name="date" id="date" value="<?= $dateAnswer ?? '' ?>">
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
    <p class="error"><?= $dateError ?? '' ?></p>
    <p class="error"><?= $timeError ?? '' ?></p>
    <section class="formfield">
        <button type="submit" name="submit">NIEUWE AFSPRAAK</button>
    </section>
</form>
<footer>
    <p>Gemaakt door Roel Hoogendoorn als project voor school.</p>
</footer>
</body>
</html>