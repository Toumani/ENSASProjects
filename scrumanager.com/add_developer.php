<?php
require_once 'identifiants.php';

try {
	// Checking datas integrity
	if ($PDOException)
		throw new PDOException();
	if (!isset($_POST['username']) || ! isset($_POST['email']) || ! isset($_POST['password']) || ! isset($_POST['password-confirm']))
		throw new Exception('Invalid form');
	if ($_POST['password'] != $_POST['password-confirm'])
		throw new Exception('Different passwords');

	$username = htmlspecialchars($_POST['username']);
	$email = htmlspecialchars($_POST['email']);
	$password = sha1($_POST['password']);
	
	// Fais une requête préparée féniasse !!
	$emailExists = $database->query('SELECT email FROM developer WHERE email = \'' . $email . '\'')->fetch();
	
	if ($emailExists)
		throw new Exception('Email exists');
	
	// Everything is okay
	// Let's insert the new user into the database
	$database->prepare('INSERT INTO developer (name,email,password) VALUES (:name,:email,:password)')
			->execute(Array('name' => $username,
							'email' => $email,
							'password' => $password));
	
	// Now let's create a folder with the required files for the new user
	$userId = $database->query('SELECT id FROM developer WHERE email = \''. $email . '\'')->fetch()['id'];
	mkdir(SITE_ROOT . 'scrum/' . $userId, 0777, true);
	mkdir(SITE_ROOT . 'scrum/' . $userId . '/dual', 0777, true);
	$indexContent =
'<?php
include \'../../index_template.php\';
';
$newProjectContent =
'
<?php
include \'../../new_project_template.php\';
';
	file_put_contents(SITE_ROOT . 'scrum/' . $userId . '/dual/index.php', $indexContent);
	file_put_contents(SITE_ROOT . 'scrum/' . $userId . '/dual/new_project.php', $newProjectContent);

	session_start();
	$_SESSION['id'] = $userId;
	$_SESSION['email'] = $email;
	$_SESSION['username'] = $username;
	
	header('Location:scrum/' . $userId . '/dual/index.php');
}
catch (PDOException $ex) {

}
catch (Exception $ex) {

}