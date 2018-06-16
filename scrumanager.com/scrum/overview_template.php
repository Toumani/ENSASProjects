<?php
session_start();
if (!isset($_SESSION['id']))
	header('Location:index.php');

require_once '../../../identifiants.php';
require_once '../../../classes/Project.php';

$_SESSION['project-selected'] = true;

// Retrieving master's ID and folder name from URL
$URL = $_SERVER['PHP_SELF'];
$start = strpos($URL,'/scrum/'); $end = strpos($URL,'overview.php');

$masterId = substr($URL, $start + strlen('/scrum/'), 1);
$folderName = substr($URL, $start + strlen('/scrum/#/'), $end - ($start + strlen('/scrum/#/') + 1));

$project_vrac = $database->prepare('SELECT * FROM project WHERE folder_name = ? AND master_id = ?');
$project_vrac->execute(Array($folderName,$masterId));
$project = $project_vrac->fetch();

$Project = new Project($project['id']);

$memberCount = (int) ($database->query('	SELECT COUNT(*) as count
											FROM project_developer
											WHERE project_id = ' . $project['id'])->fetch())['count'] + 1;

?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<title><?php echo $project['name'];  ?> - Overview | SCRUManager</title>
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
								<h3><?php echo $project['name']; ?> <small> overview</small></h3>
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
						<div class="row">
							<div class="col-md-12">
								<div class="x_panel">
									<div class="x_title">
									<h2>New Partner Contracts Consultancy</h2>
									<ul class="nav navbar-right panel_toolbox">
										<li>
											<a href="#"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
											<ul class="dropdown-menu" role="menu">
												<li><a href="#">Settings 1</a></li>
												<li><a href="#">Settings 2</a></li>
											</ul>
										</li>
										<li>
											<a href="#"><i class="fa fa-close"></i></a>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>

								<div class="x_content">
									<div class="col-md-9 col-sm-9 col-xs-12">
										<ul class="stats-overview">
											<li>
												<span class="name"> Number of collaborators </span>
												<span class="value text-success"> <?php echo $memberCount; ?> </span>
											</li>
											<!-- <li>
												<span class="name"> Total amount spent </span>
												<span class="value text-success"> 2000 </span>
											</li> -->
											<li class="hidden-phone">
												<span class="name"> Estimated project duration </span>
												<span class="value text-success"> 20 </span>
											</li>
										</ul>
										<br />
										<div class="x_content">
<?php $Project->printBacklog(5); ?>
											<a href="backlog.php" class="btn btn-primary" style="float: right;margin: 0 3% 10px 0;">See entire backlog</a>
										</div>
										<div>
											<h4>Recent Activity</h4>

											<!-- end of user messages -->
											<ul class="messages">
												<li>
													<img src="images/img.jpg" class="avatar" alt="Avatar">
													<div class="message_date">
														<h3 class="date text-info">24</h3>
														<p class="month">May</p>
													</div>
													<div class="message_wrapper">
														<h4 class="heading">Desmond Davison</h4>
														<blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
														<br />
														<p class="url">
														<span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
														<a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
														</p>
													</div>
												</li>
												<li>
													<img src="images/img.jpg" class="avatar" alt="Avatar">
													<div class="message_date">
														<h3 class="date text-error">21</h3>
														<p class="month">May</p>
													</div>
													<div class="message_wrapper">
														<h4 class="heading">Brian Michaels</h4>
														<blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
														<br />
														<p class="url">
														<span class="fs1" aria-hidden="true" data-icon=""></span>
														<a href="#" data-original-title="">Download</a>
														</p>
													</div>
												</li>
												<li>
													<img src="images/img.jpg" class="avatar" alt="Avatar">
													<div class="message_date">
														<h3 class="date text-info">24</h3>
														<p class="month">May</p>
													</div>
													<div class="message_wrapper">
														<h4 class="heading">Desmond Davison</h4>
														<blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
														<br />
														<p class="url">
														<span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
														<a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
														</p>
													</div>
												</li>
											</ul>
									<!-- end of user messages -->
										</div>
									</div>
								<!-- start project-detail sidebar -->
									<div class="col-md-3 col-sm-3 col-xs-12">
										<section class="panel">
											<div class="x_title">
												<h2>Project Description</h2>
												<div class="clearfix"></div>
											</div>
											<div class="panel-body">
												<h3 class="green"><i class="fa fa-paint-brush"></i> Gentelella</h3>
<?php $Project->printDescription(); ?>
												<br />
												<div class="project_detail">
													<p class="title">Client Company</p>
													<p><?php echo $Project->getMoa(); ?></p>
													<p class="title">Project Leader</p>
													<p><?php echo $Project->getMaster(); ?></p>
												</div>
											</div>
										</section>
									</div>
									<!-- end project-detail sidebar -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->

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
