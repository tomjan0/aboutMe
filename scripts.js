let opend = false;


function load() {
    document.write(`
<button class="project-button" id="pButton" onclick="onClick()">Rozwiń</button>
<div id="projects-container" hidden="true">
<a class="project-href" href="checkers.html">
        <article class="project-desc checkers">
        <h2>ChineseCheckers</h2>
        <p>
        Dwuosobowy projekt z kursu Technologie programowania.
         Aplikacja do gry w Trylmę napisana w Javie z
    bilibloteką JavaFx. Działa w technologi klient-serwer.
    </p>

    </article>
    </a>
    <a class="project-href" href="sztosik.html">
        <article class="project-desc sztosik">
        <h2>SZTOSiK</h2>
        <p>
        Dwuosobowy projekt z kursu Bazy Danych.
         Aplikacja do zarządzania ocenami szkolnymi oraz planem
    lekcji
    napisana w Javie z
    wykorzystaniem JDBC i MySQL do obsługi bazy danych.
    </p>
    </article>
    </a>
    <a class="project-href" href="mobiles.html">
        <article class="project-desc mobile">
        <h2>MobileOrderSystem</h2>
        <p>
        Trzyosbowy projekt z kursu Aplikacje Mobilne.
         Aplikacja mobilna do zarządzania wypożyczeniami
    sprzętu
    napisana w języku Kotlin, z wykorzystaniem Android SDK.
    </p>
    </article>
    </a>
</div>`);
}

function onClick() {
    const button = document.getElementById("pButton");
    const cont = document.getElementById("projects-container");
    if (!opend) {
        button.innerText = "Zwiń";
        cont.hidden = false;
        opend = true;
    } else {
        button.innerText = "Rozwiń";
        cont.hidden = true;
        opend = false;
    }

}
