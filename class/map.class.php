<?php //All of Report classes

class exTaxMap{
	private $nextfield;

	public $type;
	public $properties;//จำนวนแถวของข้อมูลที่มีให้แสดง
	public $geometry;//จำนวนแถวของข้อมูลทั้งหมดที่ดึงมาได้

	function __construct() {//Initital variable in class
		$this->nextfield = 1;
		$this->type = "Feature";
		$this->properties = array();
		$this->geometry = null;
        }

        function __destruct(){
		unset($this->properties);
        }

	public function AddProperties($item){
		$this->properties["field_".$this->nextfield] = $item;
		$this->nextfield++;
	}
}

?>
