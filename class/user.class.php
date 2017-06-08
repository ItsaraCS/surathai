<?php
require_once("session.class.php");

class exUser_Profile{//สำหรับดึงข้อมูลฉลากขึ้นมาแสดง
	public $id;//User ID หากมีค่าเป็น 0 ให้ทำการ Logout
	public $Gender;//เพศ
	public $Status;//สถานะ
	public $Username;//Username สำหรับใช้ login
	public $Fullname;//ชื่อจริง
	public $Level;//ณะดับการเข้าถึงข้อมูล
	public $Under;//อยู่ภายใต้
	public $Eform;//สิทธิ์ในการเข้าถึง e-form
	public $Region;//ภาคที่สังกัด
	public $Province;//จังหวัดที่สังกัด
	public $Area;//พื้นที่ที่สังกัด
	public $Branch;//สาขาที่สังกัด
	public $RegionTXT;//ภาคที่สังกัด(ตัวหนังสือ)
	public $ProvinceTXT;//จังหวัดที่สังกัด(ตัวหนังสือ)
	public $AreaTXT;//พื้นที่ที่สังกัด(ตัวหนังสือ)
	public $BranchTXT;//สาขาที่สังกัด(ตัวหนังสือ)
	public $Email;//อีเมล์
	public $Tel;//หมายเลขโทรศัพท์
	public $Mobile;//หมายเลขโทรศัพท์มือถือ

	public function Load2Profile($DBData){
		$this->id = $DBData["AdminID"];
		$this->Gender = $DBData["adGender"];
		$this->Status = $DBData["adStatus"];
		$this->Username = $DBData["adUsername"];
		$this->Fullname = $DBData["adFullname"];
		$this->Level = $DBData["adLevel"];
		$this->Under = $DBData["adUnder"];
		$this->Eform = $DBData["adEform"];
		$this->Region = $DBData["adRegion"];
		$this->Province = $DBData["adProvince"];
		$this->Area = $DBData["adArea"];
		$this->Branch = $DBData["adBranch"];
		$this->RegionTXT = "ศรรพสามิตภาค ".$DBData["adRegion"];
		$this->ProvinceTXT = $DBData["pvName"];
		$this->AreaTXT = $DBData["arName"];
		$this->BranchTXT = $DBData["brName"];
		$this->Email = $DBData["adEmail"];
		$this->Tel = $DBData["adTel"];
		$this->Mobile = $DBData["adMobile"];
	}

	public function Export2JSON(){
		return json_encode($this);
	}
}

class exUser{//สำหรับดึงข้อมูลฉลากขึ้นมาแสดง
	public $id;//User ID หากมีค่าเป็น 0 ให้ทำการ Logout
	public $Gender;//เพศ
	public $Fullname;//ชื่อจริง
	public $Level;//ณะดับการเข้าถึงข้อมูล
	public $Under;//อยู่ภายใต้
	public $Eform;//สิทธิ์ในการเข้าถึง e-form
	public $Region;//ภาคที่สังกัด
	public $Province;//จังหวัดที่สังกัด
	public $Area;//พื้นที่ที่สังกัด
	public $Branch;//สาขาที่สังกัด
	public $RegionTXT;//ภาคที่สังกัด(ตัวหนังสือ)
	public $ProvinceTXT;//จังหวัดที่สังกัด(ตัวหนังสือ)
	public $AreaTXT;//พื้นที่ที่สังกัด(ตัวหนังสือ)
	public $BranchTXT;//สาขาที่สังกัด(ตัวหนังสือ)
	public $Message;//ข้อความที่ให้แสดง

	function __construct() {//Initital variable in class
		if(!session_is_registered('surathai')){
			session_is_registered('surathai');
			$this->clearProfile();
			$this->SavetoSession();
		}else{
			$this->LoadFromSession();
		}
	}

	function __destruct(){
	}

	private function LoadFromSession(){
		$this->id = $_SESSION['surathai']["id"];
		$this->Gender = $_SESSION['surathai']["Gender"];
		$this->Fullname = $_SESSION['surathai']["Fullname"];
		$this->Level = $_SESSION['surathai']["Level"];
		$this->Under = $_SESSION['surathai']["under"];
		$this->Eform = $_SESSION['surathai']["eform"];
		$this->Region = $_SESSION['surathai']["region"];
		$this->Province = $_SESSION['surathai']["province"];
		$this->Area = $_SESSION['surathai']["area"];
		$this->Branch = $_SESSION['surathai']["branch"];
		$this->RegionTXT = $_SESSION['surathai']["regionTXT"];
		$this->ProvinceTXT = $_SESSION['surathai']["provinceTXT"];
		$this->AreaTXT = $_SESSION['surathai']["areaTXT"];
		$this->BranchTXT = $_SESSION['surathai']["branchTXT"];
	}

