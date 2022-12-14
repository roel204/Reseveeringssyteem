<?php
/** @var array $db */
require_once 'connection.php';

$query = "SELECT * FROM reasons";
$result = mysqli_query($db, $query);

$reasons = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reasons[] = $row;
}

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
    $reasonAnswer = $_POST['reason_id'];
    $messageAnswer = $_POST['message'];
    $dateTimeAnswer = date('Y-m-d H:i', strtotime($_POST['dateTime']));

    if ($_POST['name'] == '') {
        $nameError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['email'] == '') {
        $emailError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['reason_id'] == '') {
        $reasonError = 'Dit veld mag niet leeg zijn.';
    }
    if ($_POST['dateTime'] == '') {
        $dateTimeError = 'Dit veld mag niet leeg zijn.';
    }
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['reason_id']) && !empty($_POST['dateTime'])) {
        $query = "INSERT INTO reservations (`name`, `email`, `reason_id`, `message`, `dateTime`) VALUES ('$nameAnswer', '$emailAnswer', '$reasonAnswer', '$messageAnswer', '$dateTimeAnswer')";
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
        <input type="text" name="name" id="naam" placeholder="Voornaam Achternaam" value="<?= $nameAnswer ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $nameError ?></p>
    <section class="formfield">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="name@mail.com" value="<?= $emailAnswer ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $emailError ?></p>
    <section class="formfield">
        <label for="reason_id">Afspraak:</label>
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
        <label for="dateTime">Datum:</label>
        <input type="datetime-local" name="dateTime" id="dateTime" value="<?= $dateTimeAnswer ?>">
    </section>
    <p class="error"><?= $dateTimeError ?></p>
    <section class="formfield">
        <button type="submit" name="submit">Submit</button>
    </section>
</form>
</body>
</html>