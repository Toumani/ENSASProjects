<?php
session_start();
if (!isset($_SESSION['id']))
	header('Location:/index.php');
require_once 'identifiants.php';

echo '<pre>';
print_r($_POST);
echo '</pre>';

try {
	if ($PDOException)
		throw new PDOException();
	for ($i = 1; $i < 1024; $i++) {
		// Checking datas intigrity
		if (!isset($_POST['us-story-' . $i]) || !isset($_POST['us-cost-' . $i]) || !isset($_POST['us-prio-' . $i]) || !isset($_POST['us-sprint-' . $i]))
			break;

		$story = htmlspecialchars($_POST['us-story-' . $i]);
		$cost = (int) ($_POST['us-cost-' . $i]);
		$prio = (int) ($_POST['us-prio-' . $i]);
		$sprintNo = (int) ($_POST['us-sprint-' . $i]);

		if ($story === '' || $cost < 1 || $prio < 1)
			continue;

		// Inserting data. I'm not checking the sprint integrity because MySQL server actually does check it
		// and in case of violatation it won't insert the row nor won't throw an exception
		$us_vrac = $database->prepare('SELECT MAX(no) max FROM user_story WHERE project_id = ?');
		$us_vrac->execute(Array($_SESSION['project-id']));
		$us = $us_vrac->fetch();

		$values = Array('text' => $story,
						'no' => (int) $us['max'] + 1,
						'prio' => $prio,
						'cost' => $cost,
						'project_id' => $_SESSION['project-id'],
						'sprint_no' => $sprintNo > 0 ? $sprintNo : null);
		$database->prepare('INSERT INTO user_story (text,no,priority,cost,project_id,sprint_no)
							VALUES (:text,:no,:prio,:cost,:project_id,:sprint_no)')
					->execute($values);

		$project_vrac = $database->prepare('SELECT folder_name FROM project WHERE project_id = ?');
		$project_vrac->execute(Array($_SESSION['project-id']));
		$project = $project_vrac->fetch();
		header('Location:scrum/' . $_SESSION['id'] . '/' . $project['folder_name'] . '/backlog.php');
	}
}
catch (PDOException $ex) {

}
catch (Exception $ex) {

}