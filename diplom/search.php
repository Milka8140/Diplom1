<?php
session_start();

// проверка авторизации
$isAuth = isset($_SESSION['user']); 

$apiKey = "181ad0c3ffb886ed7f79fa9230a91080";

$query = isset($_GET['query']) ? urlencode($_GET['query']) : "";
$movies = [];

if ($query) {
    $url = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&language=ru-RU&query={$query}";
    $response = file_get_contents($url);
    if ($response !== false) {
        $data = json_decode($response, true);
        $movies = $data['results'] ?? [];
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Поиск фильмов</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="search.css" />
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
      <!-- 🔒 Гость -->
      <a href="auth.php" class="login-link">Войти</a>
    <?php endif; ?>

    <!-- кнопки темы -->
    <button class="theme-btn sun" id="sun-btn">
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
      <svg width="24" height="24" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
      </svg>
    </button>
  </div>

  <div class="header-line"></div>
</header>

<main class="main">
  <!-- 🔎 Форма поиска -->
  <form method="get" class="search-bar">
    <input type="text" name="query" placeholder="Введите название фильма..." 
           class="search-input" value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
    <button type="submit" class="btn-search">Искать</button>
  </form>

  <!-- 📦 Область прокрутки карточек -->
  <div class="cards-scroll">
    <div class="results">
      <?php if ($query && empty($movies)): ?>
        <p class="no-results">По вашему запросу ничего не найдено.</p>
      <?php else: ?>
        <?php foreach ($movies as $movie): ?>
          <div class="movie-card-horizontal">
            <img src="<?= $movie['poster_path'] 
                          ? 'https://image.tmdb.org/t/p/w200'.$movie['poster_path'] 
                          : 'https://via.placeholder.com/120x180' ?>" 
                 alt="Постер фильма" class="movie-poster-horizontal">

            <div class="movie-info-horizontal">
              <h2 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h2>
              <p class="movie-year"><?= $movie['release_date'] ?: "Дата неизвестна" ?></p>
              <p class="movie-rating">⭐ <?= $movie['vote_average'] ?>/10</p>
              <p class="movie-overview">
                <?= mb_strimwidth(htmlspecialchars($movie['overview']), 0, 120, "...") ?>
              </p>
              <div class="movie-actions">
                <a href="details.php?id=<?= $movie['id'] ?>" class="btn-details">Подробнее</a>
                <?php if ($isAuth): ?>
                  <a href="create-note.php?id=<?= $movie['id'] ?>" class="btn-note">Создать заметку</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</main>


<script src="change-theme.js"></script>
</body>
</html>
