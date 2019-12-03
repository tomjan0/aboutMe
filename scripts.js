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

function loadForBigScreens() {
    if (window.matchMedia("(min-width: 1200px)").matches) {
        document.getElementById("img1").innerHTML = `<img class="addition" src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Gr%C3%B6tzsch_graph.svg/1200px-Gr%C3%B6tzsch_graph.svg.png" alt="Zdjęcie grafu"/>`
        document.getElementById("img2").innerHTML = `<img class="addition" src="https://www.android.com/static/2016/img/share/andy-sm.png" alt="Logo androida"/>`;
        document.getElementById("img3").innerHTML = `<img class="addition" src="https://images.onlinelabels.com/images/clip-art/gnokii/Coffee%20Beans%20Flat-277131.png" alt="Ziarna kawy"/>`;
        document.getElementById("img4").innerHTML = `<img class="addition" src="https://images.fineartamerica.com/images-medium-large-5/icon-of-acoustic-guitar-icon-black-contour-on-white-background-of-vector-illustration-elenvd.jpg" alt="Gitara"/>`;
        document.getElementById("img5").innerHTML = `<img class="addition" src="https://media.istockphoto.com/illustrations/japan-traditional-sumie-painting-fuji-mountain-sakura-sunset-japan-illustration-id937403308" alt="Obraz japońskiej góry Fuji"/>`;
        document.getElementById("img6").innerHTML = `<img class="addition" src="https://upload.wikimedia.org/wikipedia/commons/3/33/Climbing_pictogram.svg" alt="Piktogram wspinaczki">`;
    }
}
