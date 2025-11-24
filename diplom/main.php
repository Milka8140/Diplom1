<?php
session_start();

// если нажата кнопка "Выйти"
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: main.php"); // обновляем главную
    exit();
}

$isAuth = isset($_SESSION["user"]);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Главная</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="main.css" />
</head>
<body>
<header class="header">
  <a href="main.php" class="logo-link">MyFilm</a>

  <div class="header-right">
    <?php if ($isAuth): ?>
       <a href="profile.php" class="nav-link">Профиль</a>
      <a href="notes.php" class="nav-link">Заметки</a>
      <a href="statistic.php" class="nav-link">Статистика</a>
      <a href="main.php?logout=1" class="nav-link">Выйти</a>
    <?php else: ?>
      <a href="auth.php" class="login-link">Войти</a>
    <?php endif; ?>

    <!-- кнопки темы -->
<button class="theme-btn sun" id="sun-btn">
      <!-- SVG солнца -->
      <svg width="24" height="24" viewBox="0 0 24 24" 
           fill="none" stroke="currentColor" stroke-width="2" 
           xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="5"/>
        <line x1="12" y1="1" x2="12" y2="4"/>
        <line x1="12" y1="20" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="6.34" y2="6.34"/>
        <line x1="17.66" y1="17.66" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="4" y2="12"/>
        <line x1="20" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="6.34" y2="17.66"/>
        <line x1="17.66" y1="6.34" x2="19.78" y2="4.22"/>
      </svg>
    </button>

    <button class="theme-btn moon" id="moon-btn">
      <!-- SVG луны -->
      <svg width="24" height="24" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
      </svg>
    </button>
  </div>
  <div class="header-line"></div>
</header>

 <main class="main">
    <h1 class="main-title">
      MyFilm — место, где остаются твоё кино и твои чувства
    </h1>

    <p class="main-subtitle">
      В мире, где фильмы быстро сменяют друг друга, важно сохранить то, что остаётся внутри нас. 
      MyFilm — это не просто список просмотренного, а твой личный дневник впечатлений. 
      Здесь ты можешь фиксировать эмоции, возвращаться к ним спустя время <br>
      и видеть, как меняется твой взгляд на кино. <br><br>
      Мы верим, что каждый фильм — это история, которая живёт в тебе. 
      MyFilm помогает собрать эти истории в одном месте, чтобы они никогда не потерялись.
    </p>

    <a href="search.php" class="btn-start">Начать</a>

    <!-- 📖 Как это работает -->
    <section class="how-it-works">
      <h2 class="how-title">Как это работает</h2>
      <div class="steps">
        <div class="step"><span class="step-word">Введи</span> название фильма</div>
        <div class="step"><span class="step-word">Открой</span> карточку</div>
        <div class="step"><span class="step-word">Напиши</span> комментарий</div>
        <div class="step"><span class="step-word">Сохрани</span> заметку</div>
      </div>
    </section>
  </main>

<script src="change-theme.js"></script>
</body>
</html>
