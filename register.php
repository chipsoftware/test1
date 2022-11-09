<?php

	function cleanPost($str) {
        $str = trim($str);
        $str = preg_replace("/[^\x20-\xFF\\r\\n]/","",@strval($str));
		$str = preg_replace('/<[^>]*>/', '', $str);
        $str = htmlspecialchars($str, ENT_QUOTES);
		return $str;
	}
	
	function searchUser($email) {
		global $users;
		
		$result = false;
		
		foreach($users as $user) {
			if ($user['email'] == $email) {
				$result = true;		
				break;
			}	
		}
	
		return $result;
	}	

	$users = array(
		array(
			'id' => '1',
			'name' => 'Василий',
			'soname' => 'Васильев',
			'email' => 'vasya@ukr.net'
		),
		array(
			'id' => '2',
			'name' => 'Иван',
			'soname' => 'Иванов',
			'email' => 'ivan@ukr.net'
		),
		array(
			'id' => '3',
			'name' => 'Петр',
			'soname' => 'Петров',
			'email' => 'petro@ukr.net'
		),
		array(
			'id' => '4',
			'name' => 'Сергей',
			'soname' => 'Сергеев',
			'email' => 'sirozha@ukr.net'
		),
		array(
			'id' => '5',
			'name' => 'Андрей',
			'soname' => 'Андреев',
			'email' => 'andrey@ukr.net'
		)		
	);

	$name = isset($_POST['name']) ? (string)cleanPost($_POST['name']) : '';
	$soname = isset($_POST['soname']) ? (string)cleanPost($_POST['soname']) : '';
	$email = isset($_POST['email']) ? (string)cleanPost($_POST['email']) : '';
	$password1 = isset($_POST['password1']) ? (string)cleanPost($_POST['password1']) : '';
	$password2 = isset($_POST['password2']) ? (string)cleanPost($_POST['password2']) : '';

	$errors = array();
	
	$log_file = 'validate.log';
	$log_text = date('d.m.Y H:i:s') . ' - E-mail: ' . $email . ', Пароль / Подтверждение: ' . $password1 . ' / ' . $password2 . ' - Результат проверки: ';
	
	$result = array(
		'timestamp' => time()
	);
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = 'Введен некорректный E-mail!';		
	}	
		
	if ($password1 != $password2) {
		$errors[] = 'Пароль и его подверждение не совпадают!';		
	} else {
		if ($password1 == '') {
			$errors[] = 'Пароль не может быть пустым!';		
		}
	}
	
	if (searchUser($email)) {
		$errors[] = 'Пользователь с таким E-mail уже зарегистрирован!';		
	}	
	
	if (count($errors) == 0) {
		$result['status'] = 'OK'; 
		$log_text .= 'OK';
	} else {
		$result['status'] = 'Error'; 
		$result['error'] = $errors; 
		$log_text .= implode(', ', $errors);
	}		

	file_put_contents($log_file, $log_text."\r\n", FILE_APPEND);

	echo json_encode($result);

?>