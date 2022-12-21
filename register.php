<?php
if(isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "connection.php";

    // Get form data
    $name = mysqli_escape_string($db, $_POST['name']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    $errors = [];
    if($name == '') {
        $errors['name'] = 'Please fill in your name.';
    }
    if($email == '') {
        $errors['email'] = 'Please fill in your email.';
    }
    if($password == '') {
        $errors['password'] = 'Please fill in your password.';
    }

    // If data valid
    if(empty($errors)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash($password, PASSWORD_DEFAULT);

        // store the new user in the database.
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        $result = mysqli_query($db, $query);

        if ($result) {
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Registreren</title>
</head>
<body>
<form action="" method="post" class="create">
    <h2>Registreer</h2>
    <section class="formfield">
        <label for="naam">Naam:</label>
        <input type="text" name="name" id="naam" placeholder="Voornaam Achternaam" value="<?= $name ?? '' ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $errors['name'] ?? '' ?></p>
    <section class="formfield">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="name@mail.com" value="<?= $email ?? '' ?>"
               autocomplete="off">
    </section>
    <p class="error"><?= $errors['email'] ?? '' ?></p>
    <section class="formfield">
        <label for="password">Wachtwoord::</label>
        <input type="password" name="password" id="password" placeholder="****"
               autocomplete="off">
    </section>
    <p class="error"><?= $errors['password'] ?? '' ?></p>
    <section class="formfield">
        <button type="submit" name="submit">Submit</button>
    </section>
</form>
<a href="login.php">Log In</a>
</body>
</html>
