<?php
session_start();
if (!isset($_SESSION['id']))
	header('Location:index.php');

include '../../../identifiants.php';

$URL = $_SERVER['PHP_SELF'];
$start = strpos($URL,'/scrum/'); $end = strpos($URL,'backlog.php');

$masterId = substr($URL, $start + strlen('/scrum/'), 1);
$folderName = substr($URL, $start + strlen('/scrum/#/'), $end - ($start + strlen('/scrum/#/') + 1));

$project_vrac = $database->prepare('SELECT * FROM project WHERE folder_name = ? AND master_id = ?');
$project_vrac->execute(Array($folderName,$masterId));
$project = $project_vrac->fetch();
$_SESSION['project-id'] = $project['id'];

$us_vrac = $database->prepare('	SELECT 
								*
								FROM
								(SELECT 
									text,US.no no,cost,priority,status,color
								FROM
									user_story US
								LEFT JOIN sprint S ON S.no = US.sprint_no
								WHERE
									US.project_id = ?) AS duals
									LEFT JOIN
								color C ON duals.color = C.id;');
$us_vrac->execute(Array($_SESSION['project-id']));

$sprint_vrac = $database->prepare('SELECT * FROM sprint S LEFT JOIN color C ON S.color = C.id WHERE S.project_id = ?');
$sprint_vrac->execute(Array($_SESSION['project-id']));

$costDone_vrac = $database->prepare('SELECT SUM(cost) sum FROM user_story WHERE status = 1 AND project_id = ?');
$costDone_vrac->execute(Array($_SESSION['project-id']));
$costDone = (int) $costDone_vrac->fetch()['sum'];
$costTotal_vrac = $database->prepare('SELECT SUM(cost) sum FROM user_story WHERE project_id = ?');
$costTotal_vrac->execute(Array($_SESSION['project-id']));
$costTotal = (int) $costTotal_vrac->fetch()['sum'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $project['name'] ?> - Backlog | SCRUManager</title>
<?php include '../../../meta.php'; ?>
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 left_col">
					<div id="left-pane" class="left_col scroll-view">

						<div class="navbar nav_title" style="border: 0;">
							<a href="../<?php echo $_SESSION['id']; ?>/dual/index.php" class="site_title"><i class="fa fa-paw"></i><span> SCRUManager</span></a>
						</div>
						<div class="clearfix"></div>

						<!-- menu prile quick info -->
						<div class="profile">
							<div class="profile_pic">
								<img src="img.jpg" alt="..." class="img-circle profile_img">
							</div>
							<div class="profile_info">
								<span>Welcome,</span>
								<h2><?php echo $_SESSION['username']; ?></h2>
							</div>
						</div>
						<!-- /menu prile quick info -->

						<br />

						<!-- sidebar menu + footer button -->
<?php include '../../../sidebar.php'; ?>
						<!-- /sidebar menu + footer button -->

					</div>
				</div>

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
											<table class="table table-hover">
												<thead>
													<tr>
														<th>#</th>
														<th style="width: 75%">User story</th>
														<th>Cost</th>
														<th>Prio</th>
														<th>Edit</th>
														<th style="width: 10%">Status</th>
													</tr>
												</thead>
												<tbody>
<?php
while ($us = $us_vrac->fetch()) {
?>
													<tr style="background-color: rgba(<?php echo (($us['red'] ? $us['red'] : '255') . ',' . ($us['green'] ? $us['green'] : '255') . ',' . ($us['blue'] ? $us['blue'] : '255') . ',' . ($us['alpha'] ? $us['alpha'] : '0.5')); ?>)">
														<th scope="row"><?php echo $us['no']; ?></th>
														<td><?php echo $us['text']; ?></td>
														<td><?php echo $us['cost']; ?></td>
														<td><?php echo $us['priority']; ?></td>
														<td style="vertical-align: middle;">
															<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
														</td>
														<td><?php echo $us['status'] ? '<h3><span class="label label-success">Done!</span></h3>' : '<h4><span class="label label-default">On-going</span></h4>'; ?></td>
													</tr>
<?php
}
?>
												</tbody>
												<tfoot>
													<tr style="text-align: right;font-weight: bold;">
														<td></td>
														<td>TOTAL</td>
														<td><?php echo $costDone . '/' . $costTotal; ?></td>
														<td></td>
														<td></td>
														<td></td>
												</tfoot>
											</table>
											<br />
											<h4>Listing</h4>
											<ul style="list-style-type: none">
<?php
while ($sprint = $sprint_vrac->fetch()) {
?>
												<li>
													<i class="fa fa-circle" style="color: rgb(<?php echo (($sprint['red'] ? $sprint['red'] : '255') . ',' . ($sprint['green'] ? $sprint['green'] : '255') . ',' . ($sprint['blue'] ? $sprint['blue'] : '255')); ?>);"></i> Sprint <?php echo $sprint['no']; ?>
												</li>
<?php
}
?>
											</ul>
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
