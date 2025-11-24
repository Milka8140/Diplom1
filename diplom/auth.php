<?php
require_once "connect.php";
session_start();

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $pass  = trim($_POST["pass"]);

    if ($email === "" || $pass === "") {
        $error = "Заполните все поля!";
    } else {
        // ищем пользователя по email
        $stmt = $mysqli->prepare("SELECT id, login, email, pass FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($pass, $user["pass"])) {
                $_SESSION["user"] = $user["login"];
                // ✅ мгновенный переход на главную
                header("Location: main.php");
                exit();
            } else {
                $error = "Неверный пароль!";
            }
        } else {
            $error = "Пользователь не найден!";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Авторизация</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="auth.css" />
  </head>
<body>
  <!-- Переключатель темы -->
  <div class="theme-switcher">
    <button class="theme-btn" id="sun-btn" aria-label="Светлая тема">
      <!-- SVG солнца -->
      <svg width="24" height="24" viewBox="0 0 24 24" 
           fill="none" stroke="currentColor" stroke-width="2" 
           xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="5" fill="none" stroke="currentColor"/>
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

    <button class="theme-btn" id="moon-btn" aria-label="Тёмная тема">
      <!-- SVG луны -->
      <svg width="24" height="24" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
      </svg>
    </button>
  </div>

  <!-- Форма авторизации -->


  <!-- Форма авторизации -->
  <div class="screen">
    <div class="card">
      <div class="logo">MyFilm</div>
      <div class="title">Вход в аккаунт</div>

      <!-- ✅ теперь это форма -->
      <form action="#" method="POST" class="form-login">
        <label class="label-email" for="email">Электронная почта</label>
        <input type="email" id="email" name="email" class="input-email" placeholder="Введите почту" required />

        <label class="label-pass" for="pass">Пароль</label>
        <input type="password" id="pass" name="pass" class="input-pass" placeholder="Введите пароль" required />

        <button type="submit" class="btn-login">Войти</button>
      </form>

         <?php if ($error): ?>
        <p class="register-text" style="color:red;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <p class="register-text">
        <span class="no-account">Нет аккаунта?</span>
        <a href="reg.php" class="link-register">Зарегистрируйтесь</a>
      </p>
    </div>
  </div>

  <script src="change-theme.js"></script>
</body>
</html>