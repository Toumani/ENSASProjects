<?php
session_start();

include '../../../identifiants.php';



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
								<h3>New project</h3>
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

							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel" style="height:600px;">
									<div class="x_title">
										<h2>Project main infos</h2>
										<ul class="nav navbar-right panel_toolbox">
											<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
											</li>
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
												<ul class="dropdown-menu" role="menu">
												<li><a href="#">Settings 1</a>
												</li>
												<li><a href="#">Settings 2</a>
												</li>
												</ul>
											</li>
											<li><a class="close-link"><i class="fa fa-close"></i></a>
											</li>
										</ul>
										<div class="clearfix"></div>
									</div>

									<form data-parsley-validate class="form-horizontal form-label-left" method="POST" action="add_project.php">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
												Project Name <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Project owner</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="select2_single form-control" tabindex="-1">
													<option value="-1">-- Choose your project owner --</option>
<?php
$moa_vrac = $database->query('SELECT id,name,email FROM moa');
while ($moa = $moa_vrac->fetch()) {
?>
													<option value="<?php echo $moa['id']; ?>"><?php echo $moa['name']; ?> - <?php echo $moa['email']; ?></option>
<?php
}
?>
												</select>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
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

	</body>

</html>
