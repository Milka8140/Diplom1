<?php

$name = "root";                                             // мой логин для входа в phpmyadmin
$pass = "";                                                 // мой пароль для входа в phpmyadmin
$name_bd = "diplom";                                       // название используемой в курсаче бд
 $mysqli = new mysqli("localhost", $name, $pass, $name_bd); // обьект типа mysqli для соединения с бд используя локальный хост и данные выше
 if ($mysqli -> connect_errno){ // проверка подключились ли к бд если нет 
    echo "Fail"; // выведется это
    exit(); // и программа завершается
 }

?>
<!-- 
mysqli - это расширение MySQLi (MySQL improved), 
которое предоставляет API для взаимодействия с базой данных MySQL. 
Он является одним из двух доступных расширений PHP для работы с MySQL
-->