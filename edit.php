<?php
/** @var array $db */

// Stuurt user terug als de pagina word bezocht zonder id.
if (!isset($_GET['id'])) {
    header('Location: home.php');
}

// Connect met database.
require_once 'connection.php';

// Maak variable aan en stop id er in.
$id = $_GET['id'];

// Doet query voor de dropdown reasons.
$query = "SELECT * FROM reasons";
$result = mysqli_query($db, $query);

// Maak lege array aan en zet alle reasons en in.
$reasons = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reasons[] = $row;
}

// Query voor de rest van de data.
$query = "SELECT * FROM reservations WHERE id = '$id';";
$result = mysqli_query($db, $query) or die ('Error: ' . $query);

// Stopt alle data in de row variable.
$row = mysqli_fetch_assoc($result);

// Maak lege variableen in op later data in te zetten.
$nameAnswer = '';
$emailAnswer = '';
$phoneAnswer = '';
$reasonAnswer = $row['reason_id'];
$messageAnswer = '';
$dateAnswer = '';
$timeAnswer = '';

// Maak lege variableen in op later errors in te zetten.
$nameError = '';
$emailError = '';
$reasonError = '';
$dateError = '';
$timeError = '';

// Als submit dan zet alle data uit de post in de variabalen.
if (isset($_POST['submit'])) {
    $nameAnswer = $_POST['name'];
    $emailAnswer = $_POST['email'];
    $phoneAnswer = $_POST['phone'];
    $reasonAnswer = $_POST['reason'];
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
    if ($_POST['reason'] == '') {
        $reasonError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['date'] == '') {
        $dateError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['time'] == '') {
        $timeError = 'Dit veld mag niet leeg zijn.';
    }

    // Als alles ingevult is dan stuur door naar de dabase en stuur door naar index pagina.
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['reason']) && !empty($_POST['date']) && !empty($_POST['time'])) {
        $query = "UPDATE `reservations` SET `name`='$nameAnswer',`email`='$emailAnswer', `phone`='$phoneAnswer',`reason_id`='$reasonAnswer',`message`='$messageAnswer',`date`='$dateAnswer',`time`='$timeAnswer'WHERE id = '$_POST[id]'";
        mysqli_query($db, $query);
        header('Location: home.php');
        exit;
    }
}

// Als er in een post 'delete_botton' zit (alleen in delete form post) dan delete die id regel uit de database en stuur door naar index pagina.
if (isset($_POST['delete_button'])) {
    $query = "DELETE FROM reservations WHERE id = '$id'";
    mysqli_query($db, $query);
    header('Location: home.php');
    exit;
}

// Close connection met database.
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
<a class="back" href="home.php">Terug naar afspraken</a>
<form action="" method="post" class="create">
    <h2>Edit Afspraak</h2>
    <section class="formfield">
        <label for="naam">Naam:<p class="error">*</p></label>
        <input type="text" name="name" id="naam" placeholder="Voornaam Achternaam" value="<?= $row['name']; ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $nameError ?></p>
    <section class="formfield">
        <label for="email">Email:<p class="error">*</p></label>
        <input type="email" name="email" id="email" placeholder="name@mail.com" value="<?= $row['email']; ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $emailError ?></p>
    <section class="formfield">
        <label for="phone">Telefoon:</label>
        <input type="tel" name="phone" id="phone" placeholder="06 12345678" value="<?= $row['phone']; ?>"
               autocomplete="off">
        <script>
            document.getElementById('phone').addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,8})/);
                e.target.value = !x[2] ? x[1] : x[1] + ' ' + x[2] + (x[3] ? '-' + x[3] : '');

            });
        </script>
    </section>
    <section class="formfield">
        <label for="reason">Afspraak:<p class="error">*</p></label>
        <select name="reason" id="reason">
            <?php foreach ($reasons as $reason) { ?>
                <option value=""<?php if ($reasonAnswer == '') echo "selected"; ?> hidden>Maak een keuze.</option>
                <option value="<?= $reason['id']; ?>" <?php if ($row['reason_id'] == $reason['id']) echo "selected"; ?>><?= $reason['name'] ?></option>
            <?php } ?>
        </select>
    </section>
    <p class="error"><?= $reasonError ?></p>
    <section class="formfield">
        <label for="message">bericht:</label>
        <textarea name="message" id="message" autocomplete="off"><?= $row['message']; ?></textarea>
    </section>
    <section class="formfield">
        <label for="date">Datum:<p class="error">*</p></label>
        <input type="date" name="date" id="date" value="<?= $row['date']; ?>">
        <label for="time">Tijd:<p class="error">*</p></label>
        <select name="time" id="time">
            <option value=""<?php if ($row['time'] == '') echo "selected"; ?> hidden>Kies een tijd.</option>
            <option value="10"<?php if ($row['time'] == 10) echo "selected"; ?>>10:00 uur</option>
            <option value="11"<?php if ($row['time'] == 11) echo "selected"; ?>>11:00 uur</option>
            <option value="12"<?php if ($row['time'] == 12) echo "selected"; ?>>12:00 uur</option>
            <option value="13"<?php if ($row['time'] == 13) echo "selected"; ?>>13:00 uur</option>
            <option value="14"<?php if ($row['time'] == 14) echo "selected"; ?>>14:00 uur</option>
            <option value="15"<?php if ($row['time'] == 15) echo "selected"; ?>>15:00 uur</option>
            <option value="16"<?php if ($row['time'] == 16) echo "selected"; ?>>16:00 uur</option>
        </select>
    </section>
    <p class="error"><?= $dateError ?></p>
    <p class="error"><?= $timeError ?></p>
    <input type="hidden" name="id" value="<?= $id ?>">
    <section class="formfield">
        <button type="submit" name="submit">EDIT</button>
    </section>
</form>
<form action="" method="post" class="delete">
    <h2>Delete Afspraak</h2>
    <section class="formfield">
        <label for="delete_button">Weet u zeker dat u de afspraak wilt verwijderen?</label>
        <input type="checkbox" name="delete_button" id="delete_button" value="DELETE">
    </section>
    <button type="submit">DELETE</button>
</form>
<footer>
    <p>Gemaakt door Roel Hoogendoorn als project voor school.</p>
</footer>
</body>
</html>
