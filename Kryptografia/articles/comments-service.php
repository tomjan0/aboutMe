<?php
session_start();
$errors = array();
$servername = "localhost";
$username = "kryptoUser";
$password = "kryptohaslo";
$dbname = "kryptografia";
$db = new mysqli($servername, $username, $password, $dbname);

// REGISTER USER
if (isset($_POST['add-comment'])) {
  $articleId = mysqli_real_escape_string($db, $_POST['article-id']);
  $comment = mysqli_real_escape_string($db, $_POST['comment']);
  $author = mysqli_real_escape_string($db, $_POST['author']);
  $date = date("Y-m-d H:i:s");

  if (empty($comment)) {
    array_push($errors, "Wpisz treść komentarza");
  }


  if (count($errors) == 0) {

    $query = "INSERT INTO komentarze (artykul_id, autor, data, tresc) 
  			  VALUES('$articleId', '$author', '$date', '$comment')";
    mysqli_query($db, $query);
    $sql = "SELECT nazwa_pliku FROM artykuly WHERE ID=" . $articleId . " LIMIT 1";
    $result = $db->query($sql);
    $path = $result->fetch_assoc()['nazwa_pliku'];
    header("Location: " . $path);
  }
}