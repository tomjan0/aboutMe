<head>
  <style>
    #cookies-box {
      width: 90%;
      position: fixed;
      top: 0;
      left: 5%;
      background-color: rgba(255, 255, 255, .85);
      border: 1px dashed var(--main-color-dark);
      border-radius: 5px;
      margin: 10px auto;
      padding: 10px;
      z-index: 3000;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-content: center;
    }

    .btn-cookie {
      margin: 10px 5px 5px;
      padding: 5px 10px;
      background-color: var(--main-color-theme);
      color: var(--main-color);
      border: none;
    }

    .btn-cookie:hover {
      opacity: .7;
      cursor: pointer;
    }

    @media (min-width: 768px) {
      #cookies-box {
        flex-direction: row;
      }

      #cookies-box > div {
        max-width: 80%;
      }

      .btn-cookie {
        margin-top: 5px;
      }
    }

  </style>
</head>
<body>
<?php if (!isset($_COOKIE['cookie-policy'])): ?>
  <div id="cookies-box">
    <div>
      <h3>Informacja o ciasteczkach</h3>
      <p>Informujemy, iż nasz serwis używa plików cookies w celu optymalizacji działania. Dowiedz się więcej o naszej
        <a href="../../../../WWW_projects/Kryptografia/php/cookie-policy.php">polityce cookies</a>. Korzystanie z
        serwisu
        jest jednoznaczne z akceptacją
        używania ciasteczek.</p>
    </div>
    <button class="btn-cookie" onclick="acceptCookies()">Zgadzam się</button>
  </div>
<?php endif; ?>
<script>
    function acceptCookies() {
        const d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = "cookie-policy=accepted;" + expires + ";path=/";
        const btn = document.getElementById("cookies-box");
        btn.style.display = "none";
    }
</script>
</body>