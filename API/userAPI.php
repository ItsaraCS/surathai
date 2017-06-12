<?php
	include("../class/user.class.php");
	require_once("../class/util.class.php");
	require_once("../class/database.class.php");

	$user = new exUser;

	if(isset($_POST["fn"])){
		$DB = new exDB;
		switch($_POST["fn"]){
			case "login" :
					$userdata = $DB->GetDataOneRow("SELECT `AdminID`, `adGender`, `adLevel`, `adUnder`, `adEform`, `adRegion`, `adProvince`, `adArea`, `adBranch`, `adUsername`, `adPassword`, `adFullname`, `pvName`, `arName`, `brName` FROM `Admin`,`Province`,`Area`, `Branch` WHERE adProvince = ProvinceID AND AreaID = adArea AND BranchID = adBranch AND adStatus = 1 AND adUsername = ? AND adPassword = BINARY ?",array("ss",$_POST["user"],$_POST["password"]));
					if(isset($userdata["AdminID"])){
						$user->UpdateProfile($userdata);
						$user->Login(1);
					}else{
						$user->Login(0);
					}
				break;
			case "logout" :
					$user->Logout();
				break;
			case "save" :
					$cansave = false;
					if($user->Level < 2){
						switch($user->Under){
							case 0 ://ปรับแต่งได้ทั้งหมด
								break;
							case 1 ://ภาค
									if(($user->Region <> $_POST["UnderCode"]) || ($_POST["Under"] <> 1)){
										$user->Message = "ไม่สามารถแก้ไขข้อมูลของบุคลากรนอกสังกัดได้";
									}else{
										$cansave = true;
									}
								break;
							case 2 ://พื้นที่
									if(($user->Area <> $_POST["UnderCode"]) || ($_POST["Under"] <> 2)){
										$user->Message = "ไม่สามารถแก้ไขข้อมูลของบุคลากรนอกสังกัดได้";
									}else{
										$cansave = true;
									}
								break;
							case 3 ://สาขา
									if(($user->Branch <> $_POST["UnderCode"]) || ($_POST["Under"] <> 2)){
										$user->Message = "ไม่สามารถแก้ไขข้อมูลของบุคลากรนอกสังกัดได้";
									}else{
										$cansave = true;
									}
								break;
						}
						if($cansave){
							$data = array(
								"adLevel" => $_POST["Level"],
								"adEform" => $_POST["Eform"],
								"adFullname" => $_POST["Fullname"],
								"adEmail" => $_POST["Email"],
								"adTel" => $_POST["Tel"],
								"adMobile" => $_POST["Mobile"],
								"adStatus" => $_POST["Status"],
							);
							if(isset($_POST["Password"]) && ($_POST["Password"]!="")){
								$data["adPassword"] = $_POST["Password"];
							}
							if($_POST["id"] > 0){
								switch($user->Under){
									case 0 ://ปรับแต่งได้ทั้งหมด
										break;
									case 1 ://ภาค
										$DB->UpdateData("Admin",$data,"adUnder = ? AND adRegion = ? AND AdminID = ?",array("iii",$user->Under,$user->Region,$_POST["id"]));
										break;
									case 2 ://พื้นที่
										$DB->UpdateData("Admin",$data,"adUnder = ? AND adArea = ? AND AdminID = ?",array("iii",$user->Under,$user->Area,$_POST["id"]));
										break;
									case 3 ://สาขา
										$DB->UpdateData("Admin",$data,"adUnder = ? AND adBranch = ? AND AdminID = ?",array("iii",$user->Under,$user->Branch,$_POST["id"]));
										break;
								}
							}else{
								$data["AdminID"] = $DB->NextID("Admin");
								$data["adGender"] = $_POST["Gender"];
								$data["adUsername"] = $_POST["Username"];
								$data["adUnder"] = $user->Under;
								$data["adRegion"] = $user->Region;
								$data["adProvince"] = $user->Province;
								$data["adArea"] = $user->Area;
								$data["adBranch"] = $user->Branch;
								$DB->InsertData("Admin",$data);
							}
							if($DB->GetAffectRows() > 0){
								$user->Message = "พบข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้";
							}else{
								$user->Message = "บันทึกข้อมูลสำเร็จ";
							}
						}
					}else{
						if(isset($_POST["Password"]) && ($_POST["Password"]!="")){
							$DB->UpdateData("Admin",array("adPassword" => $_POST["Password"]),"AdminID = ?",array("i",$user->id));
							if($DB->GetAffectRows() > 0){
								$user->Message = "พบข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้";
							}else{
								$user->Message = "บันทึกข้อมูลสำเร็จ";
							}
						}else{
							$user->Message = "ข้อมูลไม่เปลี่ยนแปลง";
						}
					}
				break;
			case "gettable" :
					$rpp = 10;
					$page = isset($_POST["page"])?$_POST["page"]:0;
					switch($user->Under){
						case 0 :
								$DB->GetData("SELECT AdminID AS ID, adGender AS Icon, CONCAT(adFullname,' [',adBranch,']') AS Fullname FROM `Admin` LIMIT ?,?",array("ii",$page,$rpp));
							break;
						case 1 :
								$DB->GetData("SELECT AdminID AS ID, adGender AS Icon, adFullname AS Fullname FROM `Admin` WHERE adUnder = 1 AND adRegion = ? LIMIT ?,?",array("iii",$user->Region,$page,$rpp));
							break;
						case 2 :
								$DB->GetData("SELECT AdminID AS ID, adGender AS Icon, adFullname AS Fullname FROM `Admin` WHERE adUnder = 2 AND adArea = ? LIMIT ?,?",array("iii",$user->Area,$page,$rpp));
							break;
						case 3 :
								$DB->GetData("SELECT AdminID AS ID, adGender AS Icon, adFullname AS Fullname FROM `Admin` WHERE adUnder = 3 AND adBranch = ? LIMIT ?,?",array("iii",$user->Branch,$page,$rpp));
							break;
						default : $DB->GetData("SELECT * FROM `Admin` WHERE 0");
					}
					$userList = new exUser_Table;
					$userList->Init($page,$rpp,$DB->GetNumRows());
					while($data = $DB->FetchData()){
						$userList->AddItem($data["ID"],$data["Icon"],$data["Fullname"]);
					}
					header("Access-Control-Allow-Origin: *");
					echo json_encode($userList);
					die;
				break;
			case "getdata" :
					$userdata = $DB->GetDataOneRow("SELECT `AdminID`, `adGender`, `adLevel`, `adUnder`, `adEform`, `adRegion`, `adProvince`, `adArea`, `adBranch`, `adUsername`, `adFullname`, `pvName`, `arName`, `brName`, `adStatus`, `adEmail`, `adTel`, `adMobile` FROM `Admin`,`Province`,`Area`, `Branch` WHERE adProvince = ProvinceID AND AreaID = adArea AND BranchID = adBranch AND AdminID = ?",array("i",$_POST["id"]));

					$uProfile = new exUser_Profile;
					$uProfile->Load2Profile($userdata);

					header("Access-Control-Allow-Origin: *");
					echo $uProfile->Export2JSON();
					die;
				break;
			case "autocomplete" :
					$Under = 0;
					switch($user->Under){
						case 0 :
								$DB->GetData("SELECT AdminID AS ID, adFullname, CONCAT(adFullname,' [',adBranch,']') AS Fullname FROM `Admin` WHERE adFullname LIKE ? LIMIT 10",array("s","%".$_POST["keyword"]."%"));
							break;
						case 1 :
								$DB->GetData("SELECT AdminID AS ID, adFullname, adFullname AS Fullname FROM `Admin` WHERE adUnder = 1 AND adRegion = ? AND adFullname LIKE ? LIMIT 10",array("is",$user->Region,"%".$_POST["keyword"]."%"));
							break;
						case 2 :
								$DB->GetData("SELECT AdminID AS ID, adFullname, adFullname AS Fullname FROM `Admin` WHERE adUnder = 2 AND adArea = ? AND adFullname LIKE ? LIMIT 10",array("is",$user->Area,"%".$_POST["keyword"]."%"));
							break;
						case 3 :
								$DB->GetData("SELECT AdminID AS ID, adFullname, adFullname AS Fullname FROM `Admin` WHERE adUnder = 3 AND adBranch = ? AND adFullname LIKE ? LIMIT 10",array("is",$user->Branch,"%".$_POST["keyword"]."%"));
							break;
						default : $DB->GetData("SELECT * FROM `Admin` WHERE 0");
					}

					$data = array();
					while($fdata = $DB->FetchData()){
						$sdata = new exItem;
						$sdata->id = $fdata["ID"];
						$sdata->value = $fdata["adFullname"];
						$sdata->label = $fdata["Fullname"];
						array_push($data,$sdata);
					}
					header("Access-Control-Allow-Origin: *");
					echo json_encode($data);
					die;
				break;
			default : $user->Message = "กรุณาเข้าสู่ระบบก่อนใช้งาน";
		}
	}else{
		if($user->id == 0) $user->Message = "กรุณาเข้าสู่ระบบก่อนใช้งาน";
	}

	header("Access-Control-Allow-Origin: *");
	echo json_encode($user);
?>
