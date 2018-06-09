<?php
session_start();
?>
<pre>
<?php
	// $_POST['project-owner'] = 'Linus Torvald - linustorvald@linux.com';
	// $_POST['co-worker-1'] = 'Toumani - toumani49@gmail.com';
	// $_POST['co-worker-2'] = 'Nitrate - nitrate@gmail.com';
	print_r($_POST);
	$_SESSION['id'] = 1;
	print_r($_SESSION);
?>
</pre>
<?php
require_once 'identifiants.php';

function folderName($title) {
	$specialChars = array('#', '%', '&', '{', '}', '\\', '<', '>', '*', '?', '/', '$', '!', '\'', '"', ':', '@');
	$title = str_replace($specialChars, '', $title);
	$title = trim($title, ' ');
	return strtolower(str_replace(' ', '-', $title));
}

try {
	// Checking data integrity
	if (!isset($_POST['project-name']) || !isset($_POST['co-worker-1']) || !isset($_POST['project-owner']))
		throw new Exception('Invalid form');
	
	$projectName = htmlspecialchars($_POST['project-name']);
	$projectOwner = htmlspecialchars(explode(' - ', $_POST['project-owner'])[1]);
	$folderName = folderName($projectName);
	
	$project_vrac = $database->prepare('SELECT id FROM project WHERE folder_name = ? AND master_id = ?');
	$project_vrac->execute(Array($folderName, $_SESSION['id']));
	$projectExists = $project_vrac->fetch();

	$moa_vrac = $database->prepare('SELECT id FROM moa WHERE email = ?');
	$moa_vrac->execute(Array($projectOwner));
	$moaExists = $moa_vrac->fetch();

	if ($projectExists) {
		header('Location:scrum/' . $_SESSION['id'] . '/dual/new_project.php?err=project-exists');
	}
	else if (!$moaExists) {
		header('Location:scrum/' . $_SESSION['id'] . '/dual/new_project.php?err=moa-does-not-exist');
	}
	else {
		// We can insert the project into the database
		// But first for the project we create the required files
		mkdir(SITE_ROOT . 'scrum/' . $_SESSION['id'] . '/' . $folderName, 0777, true);
		// /*!*\ Project files has to be created \*!*/
		
		$values = array (	'project_name' => $projectName,
							'folder_name' => $folderName,
							'owner_id' => $moaExists['id'],
							'master_id' => $_SESSION['id']);
echo '<pre>'; print_r($values); echo '</pre>';
		$database->prepare('INSERT INTO project (name,folder_name,created,status,owner_id,master_id)
							VALUES (:project_name,:folder_name,NOW(),1,:owner_id,:master_id)')
							->execute($values);
		$projectId = $database->query('SELECT MAX(id) id FROM project WHERE master_id = ' . $_SESSION['id'])->fetch()['id'];
		

		for ($i = 1; $i <= 1024; $i++) {
			if ($coWorker = isset($_POST['co-worker-' . $i])) { // I actually meant an affectation
				$developerEmail = htmlspecialchars(explode(' - ', $_POST['co-worker-' . $i])[1]);

				$developer_vrac = $database->prepare('SELECT id FROM developer WHERE email = ?');
				$developer_vrac->execute(Array($developerEmail));
				$developerExists = $developer_vrac->fetch();
				if ($developerExists) {
					$values = array('project_id' => $projectId,
									'developer_id' => $developerExists['id']);
echo '<pre>'; print_r($values); echo '</pre>';
					$database->prepare('INSERT INTO project_developer (joined,project_id,developer_id)
										VALUES (NOW(),:project_id,:developer_id)')
										->execute($values);
				}
			}
			else
				break;
		}
	}
}
catch (Exception $ex) {

}