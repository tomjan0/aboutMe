<?php
session_start();
require_once '../php/visits.php';
$counter = getVisitsCounter();

$isLoggedIn = isset($_SESSION['username']);
if ($isLoggedIn) {
  $username = $_SESSION['username'];
}

if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header("location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Zakamarki Kryptografii | Polityka ciasteczek</title>
  <link rel="Shortcut icon" href="../img/icon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="Stylesheet" type="text/css" href="../css/styles.css"/>
  <link rel="Stylesheet" type="text/css" href="../css/desktop.css"/>
  <style>
    .all-center > div {
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
      padding: 15px;
    }

    #cookie-policy {
      max-width: 1000px;
      list-style-position: inside;
    }

    #cookie-policy li {
      margin-bottom: 7px;
      text-align: justify;
    }

    #cookie-policy ul {
      list-style-position: inside;
      margin-left: 20px;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .all-center > div > h1 {
      margin: 15px auto;
    }

    .btn-cookie {
      margin: 15px auto 25px;
      padding: 10px 20px;
      background-color: var(--main-color-theme);
      color: var(--main-color);
      border: none;
    }

    .btn-cookie:hover {
      opacity: .7;
      cursor: pointer;
    }

    .accept {
      background-color: var(--main-color-theme);
    }

    .not-accept {
      background-color: rgba(0, 0, 0, .4);
    }
  </style>
</head>
<body>
<nav>
  <div id="visit-counter">
    <p>Odwiedzono <span><?php echo $counter ?></span> <?php echo($counter == 1 ? "raz" : "razy") ?>
    </p>
  </div>
  <div id="user-menu">
    <?php if ($isLoggedIn) : ?>
      <p>Zalogowano jako <span><?php echo $username; ?></span></p>
      <a class="user-menu-button" href="../index.php?logout='1'">Wyloguj</a>
    <?php else: ?>
      <a class="user-menu-button" href="../auth/login.php">Zaloguj</a>
      <a class="user-menu-button" href="../auth/register.php">Zarejestruj</a>
    <?php endif ?>
  </div>
</nav>
<header class="all-center photo-background">
  <a class="all-center" href="../index.php">
    <h1>Zakamarki Kryptografii</h1>
    <h2>Aleksandra Malinowska</h2>
  </a>
</header>
<main class="all-center">
  <div>
    <h1>Polityka ciasteczek</h1>
    <ol id="cookie-policy">
      <li>Serwis zbiera w sposób automatyczny tylko informacje zawarte w plikach cookies.</li>
      <li>Pliki (cookies) są plikami tekstowymi, które przechowywane są w urządzeniu końcowym użytkownika serwisu.
        Przeznaczone są do korzystania ze stron serwisu. Przede wszystkim zawierają nazwę strony internetowej swojego
        pochodzenia, swój unikalny numer, czas przechowywania na urządzeniu końcowym.
      </li>
      <li>Operator serwisu "Zakamarki kryptografii" jest podmiotem zamieszczającym na urządzeniu końcowym swojego
        użytkownika pliki cookies oraz mającym do nich dostęp.
      </li>
      <li>Operator serwisu wykorzystuje pliki (cookies) w celu:
        <ul>
          <li>możliwości logowania do serwisu</li>
          <li>utrzymania logowania użytkownika na każdej kolejnej stronie serwisu</li>
        </ul>
      </li>
      <li>Serwis stosuje dwa zasadnicze rodzaje plików (cookies) - sesyjne i stałe. Pliki sesyjne są tymczasowe,
        przechowuje
        się je do momentu opuszczenia strony serwisu (poprzez wejście na inną stronę, wylogowanie lub wyłączenie
        przeglądarki). Pliki stałe przechowywane są w urządzeniu końcowym użytkownika do czasu ich usunięcia przez
        użytkownika
        lub przez czas wynikający z ich ustawień.
      </li>
      <li>Użytkownik może w każdej chwili dokonać zmiany ustawień swojej przeglądarki, aby zablokować obsługę plików
        (cookies) lub każdorazowo uzyskiwać informacje o ich umieszczeniu w swoim urządzeniu. Inne dostępne opcje można
        sprawdzić w ustawieniach swojej przeglądarki internetowej. Należy pamiętać, że większość przeglądarek domyślnie
        jest
        ustawione na akceptację zapisu plików (cookies)w urządzeniu końcowym.
      </li>
      <li>Operator Serwisu informuje, że zmiany ustawień w przeglądarce internetowej użytkownika mogą ograniczyć dostęp
        do
        niektórych funkcji strony internetowej serwisu.
      </li>
      <li>Pliki (cookies) z których korzysta serwis (zamieszczane w urządzeniu końcowym użytkownika) mogą być
        udostępnione
        jego partnerom oraz współpracującym z nim reklamodawcą.
      </li>
      <li>Informacje dotyczące ustawień przeglądarek internetowych dostępne są w jej menu (pomoc) lub na stronie jej
        producenta.
      </li>
      <li>Bardziej szczegółowe informacje na temat plików (cookies) dostępne są na stronie <a
          href="https://ciasteczka.org.pl">ciasteczka.org.pl</a></li>
    </ol>
    <?php if (!isset($_COOKIE['cookie-policy'])) : ?>
      <button class="btn-cookie accept" onclick="setCookie('cookie-policy', 'accepted', 365)">Zgadzam się</button>
    <?php else: ?>
      <button class="btn-cookie not-accept" onclick="deleteCookie('cookie-policy')">Wycofaj zgodę</button>
    <?php endif; ?>
  </div>
</main>
<footer class="all-center">
  <p>2020 &copy; AM. Wszystkie prawa zastrzeżone. <a href="cookie-policy.php">Polityka cookies</a></p>
</footer>
<script>
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        window.location.reload();
    }

    function deleteCookie(cname) {
        document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        window.location.reload();
    }
</script>
<?php include("cookies.php"); ?>
</body>
</html>
