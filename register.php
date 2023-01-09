<?php
if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "connection.php";

    // Get form data
    $name = mysqli_escape_string($db, $_POST['name']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = mysqli_escape_string($db, $_POST['password']);
    $password_repeat = mysqli_escape_string($db, $_POST['password_repeat']);

    // Server-side validation
    $errors = [];
    if ($name == '') {
        $errors['name'] = 'Vul een naam in.';
    }
    if ($email == '') {
        $errors['email'] = 'Vul een email in.';
    }
    if ($password == '') {
        $errors['password'] = 'Vul een wachtwoord in.';
    }
    if ($password === $password_repeat) {
        // proceed with creating the account
    } else {
        $errors['password_repeat'] = 'Wachtwoorden zijn niet gelijk.';
    }

    // If data valid
    if (empty($errors)) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon"
          href="https://www.parrotfarm.nl/wp-content/uploads/2021/04/cropped-D31F63E9-3F1D-443D-841A-1ABC6EE3B6A3-32x32.png"
          sizes="32x32">
    <title>Registreren</title>
</head>
<body>
<a class="back" href="index.php">Terug naar beginpagina</a>
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
        <label for="password">Wachtwoord:</label>
        <input type="password" name="password" id="password" placeholder="Wachtwoord" autocomplete="off">
        <button class="eye-btn" type="button" onclick="togglePasswordVisibility()"><i class="fa fa-eye"></i>
        </button>
        <script>
            function togglePasswordVisibility() {
                let passwordInput = document.getElementById("password");
                let eyeBtn = document.querySelector(".eye-btn i");
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeBtn.classList.add("fa-eye-slash");
                    eyeBtn.classList.remove("fa-eye");
                } else {
                    passwordInput.type = "password";
                    eyeBtn.classList.add("fa-eye");
                    eyeBtn.classList.remove("fa-eye-slash");
                }
            }
        </script>
    </section>
    <section class="formfield">
        <label for="password_repeat">Wachtwoord:</label>
        <input type="password" name="password_repeat" id="password_repeat" placeholder="Herhaal Wachtwoord"
               autocomplete="off">
        <button class="eye-btn-2" type="button" onclick="togglePasswordVisibility2()"><i class="fa fa-eye"></i>
        </button>
        <script>
            function togglePasswordVisibility2() {
                let passwordInput2 = document.getElementById("password_repeat");
                let eyeBtn2 = document.querySelector(".eye-btn-2 i");
                if (passwordInput2.type === "password") {
                    passwordInput2.type = "text";
                    eyeBtn2.classList.add("fa-eye-slash");
                    eyeBtn2.classList.remove("fa-eye");
                } else {
                    passwordInput2.type = "password";
                    eyeBtn2.classList.add("fa-eye");
                    eyeBtn2.classList.remove("fa-eye-slash");
                }
            }
        </script>
    </section>
    <p class="error"><?= $errors['password'] ?? '' ?></p>
    <p class="error"><?= $errors['password_repeat'] ?? '' ?></p>
    <section class="formfield">
        <button type="submit" name="submit">REGISTREER</button>
    </section>
</form>
<footer>
    <p>Gemaakt door Roel Hoogendoorn als project voor school.</p>
</footer>
</body>
</html>
