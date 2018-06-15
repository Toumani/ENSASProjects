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

?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<title>Project list | SCRUManager</title>
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
													<tr>
														<th scope="row">1</th>
														<td>Mark</td>
														<td>Otto</td>
														<td>@mdo</td>
														<td>
															<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
														</td>
														<td>@mdo</td>
													</tr>
													<tr>
														<th scope="row">2</th>
														<td>Jacob</td>
														<td>Thornton</td>
														<td>@fat</td>
														<td>
															<span class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </span>
														</td>
														<td>@mdo</td>
													</tr>
													<tr>
														<th scope="row">3</th>
														<td>Larry</td>
														<td>the Bird</td>
														<td>@twitter</td>
														<td>
															<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
														</td>
														<td>@mdo</td>
													</tr>
												</tbody>
												<tfoot>
													<tr>
														<td></td>
														<td>TOTAL</td>
														<td>5/23</td>
												</tfoot>
											</table>
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
															<td><input type="text" name="us-name-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
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
															<td><input type="text" name="us-name-' + userStoryCount + '" class="form-control col-md-7 col-xs-12"/></td>\n\
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
