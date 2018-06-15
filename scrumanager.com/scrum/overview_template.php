<?php
session_start();
if (!isset($_SESSION['id']))
	header('Location:index.php');

include '../../../identifiants.php';

// Retrieving master's ID and folder name from URL
$URL = $_SERVER['PHP_SELF'];
$start = strpos($URL,'/scrum/'); $end = strpos($URL,'overview.php');

$masterId = substr($URL, $start + strlen('/scrum/'), 1);
$folderName = substr($URL, $start + strlen('/scrum/#/'), $end - ($start + strlen('/scrum/#/') + 1));

$project_vrac = $database->prepare('SELECT * FROM project WHERE folder_name = ? AND master_id = ?');
$project_vrac->execute(Array($folderName,$masterId));
$project = $project_vrac->fetch();

$memberCount = (int) ($database->query('	SELECT COUNT(*) as count
											FROM project_developer
											WHERE project_id = ' . $project['id'])->fetch())['count'] + 1;
// $
function projectProgress($project_id) {
	// TODO : return the progess in percentage of the project id given in parameter
	// To do so just sum up the cost of completed user stories and take it ratio with the tatal cost of the backlog
	
	// Facking
	return random_int(1,100);
}


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
															<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
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
															<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
															<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
														</td>
														<td>@mdo</td>
													</tr>
													<tr>
														<th scope="row">3</th>
														<td>Larry</td>
														<td>the Bird</td>
														<td>@twitter</td>
														<td>
															<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
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
												<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terr.</p>
												<br />
												<div class="project_detail">
													<p class="title">Client Company</p>
													<p>Deveint Inc</p>
													<p class="title">Project Leader</p>
													<p>Tony Chicken</p>
												</div>
												<br />
												<h5>Project files</h5>
												<ul class="list-unstyled project_files">
													<li>
														<a href=""><i class="fa fa-file-word-o"></i> Functional-requirements.docx</a>
													</li>
													<li>
														<a href=""><i class="fa fa-file-pdf-o"></i> UAT.pdf</a>
													</li>
													<li>
														<a href=""><i class="fa fa-mail-forward"></i> Email-from-flatbal.mln</a>
													</li>
													<li>
														<a href=""><i class="fa fa-picture-o"></i> Logo.png</a>
													</li>
													<li>
														<a href=""><i class="fa fa-file-word-o"></i> Contract-10_12_2014.docx</a>
													</li>
												</ul>
												<br />
												<div class="text-center mtop20">
													<a href="#" class="btn btn-sm btn-primary">Add files</a>
													<a href="#" class="btn btn-sm btn-warning">Report contact</a>
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
