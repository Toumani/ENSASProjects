<?php
class Sprint {
	public $no;
	private $projectId;
	public $startDate;
	public $endDate;
	public $color;
	
	private static $database;

	public static function setDatabase($database) {
		Sprint::$database = $database;
	}

	public function Sprint($no, $projectId) {
		$this->no = $no;
		$this->projectId = $projectId;
		$sprint = Sprint::$database->query('SELECT start_date,end_date,red,green,blue,alpha
											FROM sprint S
											LEFT JOIN color C
											ON S.color = C.id
											WHERE no = ' . $this->no . ' AND project_id = ' . $this->projectId)
											->fetch();
		$this->startDate = $sprint['start_date'];
		$this->endDate = $sprint['end_date'];
		$this->color['red'] = $sprint['red'];
		$this->color['green'] = $sprint['green'];
		$this->color['blue'] = $sprint['blue'];
		$this->color['alpha'] = $sprint['alpha'];
	}

	public function listUs($limit = 0) {
		$us_vrac = Sprint::$database->query('SELECT text FROM user_story WHERE project_id = ' . $this->projectId . ' AND sprint_no = ' . $this->no);
		while ($us = $us_vrac->fetch()) {
?>
														
<?php
		}

	} 
}

Sprint::setDatabase($database);