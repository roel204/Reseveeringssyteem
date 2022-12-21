<?php
session_start();

$login = false;
// Is user logged in?
if (isset($_SESSION['loggedInUser'])) {
    $login = true;
}

if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "connection.php";

    // Get form data
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    $errors = [];
    if ($email == '') {
        $errors['email'] = 'Please fill in your email.';
    }
    if ($password == '') {
        $errors['password'] = 'Please fill in your password.';
    }

    // If data valid
    if (empty($errors)) {
        // SELECT the user from the database, based on the email address.
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($db, $query);

        // check if the user exists
        if (mysqli_num_rows($result) == 1) {
            // Get user data from result
            $user = mysqli_fetch_assoc($result);

            // Check if the provided password matches the stored password in the database
            if (password_verify($password, $user['password'])) {
                $login = true;

                // Store the user in the session
                $_SESSION['loggedInUser'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                ];

                // Redirect to secure page
            } else {
                //error incorrect log in
                $errors['loginFailed'] = 'The provided credentials do not match.';
            }
        } else {
            //error incorrect log in
            $errors['loginFailed'] = 'The provided credentials do not match.';
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
    <title>Log in</title>
</head>
<body>
        <h2>Log in</h2>
        <?php if ($login) { ?>
            <p>Je bent ingelogd!</p>
            <p><a href="logout.php">Uitloggen</a> / <a href="index.php">Naar reseveering overzicht</a></p>
        <?php } else { ?>

            <form action="" method="post" class="create">
                <h2>Log In</h2>
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
                <p class="error"><?= $errors['loginFailed'] ?? '' ?></p>
                <section class="formfield">
                    <button type="submit" name="submit">Submit</button>
                </section>
            </form>
        <?php } ?>
        <a href="register.php">Registreer.</a>
</body>
</html>


