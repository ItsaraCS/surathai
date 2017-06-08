<?php //All of Illegal classes

class exIllegal{
	public $ID;// รหัสคดี 0 = เพิ่มคดีใหม่
	public $AreaCode;//รหัสพื้นที่
	public $AreaTXT;//พื้นที่
	public $Region;//ภาคที่
	public $ActType;//ประเภท พรบ.
	public $Lat;//Latitude
	public $Long;//Longitude
	public $ActDate;//วันที่เกิดเหตุ		
	public $Suspect;//ผู้ต้องหา
	public $Orator;//ผู้กล่าวหา
	public $Address;//สถานที่เกิดเหตุ
	public $Allegation;//ข้อกล่าวหา
	public $Material;//ของกลาง
	public $ComparativeMoney;//เปรียบเทียบปรับ
	public $Fine;//ค่าปรับ(ศาลปรับ)
	public $Officer;//จ่ายให้พนักงานสอบสวน
	public $Bribe;//สินบนนำจับ
	public $Reward;//เงินรางวัล
	public $Return;//ส่งคืนคลัง

	function __construct() {//Initital variable in class
		$this->ID = 0;
		$this->AreaCode = 0;
		$this->AreaTXT = "";
		$this->Region = 0;
		$this->ActType = 0;
		$this->Lat = 0.0;
		$this->Long = 0.0;
		$this->ActDate = "";
		$this->Suspect = "";
		$this->Orator = "";
		$this->Address = "";
		$this->Allegation = "";
		$this->Material = "";
		$this->ComparativeMoney = 0.0;
		$this->Fine = 0.0;
		$this->Officer = 0.0;
		$this->Bribe = 0.0;
		$this->Reward = 0.0;
		$this->Return = 0.0;
        }

        function __destruct(){
        }

	public function Init($Region, $Area){
		$this->Region = $Region;
		$this->AreaCode = $Area;
	}

	public function SaveData($data){
		$this->ID = $data["IllegalID"];
		$this->AreaCode = $data["ilArea"];
		$this->AreaTXT = $data["arName"];
		$this->Region = $data["ilRegion"];
		$this->ActType = $data["ilActType"];
		$this->Lat = $data["ilLat"];
		$this->Long = $data["ilLong"];
		$this->ActDate = $data["ilActDate"];
		$this->Suspect = $data["ilSuspect"];
		$this->Orator = $data["ilOrator"];
		$this->Address = $data["ilAddress"];
		$this->Allegation = $data["ilAllegation"];
		$this->Material = $data["ilMaterial"];
		$this->ComparativeMoney = $data["ilComparativeMoney"];
		$this->Fine = $data["ilFine"];
		$this->Officer = $data["ilOfficer"];
		$this->Bribe = $data["ilBribe"];
		$this->Reward = $data["IlReward"];
		$this->Return = $data["ilReturn"];
	}

	public function AddIllegal($data){
	}

	public function UpdateIllegal($data,$illegalID){
	}

	public function DelIllegal($data){
	}
}

?>
