<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Zakamarki Kryptografii | Zarejestruj się</title>
  <link rel="Shortcut icon" href="../img/icon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="Stylesheet" type="text/css" href="../css/styles.css"/>
  <link rel="Stylesheet" type="text/css" href="../css/auth.css"/>
</head>
<body>
<main class="main">
  <h1>Zarejestruj się</h1>
  <form method="post" action="register.php">
    <label>Nazwa użytkownika
      <input type="text" name="username" maxlength="50">
    </label>
    <label>E-mail
      <input type="email" name="email" maxlength="50">
    </label>
    <label>Hasło
      <input type="password" name="password" minlength="8" maxlength="50">
    </label>
    <?php include('errors.php'); ?>
    <input class="btn-submit" type="submit" value="Rozpocznij" name="register">
  </form>
  <p class="redirect">Masz już konto? <a href="login.php">Zaloguj się</a></p>
</main>
<footer>
    © 2020 Tomasz Janik
</footer>
<?php include("../php/cookies.php"); ?>
</body>
</html>

