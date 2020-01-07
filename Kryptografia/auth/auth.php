<?php
session_start();
$errors = array();
$servername = "localhost";
$username = "kryptoUser";
$password = "kryptohaslo";
$dbname = "kryptografia";
$db = new mysqli($servername, $username, $password, $dbname);

// REGISTER USER
if (isset($_POST['register'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Nazwa użytkownika jest wymagana");
  }
  if (empty($email)) {
    array_push($errors, "Adres e-mail jest wymagany");
  }
  if (empty($password)) {
    array_push($errors, "Hasło jest wymagane");
  }

  $user_check_query = "SELECT * FROM uzytkownicy WHERE nazwa='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) { // if user exists
    if ($user['nazwa'] === $username) {
      array_push($errors, "Użytkownik o podanej nazwie już istnieje");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Adres e-mail jest już w użyciu");
    }
  }

  if (count($errors) == 0) {
    $password = md5($password);//encrypt the password before saving in the database

    $query = "INSERT INTO uzytkownicy (nazwa, email, haslo) 
  			  VALUES('$username', '$email', '$password')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "Zalogowano";
    $_SESSION['timestamp'] = time();
    header('location: ../index.php');
    exit();
  }
}

// LOGIN USER
if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($email)) {
    array_push($errors, "Podaj adres email");
  }
  if (empty($password)) {
    array_push($errors, "Podaj hasło");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM uzytkownicy WHERE email='$email' AND haslo='$password' LIMIT 1";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
      $_SESSION['username'] = $user['nazwa'];
      $_SESSION['success'] = "Zalogowano";
      $_SESSION['timestamp'] = time();
      header('location: ../index.php');
      exit();
    } else {
      array_push($errors, "Nieprawidłowa nazwa użytkownika lub hasło");
    }
  }
}

