<?php
/** @var array $db */
// Setup connection with database
require_once 'connection.php';
$id = $_GET['id'];
$query = "SELECT * FROM reservations WHERE id = '$id'";

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$reservations = [];

$row = mysqli_fetch_assoc($result);
$reservations[] = $row;

if (isset($_POST['delete_button'])) {
    // Delete a record from the table
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

<form action="" method="post" class="delete">
    <label for="delete_button">Delete</label>
    <input type="checkbox" name="delete_button" id="delete_button" value="DELETE">
    <input type="submit" value="DELETE">
</form>

</body>
</html>
