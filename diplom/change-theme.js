function setTheme(theme) {
  // убираем старые классы и добавляем новый
  document.body.classList.remove("light-theme", "dark-theme");
  document.body.classList.add(theme);

  // сохраняем выбор
  localStorage.setItem("theme", theme);
}

function loadTheme() {
  // читаем сохранённую тему
  const savedTheme = localStorage.getItem("theme");

  // очищаем классы
  document.body.classList.remove("light-theme", "dark-theme");

  // применяем сохранённую или дефолтную
  if (savedTheme === "dark-theme" || savedTheme === "light-theme") {
    document.body.classList.add(savedTheme);
  } else {
    document.body.classList.add("light-theme"); // дефолтная тема
  }
}

document.addEventListener("DOMContentLoaded", () => {
  // при загрузке страницы подтягиваем тему
  loadTheme();

  // ищем кнопки переключения
  const sunBtn = document.getElementById("sun-btn");
  const moonBtn = document.getElementById("moon-btn");

  // если кнопки есть — вешаем обработчики
  if (sunBtn) {
    sunBtn.addEventListener("click", () => setTheme("light-theme"));
  }
  if (moonBtn) {
    moonBtn.addEventListener("click", () => setTheme("dark-theme"));
  }
});
