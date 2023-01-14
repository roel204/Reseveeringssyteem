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

$reasonAnswer = '';
$user_id = $_SESSION['loggedInUser']['id'];

// Als submit dan zet alle data uit de post in de variabalen.
if (isset($_POST['submit'])) {
    $nameAnswer = mysqli_real_escape_string($db, $_SESSION['loggedInUser']['name']);
    $emailAnswer = mysqli_real_escape_string($db, $_SESSION['loggedInUser']['email']);
    $phoneAnswer = mysqli_real_escape_string($db, $_POST['phone']);
    $reasonAnswer = mysqli_real_escape_string($db, $_POST['reason_id']);
    $messageAnswer = mysqli_real_escape_string($db, $_POST['message']);
    $dateAnswer = mysqli_real_escape_string($db, $_POST['date']);
    $time_startAnswer = mysqli_real_escape_string($db, $_POST['time_start']);
    $time_endAnswer = mysqli_real_escape_string($db, $_POST['time_end']);

    // Als iets leeg is dan geef error.
    $errors = [];

    if ($_POST['reason_id'] == '') {
        $errors['reason'] = 'Kies het soort afspraak.';
    }

    $query = "SELECT * FROM reservations WHERE (time_start < '$time_endAnswer' AND time_end > '$time_startAnswer') AND `date` = '$dateAnswer'";
    $result = mysqli_query($db, $query);
    $appointment = mysqli_fetch_assoc($result);
    if ($appointment) {
        $errors['time'] = 'Iemand anders heeft al een afspraak om deze tijd.';
    }

    $time_diff = strtotime($time_endAnswer) - strtotime($time_startAnswer);
    $time_diff_hours = $time_diff / 3600;
    if ($time_diff_hours > 2) {
        $errors['time'] = 'De maximale lengte van een afspraak is 2 uur.';
    }

    $time_diff = strtotime($time_endAnswer) - strtotime($time_startAnswer);
    $time_diff_minutes = $time_diff / 60;
    if ($time_diff_minutes < 30) {
        $errors['time'] = 'De minimale lengte van een afspraak is 30 minuten.';
    }

    $time_start_formatted = date("H:i", strtotime($time_startAnswer));
    $time_end_formatted = date("H:i", strtotime($time_endAnswer));
    if ((strtotime($time_start_formatted) < strtotime("10:00")) || (strtotime($time_end_formatted) > strtotime("17:00"))) {
        $errors['time'] = 'De afspraak moet tussen 10:00 en 17:00 zijn.';
    }

    if (strtotime($time_startAnswer) >= strtotime($time_endAnswer)) {
        $errors['time'] = 'De begin tijd moet eerder zijn dan de eind tijd.';
    }

    if ($_POST['time_start'] == '' || $_POST['time_end'] == '') {
        $errors['time'] = 'Kies een tijd.';
    }

    $dategood = false;
    if (isset($_POST['date'])) {
        $inputDate = strtotime(mysqli_real_escape_string($db, $_POST['date']));
        $currentDate = strtotime(date('Y-m-d'));
        if ($inputDate <= $currentDate) {
            $errors['date'] = 'Deze datum is in het verleden.';
        }
    }

    $selectedDate = $dateAnswer;
    $dayOfWeek = date("l", strtotime($selectedDate));
    if ($dayOfWeek == "Monday" || $dayOfWeek == "Tuesday" || $dayOfWeek == "Wednesday") {
        // Good
    } else {
        $errors['date'] = 'Selecteer een datum die op Maandag, Dinsdag of Woensdag valt.';
    }

    // Als alles ingevult is dan stuur door naar de dabase en stuur door naar index pagina.
    if (empty($errors)) {
        $query = "INSERT INTO reservations (`user_id`, `name`, `email`, `phone`, `reason_id`, `message`, `date`, `time_start`, `time_end`) VALUES ('$user_id', '$nameAnswer', '$emailAnswer', '$phoneAnswer', '$reasonAnswer', '$messageAnswer', '$dateAnswer', '$time_startAnswer', '$time_endAnswer')";
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
        <label for="reason_id">Afspraak:<span class="error">*</span></label>
        <select name="reason_id" id="reason_id">
            <?php foreach ($reasons as $reason) { ?>
                <option value=""<?php if ($reasonAnswer == '') echo "selected"; ?> hidden>Maak een keuze.</option>
                <option value="<?= $reason['id']; ?>" <?php if ($reasonAnswer == $reason['id']) echo "selected"; ?>><?= $reason['name'] ?></option>
            <?php } ?>
        </select>
    </section>
    <p class="error"><?= $errors['reason'] ?? '' ?></p>
    <section class="formfield">
        <label for="message">bericht:</label>
        <textarea name="message" id="message" autocomplete="off"><?= $messageAnswer ?? '' ?></textarea>
    </section>
    <section class="formfield">
        <label class="date_label" for="date">Datum:<span class="error">*</span></label>
        <input type="date" name="date" id="date" value="<?= $dateAnswer ?? '' ?>">
        <label class="time_label" for="time_start">Vanaf:<span class="error">*</span></label>
        <input type="time" name="time_start" id="time_start" value="<?= $time_startAnswer ?? '' ?>" step="60">
        <label class="time_end_label" for="time_end">Tot:<span class="error">*</span></label>
        <input type="time" name="time_end" id="time_end" value="<?= $time_endAnswer ?? '' ?>" step="60">
    </section>
    <p class="error"><?= $errors['date'] ?? '' ?></p>
    <p class="error"><?= $errors['time'] ?? '' ?></p>
    <section class="formfield">
        <button type="submit" name="submit">NIEUWE AFSPRAAK</button>
    </section>
</form>
<footer>
    <p>Gemaakt door Roel Hoogendoorn als project voor school.</p>
</footer>
</body>
</html>