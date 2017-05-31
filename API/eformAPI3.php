<?php
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/report.class.php");
	require_once("../class/factory.class.php");

$fn = isset($_GET["fn"])?$_GET["fn"]:"";

switch($fn){
	case "getdata" :
				$DB = new exDB;
				switch($_GET["data"]){
					case 1: //โรงงาน
							$data = new exFactory;
							$data->Init(5,50);
							$sdata = $DB->GetDataOneRow("SELECT `FactoryID`, `faProvince`, `faRegion`, `faCapital`, `faWorker`, `faHP`, `faLat`, `faLong`, `faIssueDate`, `faLicenseNo`, `faRegistNo`, `faContact`, `faName`, `faAddress`, `pvName` FROM `Factory`,`Province` WHERE faProvince = ProvinceID AND ? IN (0,faProvince) AND ? IN (0,faRegion) AND FactoryID = ?",array("iii",$data->Province,$data->Region,$_GET["id"]));
//							$sdata["faIssueDate"] = "xxxxx";
							$data->SaveData($sdata);
						break;
					case 2: //คดี
						break;
					case 3: //แสตมป์
							$data = intval($DB->GetDataOneField("SELECT SUM(srAmount) FROM `StampRemain` WHERE srAmount = 100 AND StampRemainID BETWEEN ? AND ?",array("ss",substr($_GET["id"],0,12),substr($_GET["id"],13))));
						break;
				}
			break;
	case "filter" :
				$DB = new exDB;
				$data = array();
				switch($_GET["src"]){
					case 1: //จังหวัด
							$DB->GetData("SELECT `ProvinceID`, `pvName` FROM `Province`");
						break;
					case 2: //ประเภท พรบ.
							$DB->GetData("SELECT `AreaID`, `arName` FROM `Area` WHERE ? IN (0,arProvince)",array("i",$_GET["value"]));
						break;
					case 3: //พื้นที่
							$DB->GetData("SELECT `ActID`, `acName` FROM `Act`");
						break;
					case 4: //ประเภท
							$DB->GetData("SELECT '1', 'โรงงาน'");
						break;
					default :
				}
				while($fdata = $DB->FetchData()){
					$sdata = new exItem;
					$sdata->id = $fdata[0];
					$sdata->value = $fdata[1];
					$sdata->label = $fdata[1];
					array_push($data,$sdata);
				}
			break;
	case "autocomplete" :
				switch($_GET["src"]){
					case 1: //โรงงาน
							$data = array();
        
							$DB = new exDB;
							$DB->GetData("SELECT `FactoryID`, `faName` FROM `Factory` WHERE faName LIKE ? LIMIT 10",array("s","%".$_GET["value"]."%"));
        
							while($fdata = $DB->FetchData()){
								$sdata = new exItem;
								$sdata->id = $fdata["FactoryID"];
								$sdata->value = $fdata["faName"];
								$sdata->label = $fdata["faName"];
								array_push($data,$sdata);
							}
							$sdata = new exItem;
							$sdata->id = 0;
							$sdata->value = $_GET["value"];
							$sdata->label = "เพิ่มโรงงานนี้";
							array_push($data,$sdata);
						break;
					case 2: //แสตมป์เต็มเล่ม
							$data = array();
        
							$DB = new exDB;
							$DB->GetData("SELECT StampRemainID FROM `StampRemain` WHERE srAmount = 100 AND srBranch = ? AND StampRemainID LIKE ? ORDER BY StampRemainID LIMIT 10",array("is",550101,$_GET["value"]."%"));
        
							while($fdata = $DB->FetchData()){
								$sdata = new exItem;
								$sdata->id = $fdata["StampRemainID"];
								$sdata->value = $fdata["StampRemainID"];
								$sdata->label = $fdata["StampRemainID"];
								array_push($data,$sdata);
							}
						break;
					case 3://แสตมป์แบ่งขาย
							$data = array();
        
							$DB = new exDB;
							$DB->GetData("SELECT StampRemainID,srAmount FROM `StampRemain` WHERE srBranch = ? AND StampRemainID LIKE ? ORDER BY srAmount, StampRemainID LIMIT 10",array("is",550101,$_GET["value"]."%"));
        
							while($fdata = $DB->FetchData()){
								$sdata = new exItem;
								$sdata->id = $fdata["StampRemainID"];
								$sdata->value = $fdata["StampRemainID"];
								$sdata->label = $fdata["StampRemainID"]." (".$fdata["srAmount"].")";
								array_push($data,$sdata);
							}
						break;
					default :
				}
			break;
	default : $data = null;
}
header("Access-Control-Allow-Origin: *");
echo json_encode($data);
?>
