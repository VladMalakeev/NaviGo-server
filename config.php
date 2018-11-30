<?php
//Адрес базы данных
 define('DBSERVER','localhost');

//Логин БД
 define('DBUSER','vlad2020');

 //Пароль БД
 define('DBPASSWORD','vlad202052091920');

 //БД
 define('DATABASE','vlad2020');

//Errors
 define('ERROR_CONNECT','Немогу соеденится с БД');

 //Errors
 define('NO_DB_SELECT','Данная БД отсутствует на сервере');

//Адрес хоста сайта
 define('HOST','https://'. $_SERVER['HTTP_HOST'] .'/');
 
 //Подключение к базе данных mySQL с помощью PDO
try {
   $db = new PDO('mysql:host=localhost;dbname='.DATABASE, DBUSER, DBPASSWORD, array(
    	PDO::ATTR_PERSISTENT => true
    	));
} catch (PDOException $e) {
    print "Ошибка соединеия!: " . $e->getMessage() . "<br/>";
    die();
}
 