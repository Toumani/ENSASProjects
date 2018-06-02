<?php
require_once 'identifiants.php';

try {
	if ($PDOException)
		throw new PDOException();
	if (!isset($_POST['username']) || ! isset($_POST['email']) || ! isset($_POST['password']) || ! isset($_POST['password-confirm']))
		throw new Exception('Invalid form');
	if ($_POST['password'] != $_POST['password-confirm'])
		throw new Exception('Different passwords');

	$username = htmlspecialchars($_POST['username']);
	$email = htmlspecialchars($_POST['email']);
	$password = sha1($_POST['password']);
	
	$emailExists = $database->query('SELECT email FROM developer WHERE email = \'' . $email . '\'')->fetch();
	
	if ($emailExists)
		throw new Exception('Email exists');
	
	$database->prepare('INSERT INTO developer (name,email,password) VALUES (:name,:email,:password)')
			->execute(Array('name' => $username,
							'email' => $email,
							'password' => $password));
	
	// header('Location:index.php');
}
catch (PDOException $ex) {

}
catch (Exception $ex) {

}

/*
{
    // D'abord, je me connecte à la base de données.
    mysql_connect(host, DBusername, DBpassword);
	mysql_select_db(dbname);
	
	$database->prepare();

    // Je mets aussi certaines sécurités ici…
    $passe = mysql_real_escape_string(htmlspecialchars($_POST['password']));
    $passe2 = mysql_real_escape_string(htmlspecialchars($_POST['password-confirm']));
    if($passe == $passe2) {
        $pseudo = mysql_real_escape_string(htmlspecialchars($_POST['username']));
        $email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
        // Je vais crypter le mot de passe.
        $passe = sha1($passe);

        mysql_query("INSERT INTO developer (name, email, password) VALUES('$pseudo', '$passe', '$email')");
    }
    
    else {
        echo 'Les deux mots de passe que vous avez rentrés ne correspondent pas…';
    }
}
*/