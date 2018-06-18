<?php
session_start();
if (!isset($_SESSION['id']))
	header('Location:/index.php');

include '../../../identifiants.php';

$_SESSION['project-selected'] = false;

$memberedProject_vrac = $database->query('SELECT id,name,folder_name,created,status,owner_id,master_id
											FROM project P
											INNER JOIN project_developer PD
											ON P.id = PD.project_id
											WHERE PD.developer_id = ' . $_SESSION['id']);

$masteredProject_vrac = $database->query('SELECT id,name,folder_name,created,status,owner_id,master_id
											FROM project
											WHERE master_id = ' . $_SESSION['id']);
// $
function projectProgress($project_id, $database) {
	// TODO : return the progess in percentage of the project id given in parameter
	// To do so just sum up the cost of completed user stories and take it ratio with the tatal cost of the backlog
	$costDone_vrac = $database->prepare('SELECT SUM(cost) sum FROM user_story WHERE status = 1 AND project_id = ?');
	$costDone_vrac->execute(Array($project_id));
	$costDone = (int) $costDone_vrac->fetch()['sum'];
	$costTotal_vrac = $database->prepare('SELECT SUM(cost) sum FROM user_story WHERE project_id = ?');
	$costTotal_vrac->execute(Array($project_id));
	$costTotal = (int) $costTotal_vrac->fetch()['sum'];
	
	return (int) ($costDone / $costTotal * 100);
}


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
								<h3>Projects <small>all projects</small></h3>
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
						<div class="clearfix"></div>

						<!-- /Project where user is SCRUM master -->
						<div class="row">
							<div class="col-md-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Projects you're mastering</h2>
										<ul class="nav navbar-right panel_toolbox">
											<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
											</li>
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
												<ul class="dropdown-menu" role="menu">
													<li><a href="#">Hide completed projects</a>
													</li>
													<li><a href="#">Hide aborded projects</a>
													</li>
												</ul>
											</li>
											<li><a class="close-link"><i class="fa fa-close"></i></a>
											</li>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">

										<p>Listing projects you created where you are the SCRUM master</p>

										<!-- start project list -->
										<table class="table table-striped projects">
											<thead>
												<tr>
													<th style="width: 1%">#</th>
													<th style="width: 20%">Project Name</th>
													<th>Team Members</th>
													<th>Project Progress</th>
													<th>Status</th>
													<th style="width: 20%">Action</th>
												</tr>
											</thead>
											<tbody>
<?php
$i = 0;
while ($masteredProject = $masteredProject_vrac->fetch()) {
	$i++;
	$projectProgress = projectProgress($masteredProject['id'], $database);
	$member_vrac = $database->query('	SELECT id,name
										FROM project_developer PD
										INNER JOIN developer D
										ON PD.developer_id = D.id
										WHERE PD.project_id = ' . $masteredProject['id']);
?>
												<tr>
													<td><?php echo $i; ?></td>
													<td>
														<a href="../<?php echo $masteredProject['folder_name']; ?>/overview.php"><?php echo $masteredProject['name']; ?></a>
														<br />
														<small>Created <?php echo $masteredProject['created']; ?></small>
													</td>
													<td>
														<ul class="list-inline">
<?php
	while ($member = $member_vrac->fetch()) {
?>
															<li>
																<a href="../../../<?php echo $member['id']; ?>/dual/profile.php"><img src="../../<?php echo $member['id']; ?>/dual/img.jpg" class="avatar" alt="<?php echo $member['name']; ?>"></a>
															</li>
<?php
	}
?>
														</ul>
													</td>
													<td class="project_progress">
														<div class="progress progress_sm">
															<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $projectProgress; ?>"></div>
														</div>
														<small><?php echo $projectProgress; ?>% Complete</small>
													</td>
													<td>
														<button type="button" class="btn btn-success btn-xs">Fine</button>
													</td>
													<td>
														<a href="../<?php echo $masteredProject['folder_name']; ?>/overview.php" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
														<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
													</td>
												</tr>
<?php
}
?>
											</tbody>
										</table>
										<!-- end project list -->
										<a href="new_project.php" class="btn btn-primary" style="float: right;margin: 0 3% 10px 0;">New project</a>
									</div>
								</div>
							</div>
						</div>
						<!-- /Project where user is SCRUM master -->

						<!-- Project where user is a simple member -->
						<div class="row">
							<div class="col-md-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Projects you're participating</h2>
										<ul class="nav navbar-right panel_toolbox">
											<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
											</li>
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
												<ul class="dropdown-menu" role="menu">
													<li><a href="#">Hide completed projects</a>
													</li>
													<li><a href="#">Hide aborded projects</a>
													</li>
												</ul>
											</li>
											<li><a class="close-link"><i class="fa fa-close"></i></a>
											</li>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">

										<p>Listing projects you were invited to</p>

										<!-- start project list -->
										<table class="table table-striped projects">
											<thead>
												<tr>
													<th style="width: 1%">#</th>
													<th style="width: 20%">Project Name</th>
													<th>Team Members</th>
													<th>Project Progress</th>
													<th>Status</th>
													<th style="width: 20%">Action</th>
												</tr>
											</thead>
											<tbody>
<?php
$i = 0;
while ($memberedProject = $memberedProject_vrac->fetch()) {
	$i++;
	$projectProgress = projectProgress($memberedProject['id']);
	$member_vrac = $database->query('	SELECT id,name
										FROM project_developer PD
										INNER JOIN developer D
										ON PD.developer_id = D.id
										WHERE PD.project_id = ' . $memberedProject['id']);
?>
												<tr>
													<td><?php echo $i; ?></td>
													<td>
														<a><?php echo $memberedProject['name']; ?></a>
														<br />
														<small>Created <?php echo $memberedProject['created']; ?></small>
													</td>
													<td>
														<ul class="list-inline">
<?php
	while ($member = $member_vrac->fetch()) {
?>
															<li>
																<a href="../../<?php echo $member['id']; ?>/dual/profile.php"><img src="../../<?php echo $member['id']; ?>/dual/img.jpg" class="avatar" alt="<?php echo $member['name']; ?>"></a>
															</li>
<?php
	}
?>
														</ul>
													</td>
													<td class="project_progress">
														<div class="progress progress_sm">
															<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $projectProgress; ?>"></div>
														</div>
														<small><?php echo $projectProgress; ?>% Complete</small>
													</td>
													<td>
														<button type="button" class="btn btn-success btn-xs">Fine</button>
													</td>
													<td>
														<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
														<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
													</td>
												</tr>
<?php
}
?>
											</tbody>
										</table>
										<!-- end project list -->

									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Project where user is a simple member -->

					<!-- footer content -->
<?php include '../../../footer.php'; ?>
					<!-- /footer content -->

				</div>
				<!-- /page content -->
			</div>

		</div>

<?php include '../../../custom_notifications.php'; ?>

<?php include '../../../scripts.php'; ?>

	</body>

</html>
