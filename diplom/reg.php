<?php
require_once "connect.php";

$success = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"]);
    $email = trim($_POST["email"]);
    $pass  = trim($_POST["pass"]);

    if ($login === "" || $email === "" || $pass === "") {
        $error = "Заполните все поля!";
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM user WHERE login = ? OR email = ?");
        $stmt->bind_param("ss", $login, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Такой логин или почта уже существуют!";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO user (login, email, pass) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $login, $email, $hash);

            if ($stmt->execute()) {
                $success = true; // ✅ регистрация прошла
            } else {
                $error = "Ошибка при регистрации: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Регистрация</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="reg.css" />
</head>
<body class="light-theme">

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

  <div class="screen">
    <div class="card">
      <div class="logo">MyFilm</div>
      <div class="title">Создание аккаунта</div>

      <!-- ✅ Форма -->
        <form action="reg.php" method="POST" class="form-register">
        <label for="login" class="label-login">Логин</label>
        <input type="text" id="login" name="login" class="input-login" placeholder="Введите логин" required />

        <label for="email" class="label-email">Электронная почта</label>
        <input type="email" id="email" name="email" class="input-email" placeholder="Введите почту" required />

        <label for="pass" class="label-pass">Пароль</label>
        <input type="password" id="pass" name="pass" class="input-pass" placeholder="Введите пароль" required />

        <button type="submit" class="btn-register">Создать аккаунт</button>
      </form>

      <?php if ($success): ?>
        <!-- ✅ Сообщение при успешной регистрации -->
        <p class="register-text">
          Регистрация успешна! Теперь вы можете <a href="auth.php" class="link-login">войти</a>.
        </p>
      <?php else: ?>
        <!-- Стандартный текст -->
        <p class="register-text">
          <span class="no-account">Уже есть аккаунт?</span>
          <a href="auth.php" class="link-login">Войти</a>
        </p>
      <?php endif; ?>

      <?php if ($error): ?>
        <!-- Ошибка -->
        <p class="register-text" style="color:red;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
    </div>
  </div>

  <script src="change-theme.js"></script>
</body>
</html>
