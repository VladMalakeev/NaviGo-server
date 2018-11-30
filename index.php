<?php
session_start();
//общие настройки
header('Content-Type: text/html; charset=UTF8');

//отображения ошибок
ini_set('display_errors',1);
error_reporting(E_ALL);


//Включаем буферизацию содержимого
//ob_start();

$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;

$err = array();

//соединение с бд
require('config.php');

if($admin === false){

    include 'auth/form.php';
   // $content = ob_get_contents();
  //  ob_end_clean();

//Подключаем наш шаблон
    include 'auth/index.html';
}

if($admin === true) {
    //html файл личного кабинета
    include ('admin/functions.php');
    include('admin/index.html');

    //Выход из авторизации
    if(isset($_GET['exit']) == true){
        //Уничтожаем сессию
        session_destroy();

        //Делаем редирект
        header('Location:'. HOST);
        exit;
    }
}