<?php

function getUserIpAddr()
{
  if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
  }
  $client = @$_SERVER['HTTP_CLIENT_IP'];
  $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
  $remote = $_SERVER['REMOTE_ADDR'];

  if (filter_var($client, FILTER_VALIDATE_IP)) {
    $ip = $client;
  } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
    $ip = $forward;
  } else {
    $ip = $remote;
  }

  return $ip;
}

function getVisitsCounter()
{
  $servername = "localhost";
  $username = "kryptoUser";
  $password = "kryptohaslo";
  $dbname = "kryptografia";
  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
//$date =  '2019-12-22';
  $date = date("Y-m-d");
//$ip = '134.2.1.230';
  $ip = getUserIpAddr();
  $sql = "SELECT * FROM `wizyty` WHERE IP='" . $ip . "'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    $sql = "INSERT INTO `wizyty` (`IP`, `OstatniaWizyta`) VALUES ('" . $ip . "','" . $date . "')";
    $conn->query($sql);
  } else if ($result->fetch_assoc()['OstatniaWizyta'] != $date) {
    $sql = "UPDATE `wizyty` SET OstatniaWizyta = '" . $date . "', LiczbaWizyt = LiczbaWizyt+1 WHERE IP='" . $ip . "'";
    $conn->query($sql);
  }


  $sql = "SELECT SUM(LiczbaWizyt) AS SumaWizyt FROM `wizyty`";
  $result = $conn->query($sql);

  $counter = $result->fetch_assoc()['SumaWizyt'];

  $conn->close();
  return $counter;
}

