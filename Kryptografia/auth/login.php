<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zaloguj się</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" type="text/css" href="../css/styles.css"/>
    <link rel="Stylesheet" type="text/css" href="../css/auth.css"/>
</head>
<body>

<main class="main">
    <h1>Zaloguj się</h1>
    <form method="post" action="login.php">
        <label>Email
            <input type="email" name="email" maxlength="50">
        </label>
        <label>Hasło
            <input type="password" name="password" minlength="8" maxlength="50">
        </label>
        <?php include('errors.php'); ?>
        <input class="btn-submit" type="submit" value="Zaloguj się" name="login">
    </form>
    <p class="redirect">Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
</main>
<footer>
    © 2020 Tomasz Janik
</footer>
<?php include("../php/cookies.php"); ?>
</body>
</html>

