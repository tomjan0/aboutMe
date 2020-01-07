<?php
//session_start();
require_once '../php/visits.php';
require_once 'comments-service.php';
$counter = getVisitsCounter();

$isLoggedIn = isset($_SESSION['username']);
if ($isLoggedIn) {
  $username = $_SESSION['username'];
}

if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
}

if (isset($_SESSION['username'])) {
    if (time() - $_SESSION['timestamp'] > 300) { //subtract new timestamp from the old one
        echo "<script>console.log('logout')</script>";
        session_destroy();
        unset($_SESSION['username']);
        $_SESSION['logged_in'] = false;
        header("Location: szyfrowanie.php"); //redirect to index.php
        exit;
    } else {
        $_SESSION['timestamp'] = time();
    }
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Kryptografia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
      window.MathJax = {
          tex: {
              tags: 'ams',
              inlineMath: [['$', '$'], ['\\(', '\\)']]
          },
          "HTML-CSS": {
              linebreaks: {automatic: true}
          },
          "SVG": {
              linebreaks: {automatic: true}
          }
      };
  </script>

  <script type="text/javascript" id="MathJax-script" async
          src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
  <link rel="Stylesheet" type="text/css" href="../css/styles.css"/>
  <link rel="Stylesheet" type="text/css" href="../css/crypto.css"/>
</head>
<body>
<nav class="navbar">
    <div class="user-actions">
        <a class="active" href="szyfrowanie.php">Schemat szyfrowania probabilistycznego</a>
        <a href="schemat-progowy.php">Schemat progowy dzielenia sekretu</a>
    </div>

    <?php if (isset($_SESSION['username'])) : ?>
        <div class="user-actions">
            <p class="user-welcome" id="demo">Czas sesji: 5m 0s</p>
            <p class="user-welcome">Witaj, <?php echo $_SESSION['username']; ?></p>
            <a class="user-menu-button" href="?logout='1'">Wyloguj</a>
        </div>
        <script>
            var timer = 300;
            var x = setInterval(function() {



                if (timer < 0) {
                    window.location.href = "szyfrowanie.php";
                    clearInterval(x);
                } else {
                    document.getElementById("demo").innerHTML ="Czas sesji: " + Math.floor(timer/60) + "m "+ timer%60 + "s ";
                    timer --;
                }
            }, 1000);
        </script>
    <?php else: ?>
        <div class="user-actions">
            <a class="user-menu-button" href="../auth/login.php">Zaloguj</a>
            <a class="user-menu-button" href="../auth/register.php">Zarejestruj</a>
        </div>
    <?php endif ?>
</nav>
<main>
  <article>
      <h1>Schemat Goldwasser-Micali szyfrowania probabilistycznego</h1>
      <section>
          <h2>Algorytm generowania kluczy</h2>
          <p>Schemat Goldwasser-Micali wymaga wygenerowania klucza prywatnego i publicznego za pomocą poniższego
              algorytmu
          </p>
          <ol>
              <li>Wybierz losowo dwie duże liczby pierwsze $p$ oraz $q$ (podobnego rozmiaru)</li>
              <li>Policz $n = pq$</li>
              <li>Wybierz $y \in {Z_n}$, takie, że $y$ jest nieresztą kwadratową modulo $n$ i symbol
                  Jakobiego
                  $\left(\frac{y}{n}\right) = 1$ (czyli $y$ jest pseudokwadratem modulo $n$)
              </li>
          </ol>
          <p>Klucz publiczny stanowi para $(n,y)$, zaś odpowiadający mu klucz prywatny to
              para
              $(p,q)$.
          </p>
      </section>
      <section>
          <h2>Algorytm szyfrowania</h2>
          <p>Chcąc zaszyfrować wiadomość $m$ przy użyciu klucza publicznego $(n, y)$ wykonaj kroki:
          </p>
          <ol>
              <li>Przedstaw $m$ w postaci łańcucha binarnego $m=m_1m_2...m_t$ długości $t$</li>
              <li class="code">
                  <p>For $i$ from $1$ to $t$ do</p>
                  <p class="shift">wybierz losowe $x \in \mathbf{Z_n^*}$</p>
                  <p class="shift">If $m_i = 1$ then set $c_i \leftarrow yx^2 \mod n$</p>
                  <p class="shift">Else set $c_i \leftarrow x^2 \mod n$</p>
              </li>
              <li>Kryptogram wiadomości $m$ stanowi $c = (c_1,c_2,...,c_t)$.</li>
          </ol>
      </section>
      <section>
          <h2>Algorytm deszyfrowania</h2>
          <p>Chcąc odzyskać wiadomość z kryptogramu $c$ przy użyciu klucza prywatnego $(p,q)$ wykonaj kroki:
          </p>
          <ol>
              <li>
                  <p>For $i$ from $1$ to $t$ do</p>
                  <p class="shift">policz symbol Legendre'a $e_i = \left( \frac{c_i}{p}\right)$ (algorytm
                      $(\ref{jacobi})$)</p>
                  <p class="shift">If $e_i = 1$ then set $m_i \leftarrow 0$</p>
                  <p class="shift">Else set $m_i \leftarrow 1$</p>
              </li>
              <li>Zdeszyfrowana wiadomość to $m = m_1m_2...m_t$.</li>
          </ol>
      </section>
      <section>
          <h2>Reszta/niereszta kwadartowa</h2>
          <section>
              <h3>Definicja</h3>
              <p>Niech $a \in \mathbf{Z_n}$. Mówimy, ze $a$ jest
                  <span>resztą kwadratową modulo $n$ (kwadartem modulo $n$)</span>, jeżeli
                  istnieje $x \in \mathbf{Z_n^*}$ takie, że $x^2 \equiv a \mod p$. Jeżeli takie $x$ nie istnieje,
                  to
                  wówczas
                  $a$ nazywamy <span>nieresztą
            kwadartową modulo $n$</span>.
                  Zbiór wszystkich reszt kwadartowych modulo $n$ oznaczamy $Q_n$, zaś zbiór wszystkich niereszt
                  kwadartowych
                  modulo $n$ oznaczamy $\bar{Q_n}$.
              </p>
          </section>
      </section>
      <section>
          <h2>Symbol Legendre'a</h2>
          <section>
              <h3>Definicja</h3>
              <p>Niech $p$ będzie nieparzystą liczbą pierwszą a $a$ liczbą całkowitą.
                  <span>Symbol Legendre'a</span>
                  $\left(\frac{a}{p}\right)$ jest zdefiniowany jako:
              <div>
                  $$
                  \left(\frac{a}{p}\right) = \left\{
                  \begin{array}{1}
                  0 & \textrm{ jeżeli } p|a\\
                  1 & \textrm{ jeżeli } a \in Q_p\\
                  -1 & \textrm{ jeżeli } a \in \bar{Q_p}
                  \end{array}
                  \right.
                  $$
              </div>
              </p>
          </section>
          <section>
              <h3>Własności</h3>
              <p>Niech $a,b \in \mathbb{Z}$, zaś $p$ to nieparzysta liczba pierwsza. Wówczas:</p>
              <ul>
                  <li>$\left(\frac{a}{p}\right) \equiv a^{\frac{p-1}{2}} \pmod p$</li>
                  <li>$\left(\frac{ab}{p}\right) = \left(\frac{a}{p}\right) \left(\frac{b}{p}\right)$</li>
                  <li>$a \equiv b \pmod{p} \Longrightarrow \left(\frac{a}{p}\right) = \left(\frac{b}{p}\right)$
                  </li>
                  <li>$\left(\frac{2}{p}\right) = (-1)^{\frac{(p^2 - 1)}{8}}$</li>
                  <li>Jeżeli $q$ jest nieparzystą liczbą pierwszą inną od $p$ to: $$\left(\frac{p}{q}\right) =
                      \left(\frac{q}{p}\right) \left(-1\right)^{\frac{(p-1)(q-1)}{4}}$$
                  </li>
              </ul>
          </section>
      </section>
      <section>
          <h2>Symbol Jacobiego</h2>
          <section>
              <h3>Definicja</h3>
              <p>Niech $n\geqslant3$ będzie liczbą nieparzystą a jej rozkład na czynniki pierwsze to
                  $n=p_{1}^{e_1}p_{2}^{e_2}...p_{k}^{e_k}$
                  . <span>Symbol Jacobiego</span> $\left(\frac{a}{n}\right)$ jest zdefiniowany jako:
              <div>
                  $$
                  \bigg(\frac{a}{n}\bigg) = \bigg(\frac{a}{p_1}\bigg)^{e_1} \bigg(\frac{a}{p_2}\bigg)^{e_2} \dots
                  \bigg(\frac{a}{p_k}\bigg)^{e_k}
                  $$
              </div>
              </p>
              <p>Jeżeli $n$ jest liczbą pierwszą, to symbol Jacobiego jest symbolem Legendre'a.</p>
          </section>
          <section>
              <h3>Własności</h3>
              <p>Niech $a,b \in \mathbb{Z}$, zaś $m,n \geqslant 3$ to nieparzyste liczby całkowite. Wówczas:</p>
              <ul>
                  <li>$\left(\frac{a}{n}\right) = 0,1, \textrm{albo} -1$</li>
                  <li>$\left(\frac{a}{n}\right) = 0 \iff gcd(a,n) \neq 1$</li>
                  <li>$\left(\frac{ab}{n}\right) = \left(\frac{a}{n}\right)\left(\frac{b}{n}\right)$</li>
                  <li>$\left(\frac{a}{mn}\right) = \left(\frac{a}{m}\right)\left(\frac{a}{n}\right)$</li>
                  <li>$a \equiv b \pmod{n} \Longrightarrow \left(\frac{a}{n}\right) = \left(\frac{b}{n}\right)$
                  </li>
                  <li>$\left(\frac{1}{n}\right) = 1$</li>
                  <li>$\left(\frac{-1}{n}\right) = (-1)^{\frac{n-1}{2}}$</li>
                  <li>$\left(\frac{2}{n}\right) = (-1)^{\frac{n^2-1}{8}}$</li>
                  <li>$\left(\frac{m}{n}\right) = \left(\frac{n}{m}\right) \left(-1\right)^{\frac{(m-1)(n-1)}{4}}$
                  </li>
              </ul>
              <p>Z własności symbolu Jacobiego wynika, że jeżeli $n$ nieparzyste oraz $a$ nieparzyste i w postaci
                  $a =
                  2^ea_1$, gdzie $a_1$ też nieparzyste to:
              <div>
                  $$
                  \bigg(\frac{a}{n}\bigg) = \bigg(\frac{2^e}{n}\bigg)\bigg(\frac{a_1}{n}\bigg)
                  = \bigg(\frac{2}{n}\bigg)^e\bigg(\frac{n \mod
                  a_1}{a_1}\bigg)\left(-1\right)^{\frac{(a_1-1)(n-1)}{4}}
                  $$
              </div>
              </p>
          </section>
      </section>
      <section>
          <h2>Algorytm obliczania symbolu Jacobiego (i Legendre'a)</h2>
          <p>Niech $n$ będzie nieparzystą liczbą całkowitą i $n \geqslant 3$ oraz niech $a$ będzie liczbą
              całkowitą
              taką, że
              $0 \leqslant a < n$. Wtedy algorytm obliczania symbolu Jacobiego ($Jacobi(a,n)$) przedstawia się
              następująco:
          </p>
          <ol>
              $$\begin{equation} Jacobi(a,n) \tag{*} \label{jacobi} \end{equation}$$
              <li>If $a = 0$ then return $0$</li>
              <li>If $a=1$ then return $1$</li>
              <li>Write $a=2^ea_1$, gdzie $a_1$ nieparzyste</li>
              <li>If $e$ parzyste set $s \leftarrow 1$<br>Else set $s \leftarrow 1$ if $n \equiv 1 \lor 7 \pmod
                  8$, or
                  set
                  $s \leftarrow -1$ if $n \equiv 3 \lor 5 \pmod 8$
              </li>
              <li>If $n \equiv 3 \pmod 4$ and $a_1 \equiv 3 \pmod 4$ then set $s \leftarrow -s$</li>
              <li>Set $n_1 \leftarrow n \mod a_1$</li>
              <li>If $a_1 = 1$ then return $s$<br>Else return $s\cdot Jacobi(n_1,a_1)$</li>
          </ol>
          <p>Czas działania tego algorytmu wynosi $\mathcal{O}((\lg{n})^2)$ operacji bitowych.</p>
      </section>
    <hr class="break-point">
    <section>
      <h2>Komentarze</h2>
      <div id="add-comment">
        <?php if (isset($_SESSION['username'])) : ?>
          <form method="post" action="schemat-progowy.php">
            <input type="hidden" name="article-id" value="1">
            <input type="hidden" name="author" value='<?php echo "$username"; ?>'/>
            <label>Treść komentarza
              <textarea name="comment" maxlength="150" rows="3" placeholder="Wpisz treść..." required></textarea>
            </label>
            <input type="submit" value="Dodaj" name="add-comment" class="btn-submit">
          </form>
        <?php else: ?>
          <p>Tylko zalogowani użytkownicy mogą dodawać komentarze</p>
          <a href="../auth/login.php" class="btn-sign">Zaloguj się</a>
        <?php endif ?>

      </div>
      <?php $articleId = 1; ?>
      <div id="comments">
        <?php include('comments.php'); ?>
      </div>
    </section>
  </article>
</main>
<footer>
    © 2020 Tomasz Janik | Licznik odwiedzin: <?php echo $counter ?>
</footer>
<?php include("../php/cookies.php"); ?>
</body>
</html>
