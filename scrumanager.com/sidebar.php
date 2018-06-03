						<!-- sidebar menu -->
						<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

							<div class="menu_section">
								<h3>General</h3>
								<ul class="nav side-menu">
									<li><a><i class="fa fa-code"></i> Project <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu" style="display: none">
											<li><a href="index.html">Overview</a>
											</li>
											<li><a href="index2.html">Backlog</a>
											</li>
											<li><a href="index3.html">Sprints</a>
											</li>
										</ul>
									</li>
									<li><a><i class="fa fa-users"></i> Collaborators <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu" style="display: none">
<?php
$developer_vrac =
$database->prepare('	SELECT id, name
						FROM developer D
						INNER JOIN project_developer PD ON D.id = PD.developer_id
						WHERE
							(
								PD.project_id IN (	SELECT project_id
													FROM project_developer
													WHERE developer_id = ?)
							OR
								D.id IN (	SELECT project_id
											FROM project_developer
											WHERE developer_id = ?)
						)
						AND
							D.id <> ?');
$developer_vrac->execute(Array($_SESSION['id'],$_SESSION['id'],$_SESSION['id']));
while ($developer = $developer_vrac->fetch()) {
?>
											<li>
												<a href="../../<?php echo $developer['id']; ?>/dual/profile.php"><?php echo $developer['name']; ?></a>
											</li>
<?php
}
?>
										</ul>
									</li>
									<li><a><i class="fa fa-user"></i> Owner <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu" style="display: none">
<?php
$moa_vrac =
$database->query('	SELECT id,name
					FROM moa
					WHERE id IN ( 	SELECT DISTINCT owner_id
									FROM project
									WHERE master_id = ' . $_SESSION['id'] . ')');
while($moa = $moa_vrac->fetch()) {
?>
											<li>
												<a href="#"><?php echo $moa['name']; ?></a>
											</li>
<?php
}
?>
										</ul>
									</li>
								</ul>
							</div>
						</div>
						<!-- /sidebar menu -->

						<!-- menu footer buttons -->
						<div class="sidebar-footer hidden-small">
							<a data-toggle="tooltip" data-placement="top" title="Settings">
								<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
							</a>
							<a data-toggle="tooltip" data-placement="top" title="FullScreen">
								<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
							</a>
							<a data-toggle="tooltip" data-placement="top" title="Lock">
								<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
							</a>
							<a href="../../log_out.php" data-toggle="tooltip" data-placement="top" title="Logout">
								<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
							</a>
						</div>
						<!-- /menu footer buttons -->
