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
        header("Location: schemat-progowy.php"); //redirect to index.php
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
<!--    <meta http-equiv="refresh" content="5;url=schemat-progowy.php?logout='1'">-->
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
        <a href="szyfrowanie.php">Schemat szyfrowania probabilistycznego</a>
        <a class="active" href="schemat-progowy.php">Schemat progowy dzielenia sekretu</a>
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
                    window.location.href = "schemat-progowy.php";
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
<main class="all-center">
    <article>
        <h1>Schemat progowy $(t,n)$ dzielenia sekretu Shamira</h1>
        <section>
            <h2>Algorytm</h2>
            <h3>Cel</h3>
            <p>Zaufana Trzecia Strona $T$ ma sekret $S \geqslant 0$, który chce podzielić pomiędzy $n$
                uczestników
                tak, aby dowolnych $t$ spośród nich mogło sekret odtworzyć.
            </p>
            <h3>Faza inicjalizacji</h3>
            <ol>
                <li>$T$ wybiera liczbę pierwszą $p > max(S,n)$ i definiuje $a_0 = S$</li>
                <li>$T$ wybiera losowo i niezależnie $t-1$ współczynników $a_1,\dots,a_{t-1} \in \mathbf{Z_p}$</li>
                <li>$T$ definiuje wielomian nad $\mathbf{Z_p}$: $$f(x) = a_0 + \sum_{j=0}^{t-1} a_jx^j$$</li>
                <li>Dla $1 \leqslant i \leqslant n$ Zaufana Trzecia Strona $T$ wybiera losowo $x_i \in
                    \mathbf{Z_p}$,
                    oblicza:
                    $$
                    \begin{equation} \tag{*} \label{s}
                    S_i = f(x_i) \mod p
                    \end{equation}
                    $$
                    i bezpiecznie przekazuje parę $(x_i,S_i)$ użytkownikowy $P_i$
                </li>
            </ol>
            <h3>Faza łączenia udziałów w sekret</h3>
            <p>Dowolna grupa $t$ lub więcej użytkowników łączy swoje udziały - $t$ różnych punktów $(x_i,S_i)$
                wielomianu $f$ i dzięki interpolacji Lagrange'a odzyskuje sekret $S=a_0=f(0)$ </p>
        </section>
        <section>
            <h2>Interpolacja Lagrange'a</h2>
            <p>Mając dane $t$ różnych punktów $(x_i,y_i)$ nieznanego wielomianu $f$ stopnia mniejszego od $t$ możemy
                policzyć jego współczynniki korzystając ze wzoru:
                <span>
          $$
          \begin{equation}
            f(x) = \sum_{i=1}^{t} \left(y_i \prod_{1 \leqslant j \leqslant t,\, j \neq i} \frac{x-x_j}{x_i-x_j} \right)
          \mod p
          \label{sh}
          \end{equation}
          $$
        </span>
            </p>
            <p>W schemacie Shamira, aby odzyskać sekret $S$, użytkownicy nie muszą znać całego wielomianu $f$.
                Wstawiając
                do wzoru na interpolację Lagrange'a $x=0$, dostajemy wersję uproszczoną, ale wystarczającą aby
                policzyć
                sekret $S=f(0)$:
                <span>
          $$
          \begin{equation}
            f(x) = \sum_{i=1}^{t} \left(y_i \prod_{1 \leqslant j \leqslant t,\, j \neq i} \frac{x_j}{x_j-x_i} \right)
          \mod p
          \label{sh2}
          \end{equation}
          $$
        </span>
            </p>
        </section>
        <section>
            <h2>Przykład</h2>
            <p>Podzielmy sekret na 4 części. Każdy udziałowiec otrzymuje jedną część
                sekretu i ustalmy, że do odtworzenia go potrzeba 3 części
            </p>
            <p>Ustalamy $S=23, n=4$ oraz $t=3$, zatem rozważany jest schemat progowy Shamira (3,4).</p>
            <p>Niech $p=29$, $a_1 = 12$, $a_2=9$ oraz $x_1=3, x_2=4, x_3=5, x_4=6$. Zatem wielomian
                przyjmuje
                postać $$f(x) = 23 + 12x + 9x^2.$$
            </p>
            <p>Następnie obliczamy poszczególne części sekretu:
                <span>
        $$
        \begin{array}{1}
        S_1 = f(3) \mod 29 = 24, \\
        S_2 = f(4) \mod 29 = 12, \\
        S_3 = f(5) \mod 29 = 18, \\
        S_4 = f(6) \mod 29 = 13,
        \end{array}
        $$
      </span>
            </p>
            <p>Obliczamy sekret na podstawie wiedzy udziałowców $P_1, P_2$ i $P_4$.
                <span>
        $$
        f(0) = 24 \cdot \frac{4}{4-3} \cdot \frac{6}{6-3} + 12 \cdot \frac{3}{3-4} \cdot \frac{6}{6-4} +
                  13 \cdot \frac{3}{3-6}\cdot \frac{4}{4-6} = 110
        $$
      </span>
                na mocy wzoru ($\ref{sh2}$) $$S = 110 \mod 29 = 23.$$
            </p>
        </section>
        <hr class="break-point">
        <section>
            <h2>Komentarze</h2>
            <div id="add-comment">
                <?php if (isset($_SESSION['username'])) : ?>
                    <form method="post" action="schemat-progowy.php">
                        <input type="hidden" name="article-id" value="2">
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
            <?php $articleId = 2; ?>
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
