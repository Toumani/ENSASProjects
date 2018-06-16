<?php
require_once 'identifiants.php';

try {
	if ($PDOException)
		throw new PDOException();
	if (!isset($_POST['email']) || ! isset($_POST['password']))
		throw new Exception('Invalid form');
	
	$email = htmlspecialchars($_POST['email']);
	$password = sha1($_POST['password']);

	$developer_vrac = $database->prepare('SELECT id,name,email,profile FROM developer WHERE email = ? AND password = ?');
	$developer_vrac->execute(array($email,$password));
	$isLoginValid = $developer_vrac->fetch();

	if (!$isLoginValid)
		throw new Exception('Email or password incorrect');
	
	session_start();
	
	$_SESSION['id'] = $isLoginValid['id'];
	$_SESSION['email'] = $isLoginValid['email'];
	$_SESSION['username'] = $isLoginValid['name'];
	$_SESSION['profile'] = $isLoginValid['profile'] == '' ? '../../../images/img.jpg' : $isLoginValid['profile'];
	$_SESSION['project-selected'] = false;

	header('Location:scrum/' . $_SESSION['id'] . '/dual/index.php');
}
catch (PDOException $ex) {

}
catch (Exception $ex) {
	print_r($_POST);
}