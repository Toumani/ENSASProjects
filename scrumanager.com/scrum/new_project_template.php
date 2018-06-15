<?php
session_start();
if (!isset($_SESSION['id']))
	header('Location:index.php');

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

									<form data-parsley-validate class="form-horizontal form-label-left" method="POST" action="../../../add_project.php">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
												Project Name <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" id="project-name" required="required" class="form-control col-md-7 col-xs-12" name="project-name" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Project owner *</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="project-owner" id="project-owner" class="form-control col-md-10" style="float: left;" />
												<div id="project-owner-container" required style="position: relative; float: left; width: 400px; margin: 10px;"></div>
											</div>
										</div>
										<div class="form-group last-autocomplete">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Co-workers</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="co-worker-1" id="autocomplete-co-workers-1" class="form-control col-md-10" style="float: left;" />
												<div id="autocomplete-co-workers-container-1" style="position: relative; float: left; width: 400px; margin: 10px;"></div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3 col-sm-3"></div>
											<a id="add-co-worker" class="btn btn-app">
												<i class="fa fa-plus"></i> Add
											</a>
										</div>
										<div class="form-group">
											<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
												<a href="index.php" class="btn btn-primary">Cancel</a>
												<button type="submit" class="btn btn-success">Create</button>
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

		<!-- Autocomplete -->
		<script src="../../../js/autocomplete/jquery.autocomplete.js"></script>
		<!-- pace -->
		<script src="../../../js/pace/pace.min.js"></script>
		<script type="text/javascript">
			// Project owner list
			var moas = {
<?php
$moa_vrac = $database->query('SELECT id,name,email FROM moa');
$moa = $moa_vrac->fetch();
?>
				'<?php echo $moa['id']; ?>' : '<?php echo $moa['name']; ?> - <?php echo $moa['email']; ?>'
<?php
while ($moa = $moa_vrac->fetch()) {
?>
				,'<?php echo $moa['id']; ?>' : '<?php echo $moa['name']; ?> - <?php echo $moa['email']; ?>'
<?php
}
?>
			};

			// Developer list
			var developers = {

<?php
$developer_vrac = $database->query('SELECT id,name,email FROM developer');
$developer = $developer_vrac->fetch();
?>
				'<?php echo $developer['id']; ?>' : '<?php echo $developer['name']; ?> - <?php echo $developer['email']; ?>'
<?php
while ($developer = $developer_vrac->fetch()) {
?>
				,'<?php echo $developer['id']; ?>' : '<?php echo $developer['name']; ?> - <?php echo $developer['email']; ?>'
<?php
}
?>
			};
			$(function() {
				'use strict';
				var moasArray = $.map(moas, function(value, key) {
					return {
					value: value,
					data: key
					};
				});
				// Initialize autocomplete with custom appendTo:
				$('#project-owner').autocomplete({
					lookup: moasArray,
					appendTo: '#project-owner-container'
				});

				var developersArray = $.map(developers, function(value, key) {
					return {
					value: value,
					data: key
					};
				});
				// Initialize autocomplete with custom appendTo:
				$('#autocomplete-co-workers-1').autocomplete({
					lookup: developersArray,
					appendTo: '#autocomplete-co-workers-container-1'
				});

				var autocompleteNb = 1;

				$('#add-co-worker').on('click', function() {
					autocompleteNb++;
					var $oldLastAutocomplete = $('.last-autocomplete');
					$oldLastAutocomplete.removeClass('last-autocomplete');
					$(
'					<div class="form-group last-autocomplete">\n\
						<div class="col-md-3 col-sm-3 col-xs-12"></div>\n\
						<div class="col-md-6 col-sm-6 col-xs-12">\n\
							<input type="text" name="co-worker-' + autocompleteNb + '" id="autocomplete-co-workers-' + autocompleteNb + '" class="form-control col-md-10" style="float: left;" />\n\
							<div id="autocomplete-co-workers-container-' + autocompleteNb + '" style="position: relative; float: left; width: 400px; margin: 10px;"></div>\n\
						</div>\n\
					</div>\n\
'
					).insertAfter($oldLastAutocomplete);
					// Initialize autocomplete with custom appendTo:
					$('#autocomplete-co-workers-' + autocompleteNb).autocomplete({
						lookup: developersArray,
						appendTo: '#autocomplete-co-workers-container-' + autocompleteNb
					});
				});
			});
		</script>

	</body>

</html>
