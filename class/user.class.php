<?php
class exUser_Profile{//สำหรับดึงข้อมูลฉลากขึ้นมาแสดง
	public $id;//User ID หากมีค่าเป็น 0 ให้ทำการ Logout
	public $fullname;//ชื่อจริง
	public $level;//ณะดับการเข้าถึงข้อมูล
	public $Region;//ภาคที่สังกัด
	public $Province;//จังหวัดที่สังกัด
	public $Area;//พื้นที่ที่สังกัด
	public $Branch;//สาขาที่สังกัด
	public $RegionTXT;//ภาคที่สังกัด(ตัวหนังสือ)
	public $ProvinceTXT;//จังหวัดที่สังกัด(ตัวหนังสือ)
	public $AreaTXT;//พื้นที่ที่สังกัด(ตัวหนังสือ)
	public $BranchTXT;//สาขาที่สังกัด(ตัวหนังสือ)

	function __construct() {//Initital variable in class
		$this->id = 1;
		$this->fullname = "ทดสอบ ครั้งแรก";
		$this->level = 1;
		$this->Region = 5;
		$this->Province = 50;
		$this->Area = 5501;
		$this->Branch = 550101;
		$this->RegionTXT = "ภาคที่ 5";
		$this->ProvinceTXT = "เชียงใหม่";
		$this->AreaTXT = "สำนักงานสรรพสามิตพื้นที่เชียงใหม่";
		$this->BranchTXT = "สาขาเมืองเชียงใหม่";
	}
}
?>

