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
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    $errors = [];
    if ($email == '') {
        $errors['email'] = 'Vul een email in.';
    }
    if ($password == '') {
        $errors['password'] = 'Vul een wachtwoord in.';
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

            $user = array_map(function ($innerArray) {
                return array_map('htmlentities', $innerArray);
            }, $user);

            // Check if the provided password matches the stored password in the database
            if (password_verify($password, $user['password'])) {
                $login = true;

                // Store the user in the session
                $_SESSION['loggedInUser'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'admin' => $user['admin'],
                ];

                // Redirect to secure page
            } else {
                //error incorrect log in
                $errors['loginFailed'] = 'Er ging iets mis, probeer het nogmaals.';
            }
        } else {
            //error incorrect log in
            $errors['loginFailed'] = 'Er ging iet mis, probeer het nogmaals.';
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
    <link rel="icon"
          href="https://www.parrotfarm.nl/wp-content/uploads/2021/04/cropped-D31F63E9-3F1D-443D-841A-1ABC6EE3B6A3-32x32.png"
          sizes="32x32">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Log in</title>
</head>
<body>
<a class="back" href="index.php">Terug naar beginpagina</a>
<?php if ($login) {
    header('Location: home.php');
} else { ?>

    <form action="" method="post" class="create">
        <h2>Log In</h2>
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
        <p class="error"><?= $errors['password'] ?? '' ?></p>
        <p class="error"><?= $errors['loginFailed'] ?? '' ?></p>
        <section class="formfield">
            <button type="submit" name="submit">LOG IN</button>
        </section>
    </form>
<?php } ?>
<footer>
    <p>Gemaakt door Roel Hoogendoorn als project voor school.</p>
</footer>
</body>
</html>


