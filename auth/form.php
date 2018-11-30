<?php
if(isset($_POST['submit']))
{
	 $login = $_POST['login'];
	 $password = $_POST['password'];
	//Проверяем на пустоту
	if(empty($login))
		$err[] = 'Не введен Логин';
	
	if(empty($password))
		$err[] = 'Не введен Пароль';
	
	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		login($login, $password);
	}
 }
 
 function showErrorMessage($data)
 {
	 if(is_array($data)){
		 foreach($data as $value){
			 echo $value."</br>";
		 }
	 }
	 else echo $data;
	 
 }
 
 function login($login, $password)
 {
	 //подключаемся к бд
     global $db;
	 $sql = "SELECT password
				FROM admin
				WHERE login = :login";
     $stmt = $db->prepare($sql);
     $stmt->bindValue(':login', $login, PDO::PARAM_STR);
     $stmt->execute();
       $result = $stmt->fetch(PDO::FETCH_LAZY);
	if(!empty($result['password'])){
		if($result['password'] == $password){
			$_SESSION['admin'] = true;
				
				//Сбрасываем параметры
				header('Location:'. HOST );
				exit;
			
		}
		else showErrorMessage("Не верный пароль");
	}
	else showErrorMessage("Логин не существует");
 
 }