        private function SavetoSession(){
		$_SESSION['surathai']["id"] = $this->id;
		$_SESSION['surathai']["Gender"] = $this->Gender;
		$_SESSION['surathai']["Fullname"] = $this->Fullname;
		$_SESSION['surathai']["Level"] = $this->Level;
		$_SESSION['surathai']["under"] = $this->Under;
		$_SESSION['surathai']["eform"] = $this->Eform;
		$_SESSION['surathai']["region"] = $this->Region;
		$_SESSION['surathai']["province"] = $this->Province;
		$_SESSION['surathai']["area"] = $this->Area;
		$_SESSION['surathai']["branch"] = $this->Branch;
		$_SESSION['surathai']["regionTXT"] = $this->RegionTXT;
		$_SESSION['surathai']["provinceTXT"] = $this->ProvinceTXT;
		$_SESSION['surathai']["areaTXT"] = $this->AreaTXT;
		$_SESSION['surathai']["branchTXT"] = $this->BranchTXT;
	}

	private function clearProfile(){
		$this->id = 0;
		$this->Gender = 0;
		$this->Fullname = "";
		$this->Level = 99;
		$this->Under = 9;
		$this->Eform = 0;
		$this->Region = 0;
		$this->Province = 0;
		$this->Area = 0;
		$this->Branch = 0;
		$this->RegionTXT = "";
		$this->ProvinceTXT = "";
		$this->AreaTXT = "";
		$this->BranchTXT = "";
		$this->Message = "";
/*		$this->id = 1;
		$this->Gender = 0;
		$this->Fullname = "ทดสอบ ครั้งแรก";
		$this->Level = 1;
		$this->Under = 0;
		$this->Eform = 1;
		$this->Region = 5;
		$this->Province = 50;
		$this->Area = 5501;
		$this->Branch = "0550101";
		$this->RegionTXT = "สรรพาสามิตภาค 5";
		$this->ProvinceTXT = "เชียงใหม่";
		$this->AreaTXT = "เชียงใหม่";
		$this->BranchTXT = "สาขาเมืองเชียงใหม่";
		$this->Message = "";*/
	}

	public function Login($status){	
		if($status == 1){
			$this->SavetoSession();
		}else{
			$this->clearProfile();
			$this->Message = "ไม่สามารถเข้าใช้งานได้";
		}
	}

	public function Logout(){
		$this->clearProfile();
		if(session_is_registered('surathai')){
			session_unregister('surathai');
		}
	}

	public function CanAccessEform(){
		return ($this->Eform==1);
	}

	public function GetJSONProfile(){
		return json_encode($this);
	}

	public function UpdateProfile($DBData){
		$this->id = $DBData["AdminID"];
		$this->Gender = $DBData["adGender"];
		$this->Fullname = $DBData["adFullname"];
		$this->Level = $DBData["adLevel"];
		$this->Under = $DBData["adUnder"];
		$this->Eform = $DBData["adEform"];
		$this->Region = $DBData["adRegion"];
		$this->Province = $DBData["adProvince"];
		$this->Area = $DBData["adArea"];
		$this->Branch = $DBData["adBranch"];
		$this->RegionTXT = "ศรรพสามิตภาค ".$DBData["adRegion"];
		$this->ProvinceTXT = $DBData["pvName"];
		$this->AreaTXT = $DBData["arName"];
		$this->BranchTXT = $DBData["brName"];
	}
}

class exUser_Table{
        public $num_of_row;//จำนวนแถวของข้อมูลที่มีให้แสดง
        public $sum_of_row;//จำนวนแถวของข้อมูลทั้งหมดที่ดึงมาได้
        public $cur_page;//หน้าปัจจุบัน
        public $row_per_page;//จำนวนแถวของข้อมูลต่อ 1 หน้า
        public $data;//ข้อมูล เป็น array of class exReport_Cell

        function __construct() {//Initital variable in class
                $this->num_of_row = 0;
                $this->sum_of_row = 0;
                $this->cur_page = 1;
                $this->row_per_page = 0;
                $this->data = array();
        }

        function __destruct(){
                unset($this->data);
        }

	public function AddItem($id,$icon,$text){
		$CellObj = new exUser_Item;
		$CellObj->id = $id;
		$CellObj->icon = $icon;
		$CellObj->text = $text;
		array_push($this->data,$CellObj);
		$this->num_of_row++;
		return true;
        }

        public function Init($cur,$rpp,$sumrow){
                if($cur > $sumrow / $rpp){
                        $cur = ceil($sumrow / $rpp);
                }
                $this->cur_page = $cur;
                $this->row_per_page = $rpp;
                $this->sum_of_row= $sumrow;
        }
}

class exUser_Item{
        public $id; //id ของ user จะถูกนำไปใช้เรียก getdata อีกที
        public $icon;//บอกว่าเป็นเพศใด 0=หญิง 1=ชาย
        public $text;//ข้อความที่แสดง (ชื่อ - นามสกุล)
}
?>
