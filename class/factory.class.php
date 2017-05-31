<?php //All of Report classes

class exFactory{
	public $ID;// รหัสโรงงาน 0 = เพิ่มโรงงานใหม่
	public $Province;//ID จังหวัด
	public $ProvinceTXT;//ID จังหวัด
	public $Region;//ภาคที่
	public $Lat;//Latitude
	public $Long;//Longitude
	public $IssueDate;//วันที่ออกใบอนุญาต
	public $LicenseNo;//หมายเลขใบอนุญาต
	public $ContactName;//ผู้ขออนุญาต
	public $FactoryName;//ชื่อโรงงาน
	public $Address;//สถานที่ตั้งโรงงาน
	public $PCapital;//ทุนการผลิต;
	public $HPower;//แรงม้า;
	public $Worker;//จำนวนคนงาน;
	public $Plan;//ชื่อไฟล์ของผังโรงงาน
	public $LicenseCon;//ใบอนุญาตก่อสร้าง

	function __construct() {//Initital variable in class
		$this->ID = 0;
		$this->Province = 0;
		$this->Region = 0;
		$this->Lat = 0;
		$this->Long = 0;
		$this->IssueDate = "";
		$this->RegistNo = "";
		$this->ContactName = "";
		$this->FactoryName = "";
		$this->Address = "";
		$this->PCapital = 0;
		$this->HPower = 0;
		$this->Worker = 0;
		$this->Plan = "";
		$this->LicenseNo = "";
        }

        function __destruct(){
        }

	public function Init($Region, $Province){
		$this->Region = $Region;
		$this->Province = $Province;
	}

	public function SaveData($data){
		$this->ID = $data["FactoryID"];
		$this->Province = $data["faProvince"];
		$this->ProvinceTXT = $data["pvName"];
		$this->Region = $data["faRegion"];
		$this->Lat = $data["faLat"];
		$this->Long = $data["faLong"];
		$this->IssueDate = $data["faIssueDate"];
		$this->RegistNo = $data["faRegistNo"];
		$this->ContactName = $data["faContact"];
		$this->FactoryName = $data["faName"];
		$this->Address = $data["faAddress"];
		$this->PCapital = $data["faCapital"];
		$this->HPower = $data["faHP"];
		$this->Worker = $data["faWorker"];
		$this->Plan =  (!is_null($this->ID) && file_exists("../data/factoryplan/".$this->ID.$fdata["faPlan"]))==true?"data/factoryplan/".$this->ID.$fdata["faPlan"]:"";
		$this->LicenseNo = $data["faLicenseNo"];
	}

	public function AddFactory($data){
	}

	public function UpdateFactory($date,$FacID){
	}

	public function DelFactory($data){
	}

	public function FactoryPlanUpdate($File){
	}
}

?>
