<?php //All of Report classes
class exFilter_Bar{//ทุก properties เป็น array of class exItem ดูได้ใน util.class.php
	public $job;//งานส่วนใด 1=งานภาษี ... 5=ข้อมูลโรงงาน
	public $year;//ปีงบประมาณเป็น พศ
	public $region;//ภาค
	public $province;//จังหวัด
}

class exReport_Table{
	private $lastid;//เลขรัน ID

	public $num_of_column;//จำนวนคอลัมน์
	public $num_of_row;//จำนวนแถวของข้อมูลที่มีให้แสดง
	public $sum_of_row;//จำนวนแถวของข้อมูลทั้งหมดที่ดึงมาได้
	public $cur_page;//หน้าปัจจุบัน
	public $row_per_page;//จำนวนแถวของข้อมูลต่อ 1 หน้า
	public $label;//หัวข้อ เป็น array of string
	public $data;//ข้อมูล เป็น array of class exReport_Cell

	function __construct() {//Initital variable in class
		$this->lastid=0;
		$this->num_of_column = 0;
		$this->num_of_row = 0;
		$this->sum_of_row = 0;
		$this->cur_page = 1;
		$this->row_per_page = 0;
		$this->label = array();
		$this->data = array();
        }

        function __destruct(){
		unset($this->label);
		unset($this->data);
        }

	public function AddLabel($text){
		if(empty($this->data)){
			array_push($this->label,$text);
			$this->num_of_column++;
			return true;
		}else{
			return false;
		}
	}

	public function AddCell($text,$align=0){
		if($this->lastid < ($this->num_of_column * $this->row_per_page)){
			$CellObj = new exReport_Cell;
			$CellObj->id = $this->lastid;
			$CellObj->row = 1 + floor($this->lastid / $this->num_of_column);
			$this->num_of_row = $CellObj->row;
			$CellObj->align = $align;
			$CellObj->text = $text;
			array_push($this->data,$CellObj);
			$this->lastid++;
			return true;
		}else{
			return false;
		}
	}

	public function Init($cur,$rpp,$sumrow){
		if($cur > $sumrow / $rpp){
			$cur = floor($sumrow / $rpp);
		}
		$this->cur_page = $cur;
		$this->row_per_page = $rpp;
		$this->sum_of_row= $sumrow;
	}
}

class exReport_Cell{
	public $id; //id ของ cell
	public $row;//แถวที่เริ่มต้นจาก 1 จนถึง row_per_page
	public $align;//ชิดซ้ายหรือชิดขวา 0=ซ้าย, 1 = ขวา, 2 = กลาง
	public $text;//ข้อความใน cell
}

class exChart{
	public $minvalue;//เลขข้อมูลที่ต่ำสุด นำไปใช้คำนวณสร้าง label แกนตั้ง
	public $maxvalue;//เลขข้อมูลที่มากที่สุด นำไปใช้คำนวณสร้าง label แกนตั้ง
	public $labels;//แสดงในแกนนอน
	public $datasets;//ชุดข้อมูลกราฟ เป็น array of class exChart_Data
}

class exChart_Data{
	public $label;//ชื่อข้อมูล ที่จะแสดงฝั่งขวา
	public $data;//ชุดข้อมูลเป็น array
}
?>
