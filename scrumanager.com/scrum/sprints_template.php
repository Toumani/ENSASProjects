<?php
session_start();
if (!isset($_SESSION['id']))
	header('Location:/index.php');

require_once '../../../identifiants.php';

require_once '../../../classes/Project.php';

$_SESSION['project-selected'] = true;

$URL = $_SERVER['PHP_SELF'];
$start = strpos($URL,'/scrum/'); $end = strpos($URL,'sprints.php');

$masterId = substr($URL, $start + strlen('/scrum/'), 1);
$folderName = substr($URL, $start + strlen('/scrum/#/'), $end - ($start + strlen('/scrum/#/') + 1));

$project_vrac = $database->prepare('SELECT * FROM project WHERE folder_name = ? AND master_id = ?');
$project_vrac->execute(Array($folderName,$masterId));
$project = $project_vrac->fetch();
$_SESSION['project-id'] = $project['id'];

$Project = new Project($project['id']);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $project['name'] ?> - Sprints | SCRUManager</title>
<?php include '../../../meta.php'; ?>
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">

				<!-- menu prile + sidebar menu + footer button -->
<?php include '../../../sidebar.php'; ?>
				<!-- /menu prile + sidebar menu + footer button -->

				<!-- top navigation -->
<?php include '../../../top_navigation.php'; ?>
				<!-- /top navigation -->

				<!-- page content -->
				<div class="right_col" role="main">

					<div class="">
						<div class="page-title">
							<div class="title_left">
								<h3><?php echo $project['name']; ?> <small> backlog</small></h3>
							</div>

							<div class="title_right">
								<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Search for...">
										<span class="input-group-btn">
											<button class="btn btn-default" type="button">Go!</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="x_panel">
									<div class="x_title">
									<h2>New Partner Contracts Consultancy</h2>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div class="x_content">
<?php $Project->printSprints(); ?>
											<br />
<?php $Project->listSprints(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<form method="POST" action="../../../add_user_story.php">
							<div class="col-md-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Add user story</h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="x_content">
												<table class="table table-hover">
													<thead>
														<tr>
															<th style="width: 75%">User story</th>
															<th>Cost</th>
															<th>Prio</th>
															<th style="width: 10%">Sprint no</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
												<input type="submit" class="btn btn-primary" style="float: right;margin: 0 3% 10px 0;" value="Submit" />
												<span id="add-us-btn" class="btn btn-default" style="float: right;margin: 0 3% 10px 0;">Add user stories</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<!-- footer content -->
<?php include '../../../footer.php'; ?>
					<!-- /footer content -->

				</div>
				<!-- /page content -->
			</div>

		</div>

<?php include '../../../custom_notifications.php'; ?>

<?php include '../../../scripts.php'; ?>

<script>
$(document).ready(function() {
	var userStoryCount = 1;
	var sprintOptionHTML =
'<?php
$sprint_vrac = $database->prepare('SELECT no FROM sprint WHERE project_id = ?');
$sprint_vrac->execute(Array($project['id']));
while ($sprint = $sprint_vrac->fetch()) {
?>																	<option value="<?php echo $sprint['no']; ?>"><?php echo $sprint['no']; ?></option>\n\
<?php
}
?>'
	var userStoryHTML =
'														<tr>\n\
															<td><input type="text" name="us-story-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
															<td><input type="number" min="1" name="us-cost-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
															<td><input type="number" min="1" name="us-prio-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
															<td>\n\
																<select class="form-control" name="us-sprint-' + userStoryCount + '">\n\
																	<option value="-1"> -- </option>\n\
' + sprintOptionHTML +
'																</select>\n\
															</td>\n\
														</tr>\n\
';
	$('form tbody').append(userStoryHTML);
	console.log(userStoryHTML);

	$('#add-us-btn').on('click', function() {
		userStoryCount++;
		userStoryHTML =
'														<tr>\n\
															<td><input type="text" name="us-story-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
															<td><input type="number" min="1" name="us-cost-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
															<td><input type="number" min="1" name="us-prio-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
															<td>\n\
																<select class="form-control" name="us-sprint-' + userStoryCount + '">\n\
																	<option value="-1"> -- </option>\n\
' + sprintOptionHTML +
'																</select>\n\
															</td>\n\
														</tr>\n\
';
		$('form tbody').append(userStoryHTML);;
	});
})
</script>

	</body>

</html>
