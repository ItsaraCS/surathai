<?php
class exSearch_Table{
	private $lastid;//เลขรัน ID

	public $num_of_column;//จำนวนคอลัมน์
	public $num_of_row;//จำนวนแถวของข้อมูลที่มีให้แสดง
	public $sum_of_row;//จำนวนแถวของข้อมูลทั้งหมดที่ดึงมาได้
	public $cur_page;//หน้าปัจจุบัน
	public $row_per_page;//จำนวนแถวของข้อมูลต่อ 1 หน้า
	public $label;//หัวข้อ เป็น array of string
	public $data;//ข้อมูล เป็น array of class exReport_Cell
	public $menu;//เมนูด้านซ้าย เป็น array of exMenu
	public $latlong;//พิกัดเป็น array of exLatLong

	function __construct() {//Initital variable in class
		$this->lastid=0;
		$this->num_of_column = 0;
		$this->num_of_row = 0;
		$this->sum_of_row = 0;
		$this->cur_page = 1;
		$this->row_per_page = 0;
		$this->label = array();
		$this->data = array();
		$this->menu = array();		
		$this->latlong = array();		
	}

	function __destruct(){
		unset($this->label);
		unset($this->data);
	}

	public function AddLatLong($id,$lat,$long){
		$ll = new exGPS;
		$ll->Lat = $lat;
		$ll->Long = $long;
		$this->latlong[$id] = $ll;
	}
	
	public function AddTitleMenu($Title){
		$this->menu = array();
		$menu = new exMenu;
		$menu->id = 0;
		$menu->column = count($Title);
		$menu->subject = $Title[0];
		$menu->type = 0;
		array_shift($Title);
		$menu->value = $Title;
		$this->menu[0] = $menu;
	}
	
	public function AddcontentMenu($content){		
		$menu = new exMenu;
		$menu->id = count($this->menu);
		$menu->column = count($content);
		$menu->subject = $content[0];
		$menu->type = 1;
		array_shift($content);
		$menu->value = $content;
		array_push($this->menu,$menu);
	}

	public function TerminateMenu($summary){
		$menu = new exMenu;
		$menu->id = 0;
		$menu->column = count($summary)+1;
		$menu->subject = "รวมทั้งสิ้น";
		$menu->type = 2;
		$menu->value = $summary;
		array_push($this->menu,$menu);
	}

	public function InitMenu($job,$data){
		switch($job){
			case 1 :
						$this->AddTitleMenu(array("รายการ","ภาษี"));
						$this->AddcontentMenu(array("ก่อสร้าง",isset($data[0])?number_format($data[0],2):"-"));
						$this->AddcontentMenu(array("ผลิต",isset($data[1])?number_format($data[1],2):"-"));
						$this->AddcontentMenu(array("ขาย",isset($data[2])?number_format($data[2],2):"-"));
						$this->AddcontentMenu(array("ขน",isset($data[3])?number_format($data[3],2):"-"));
						$this->AddcontentMenu(array("แสตมป์",isset($data[4])?number_format($data[4],2):"-"));
						$this->TerminateMenu(array(isset($data[5])?number_format($data[5],2):"-"));
				break;
			case 2 :
						$this->AddTitleMenu(array("ประเภทคดี","จำนวนคดี"));
						$this->AddcontentMenu(array("ไม่ทำบัญชี",isset($data[0])?number_format($data[0]):"-"));
						$this->AddcontentMenu(array("ผลิต",isset($data[1])?number_format($data[1]):"-"));
						$this->AddcontentMenu(array("ขาย",isset($data[2])?number_format($data[2]):"-"));
						$this->AddcontentMenu(array("ขน",isset($data[3])?number_format($data[3]):"-"));
						$this->TerminateMenu(array(isset($data[4])?number_format($data[4]):"-"));
				break;
			case 3 :
						$this->AddTitleMenu(array("ประเภทใบอนุญาต","จำนวนใบอนุญาต"));
						$this->AddcontentMenu(array("ก่อสร้าง",isset($data[0])?number_format($data[0]):"-"));
						$this->AddcontentMenu(array("ผลิต",isset($data[1])?number_format($data[1]):"-"));
						$this->AddcontentMenu(array("ขาย",isset($data[2])?number_format($data[2]):"-"));
						$this->AddcontentMenu(array("ขน",isset($data[3])?number_format($data[3]):"-"));
						$this->TerminateMenu(array(isset($data[4])?number_format($data[4]):"-"));
				break;
			case 4 :
						$this->AddTitleMenu(array("ประเภทสุรา","28","30","35","40","รวม"));
						$this->AddcontentMenu(array("สุรากลั่น",number_format($data[0]),number_format($data[1]),number_format($data[2]),number_format($data[3]),number_format($data[4])));
						$this->AddcontentMenu(array("สุราแช่",number_format($data[5]),number_format($data[6]),number_format($data[7]),number_format($data[8]),number_format($data[9])));
						$this->TerminateMenu(array(number_format($data[0]+$data[5]),number_format($data[1]+$data[6]),number_format($data[2]+$data[7]),number_format($data[3]+$data[8]),number_format($data[4]+$data[9])));
				break;
			case 5 :
						$this->AddTitleMenu(array("ประเภทโรงงาน","จำนวน"));
						$this->AddcontentMenu(array("โรงงาน",isset($data[0])?number_format($data[0]):"-"));
						$this->AddcontentMenu(array("ยี่ห้อ",isset($data[1])?number_format($data[1]):"-"));
						$this->TerminateMenu(array(isset($data[2])?number_format($data[2]):"-"));
				break;
			default :
		}
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

	public function Init($job,$cur,$rpp,$sumrow,$data=null){
		if($cur > $sumrow / $rpp){
			$cur = ceil($sumrow / $rpp);
		}
		$this->cur_page = $cur;
		$this->row_per_page = $rpp;
		$this->sum_of_row= $sumrow;
		$this->InitMenu($job,$data);
	}
}

class exGPS{
	public $Lat;
	public $Long;
}

class exMenu{
  public $id; //id ของ menu
  public $type; //มี 3 type ได้แก่ 0=หัวตาราง,1=เนื้อหา, 2=สรุป
  public $column; //จำนวน column นับรวมส่วนที่เป็น subject ด้วย
  public $subject;//หัวข้อ เป็น string
  public $value;//ค่าที่แสดง เป็น array of string มีจำนวนเท่ากับ column - 1
}
?>
