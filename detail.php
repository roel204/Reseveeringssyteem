<?php
/** @var array $db */

// Stuurt user terug als de pagina word bezocht zonder id.
if (!isset($_GET['id'])) {
    header('Location: index.php');
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
$reasonAnswer = $row['reason_id'];
$messageAnswer = '';
$dateTimeAnswer = '';

// Maak lege variableen in op later errors in te zetten.
$nameError = '';
$emailError = '';
$reasonError = '';
$dateTimeError = '';

// Als submit dan zet alle data uit de post in de variabalen.
if (isset($_POST['submit'])) {
    $nameAnswer = $_POST['name'];
    $emailAnswer = $_POST['email'];
    $reasonAnswer = $_POST['reason'];
    $messageAnswer = $_POST['message'];
    $dateTimeAnswer = date('Y-m-d H:i', strtotime($_POST['dateTime']));

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
    if ($_POST['dateTime'] == '') {
        $dateTimeError = 'Dit veld mag niet leeg zijn.';
    }

    // Als alles ingevult is dan stuur door naar de dabase en stuur door naar index pagina.
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['reason']) && !empty($_POST['dateTime'])) {
        $query = "UPDATE `reservations` SET `name`='$nameAnswer',`email`='$emailAnswer',`reason_id`='$reasonAnswer',`message`='$messageAnswer',`dateTime`='$dateTimeAnswer' WHERE id = '$_POST[id]'";
        mysqli_query($db, $query);
        header('Location: index.php');
        exit;
    }
}

// Als er in een post 'delete_botton' zit (alleen in delete form post) dan delete die id regel uit de database en stuur door naar index pagina.
if (isset($_POST['delete_button'])) {
    $query = "DELETE FROM reservations WHERE id = '$id'";
    mysqli_query($db, $query);
    header('Location: index.php');
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
<a class="back" href="index.php">Terug naar afspraken</a>
<form action="" method="post" class="create">
    <h2>Edit Afspraak</h2>
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
        <label for="dateTime">Datum:</label>
        <input type="datetime-local" name="dateTime" id="dateTime" value="<?= $row['dateTime']; ?>">
    </section>
    <p class="error"><?= $dateTimeError ?></p>
    <input type="hidden" name="id" value="<?= $id ?>">
    <section class="formfield">
        <button type="submit" name="submit">EDIT</button>
    </section>
</form>
<form action="" method="post" class="delete">
    <h2>Delete Afspraak</h2>
    <label for="delete_button">Weet u zeker dat u de afspraak wilt verwijderen?</label>
    <input type="checkbox" name="delete_button" id="delete_button" value="DELETE">
    <button type="submit">DELETE</button>
</form>

</body>
</html>
