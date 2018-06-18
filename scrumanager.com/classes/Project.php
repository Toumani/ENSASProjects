<?php
require_once 'Sprint.php';

class Project {
	private $id;
	private $name;
	private $ownerId;
	private $masterId;
	private static $database;

	private $backlogPrepared = false;
	private $us_vrac;
	private $us;
	private $sprint_vrac;
	private $sprint;
	private $costDone;
	private $costTotal;

	private $sprintsPrepared = false;
	private $Sprint;
	private $nbSprits = 0;

	public function Project($id) {
		$this->id = (int)$id;
		$project = Project::$database->query('SELECT * FROM project WHERE id = ' . $id)->fetch();
		$this->name = $project['name'];
		$this->ownerId = $project['owner_id'];
		$this->masterId = $project['master_id'];
	}

	public static function setDatabase($database) {
		Project::$database = $database;
	}

	public function prepareBacklog($limit = 0) {
		// Retrieving user stories with color
		$limit = (int) $limit;
		if ($limit > 0) {
			$this->us_vrac = Project::$database->prepare('	SELECT 
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
			$this->us_vrac->execute(Array($this->id));
		}
		else {
			$this->us_vrac = Project::$database->prepare('	SELECT 
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
										color C ON duals.color = C.id;
										LIMIT 0,?');
			$this->us_vrac->execute(Array($this->id,$limit));
		}

		// Retrieving sprint list
		$this->sprint_vrac = Project::$database->prepare('SELECT * FROM sprint S LEFT JOIN color C ON S.color = C.id WHERE S.project_id = ?');
		$this->sprint_vrac->execute(Array($this->id));

		// Retrieving user stories cost (done and toal)
		$costDone_vrac = Project::$database->prepare('SELECT SUM(cost) sum FROM user_story WHERE status = 1 AND project_id = ?');
		$costDone_vrac->execute(Array($this->id));
		$this->costDone = (int) $costDone_vrac->fetch()['sum'];

		$costTotal_vrac = Project::$database->prepare('SELECT SUM(cost) sum FROM user_story WHERE project_id = ?');
		$costTotal_vrac->execute(Array($this->id));
		$costTotal = $costTotal_vrac->fetch();
//echo 'POO : ' . $this->costDone . ' and ' . $costTotal['sum'];
		$this->costTotal = $costTotal['sum'];

		$this->backlogPrepared = true;
	} // End of prepareBacklog()

	public function prepareSprints() {
		$sprint_vrac = Project::$database->query('SELECT no FROM sprint WHERE project_id = ' . $this->id);
		for ($i = 0; $sprint = $sprint_vrac->fetch(); $i++) {
			$this->Sprint[$i] = new Sprint($sprint['no'], $this->id);
		}
		$nbSprits = $i;
		$this->sprintsPrepared = true;
	} // end of prepareSprints()

	public function printBacklog() {
		if (!$this->backlogPrepared) {
			// I don't know why but the function must be called twice in order to work properly !!!
			$this->prepareBacklog();
			$this->prepareBacklog();
		}
?>
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
while ($this->us = $this->us_vrac->fetch()) {
?>
													<tr style="background-color: rgba(<?php echo (($this->us['red'] ? $this->us['red'] : '255') . ',' . ($this->us['green'] ? $this->us['green'] : '255') . ',' . ($this->us['blue'] ? $this->us['blue'] : '255') . ',' . ($this->us['alpha'] ? $this->us['alpha'] : '0.5')); ?>)">
														<th scope="row"><?php echo $this->us['no']; ?></th>
														<td><?php echo $this->us['text']; ?></td>
														<td><?php echo $this->us['cost']; ?></td>
														<td><?php echo $this->us['priority']; ?></td>
														<td style="vertical-align: middle;">
															<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
														</td>
														<td><?php echo $this->us['status'] ? '<h3><span class="btn btn-success btn-sm">Done!</span></h3>' : '<h4><span class="btn btn-default btn-xs">On-going</span></h4>'; ?></td>
													</tr>
<?php
}
?>
												</tbody>
												<tfoot>
													<tr style="text-align: right;font-weight: bold;">
														<td></td>
														<td>TOTAL</td>
														<td><?php echo $this->costDone . '/' . $this->costTotal; ?></td>
														<td></td>
														<td></td>
														<td></td>
												</tfoot>
											</table>
<?php
	} // End of printBacklog()

	public function printSprints() {
		if (!$this->sprintsPrepared)
			$this->prepareSprints();
?>
											<table class="table table-hover">
											<thead>
												<tr>
													<th>#</th>
													<th style="width: 75%">Sprint user stories</th>
													<th>Start date</th>
													<th>End date</th>
													<th>Edit</th>
													<th style="width: 10%">Status</th>
												</tr>
											</thead>
											<tbody>
<?php
		foreach ($this->Sprint as $key => $value) {
			$sprint_r = (Array) $value;
?>
												<tr style="background-color: rgba(<?php echo (($sprint_r['color']['red'] ? $sprint_r['color']['red'] : '255') . ',' . ($sprint_r['color']['green'] ? $sprint_r['color']['green'] : '255') . ',' . ($sprint_r['color']['blue'] ? $sprint_r['color']['blue'] : '255') . ',' . ($sprint_r['color']['alpha'] ? $sprint_r['color']['alpha'] : '0.5')); ?>)">
													<th scope="row"><?php echo $sprint_r['no']; ?></th>
													<td><?php $this->Sprint[$key]->listUs(); ?>
													</td>
													<td><?php echo $sprint_r['startDate']; ?></td>
													<td><?php echo $sprint_r['endDate']; ?></td>
													<td style="vertical-align: middle;">
														<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
													</td>
													<td><?php echo false ? '<h3><span class="btn btn-success btn-sm">Done!</span></h3>' : '<h4><span class="btn btn-default btn-xs">On-going</span></h4>'; ?></td>
												</tr>
<?php
		}
?>
											</tbody>
										</table>
<?php
	} // End function printSprints()

	public function listSprints() {
		if (!$this->backlogPrepared) {
			$this->prepareBacklog();
			$this->prepareBacklog();
		}
?>
											<h4>Listing</h4>
											<ul style="list-style-type: none">
<?php
			while ($this->sprint = $this->sprint_vrac->fetch()) {
?>
												<li>
													<i class="fa fa-circle" style="color: rgb(<?php echo (($this->sprint['red'] ? $this->sprint['red'] : '255') . ',' . ($this->sprint['green'] ? $this->sprint['green'] : '255') . ',' . ($this->sprint['blue'] ? $this->sprint['blue'] : '255')); ?>);"></i> Sprint <?php echo $this->sprint['no']; ?>
												</li>
<?php
			}
?>
											</ul>
<?php
	} // End of listsSprints()

	public function printDescription() {
?>
		<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terr.</p>
<?php
	}

	public function getName() { return $this->name; }

	public function getMoa() {
		return Project::$database->query('SELECT name FROM moa WHERE id = ' . $this->ownerId)->fetch()['name'];
	}
	public function getMaster() {
		return Project::$database->query('SELECT name FROM developer WHERE id = ' . $this->masterId)->fetch()['name'];
	}
}

Project::setDatabase($database);