<?php
session_start();

// проверка авторизации
$isAuth = isset($_SESSION['user']); 

$apiKey = "181ad0c3ffb886ed7f79fa9230a91080";

// проверяем наличие id
if (!isset($_GET['id'])) {
    die("Фильм не найден");
}

$movieId = (int)$_GET['id'];

// Запрос к TMDB API по id фильма
$url = "https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ru-RU&append_to_response=credits,videos";
$response = file_get_contents($url);

if ($response === false) {
    die("Ошибка при получении данных о фильме");
}

$data = json_decode($response, true);

if (!$data) {
    die("Фильм не найден");
}

// основные данные
$title = $data['title'] ?? 'Без названия';
$year = isset($data['release_date']) ? substr($data['release_date'],0,4) : 'Дата неизвестна';
$duration = $data['runtime'] ?? '—';
$director = '';
if (!empty($data['credits']['crew'])) {
    foreach ($data['credits']['crew'] as $crew) {
        if ($crew['job'] === 'Director') {
            $director = $crew['name'];
            break;
        }
    }
}
$genre = !empty($data['genres']) ? implode(", ", array_column($data['genres'], 'name')) : '—';
$description = $data['overview'] ?? 'Описание отсутствует';
$poster = $data['poster_path'] ? "https://image.tmdb.org/t/p/w300".$data['poster_path'] : "https://via.placeholder.com/240x360";
$rating = $data['vote_average'] ?? '—';

// актёры
$actors = [];
if (!empty($data['credits']['cast'])) {
    $actors = array_slice($data['credits']['cast'], 0, 10); // первые 10 актёров
}

// трейлер
$trailerUrl = '';
if (!empty($data['videos']['results'])) {
    foreach ($data['videos']['results'] as $video) {
        if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
            $trailerUrl = "https://www.youtube.com/embed/".$video['key'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Информация о фильме</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="details.css" />
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

    <!-- 🌙☀️ Кнопки темы -->
    <button class="theme-btn sun" id="sun-btn" aria-label="Светлая тема">
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

    <button class="theme-btn moon" id="moon-btn" aria-label="Тёмная тема">
      <svg width="24" height="24" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
      </svg>
    </button>
  </div>

  <div class="header-line"></div>
</header>


<div class="header-space"></div>

<main class="cards-scroll">
  <div class="results">
    <!-- 🎬 Основной блок фильма -->
    <div class="movie-details">
      <div class="movie-poster">
        <img src="<?= htmlspecialchars($poster) ?>" alt="Постер фильма">
      </div>
      <div class="movie-info">
        <h1 class="movie-title"><?= htmlspecialchars($title) ?></h1>
        <p class="movie-year"><?= htmlspecialchars($year) ?></p>
        <p class="movie-duration">Длительность: <?= htmlspecialchars($duration) ?> минут</p>
        <p class="movie-director">Режиссер: <?= htmlspecialchars($director) ?></p>
        <p class="movie-genre">Жанр: <?= htmlspecialchars($genre) ?></p>
        <p class="movie-rating-inline">⭐ <?= htmlspecialchars($rating) ?> / 10</p>

        <h2 class="info-subtitle">Описание</h2>
        <p class="movie-description"><?= nl2br(htmlspecialchars($description)) ?></p>

        <?php if ($isAuth): ?>
          <div class="movie-actions">
            <a href="create-note.php?movie_id=<?= $movieId ?>" class="btn-note">Создать заметку</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- 👥 Актёры -->
    <section class="movie-cast">
      <h2 class="info-subtitle">Актёры</h2>
      <div class="cast-grid">
        <?php foreach ($actors as $actor): ?>
          <div class="actor-card">
            <img src="<?= $actor['profile_path'] 
                          ? 'https://image.tmdb.org/t/p/w200'.$actor['profile_path'] 
                          : 'https://via.placeholder.com/200x300' ?>" 
                 alt="<?= htmlspecialchars($actor['name']) ?>" 
                 class="actor-photo">
            <p class="actor-name"><?= htmlspecialchars($actor['name']) ?></p>
            <p class="actor-role">Роль: <?= htmlspecialchars($actor['character']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</main>


<script src="change-theme.js"></script>
</body>
</html>