<?php
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/report.class.php");
	require_once("../class/factory.class.php");

$fn = isset($_GET["fn"])?$_GET["fn"]:"";

switch($fn){
	case "gettable" :
				$data = new exReport_Table;
				$data->Init(1,10,10);
				switch($_GET["job"]){
					case 1: //โรงงาน
					case 2: //คดี
					case 3: //แสตมป์
							$DB = new exDB;
							$DB->GetData("SELECT ssBuyDate,ssStartNo,ssFinishNo,ssAmount,faName FROM (SELECT SaleStampID, ssBuyDate,ssStartNo,ssFinishNo,ssAmount,faName FROM `SaleStamp`,`Factory` WHERE FactoryID = ssFactoryID ORDER BY ssBuyDate DESC,SaleStampID LIMIT 10) AS X ORDER BY ssBuyDate");

							$TitleShow = array("วันที่","เลขแสตป์เริ่ม","เลขสแตมป์สิ้นสุด","จำนวนดวง","โรงงาน");
							
							for($i=0;$i<count($TitleShow);$i++){
								$data->AddLabel($TitleShow[$i]);
							}

							$etcObj = new exETC;
							while($fdata = $DB->FetchData()){
								$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["ssBuyDate"]));
								$data->AddCell($fdata["ssStartNo"]);
								$data->AddCell($fdata["ssFinishNo"]);
								$data->AddCell($fdata["ssAmount"],1);
								$data->AddCell($fdata["faName"]);
							}
						break;
				}
		break;
	case "getdata" :
				$DB = new exDB;
				switch($_GET["data"]){
					case 1: //โรงงาน
							$data = new exFactory;
							$data->Init(5,50);
							$sdata = $DB->GetDataOneRow("SELECT `FactoryID`, `faProvince`, `faRegion`, `faCapital`, `faWorker`, `faHP`, `faLat`, `faLong`, `faIssueDate`, `faLicenseNo`, `faRegistNo`, `faContact`, `faName`, `faAddress`, `pvName` FROM `Factory`,`Province` WHERE faProvince = ProvinceID AND ? IN (0,faProvince) AND ? IN (0,faRegion) AND FactoryID = ?",array("iii",$data->Province,$data->Region,$_GET["id"]));
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
							if(basename($_SERVER['HTTP_REFERER'])=='e_factory.php'){
								$sdata = new exItem;
								$sdata->id = 0;
								$sdata->value = $_GET["value"];
								$sdata->label = "เพิ่มโรงงานนี้";
								array_push($data,$sdata);
							}
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
	case "submit" :
                                parse_str($_GET["content"], $data_array);
                                switch($_GET["data"]){
                                        case 1://โรงงาน
							$result = 1;
							if(isset($_FILES["pic"])){
	                                                        $msg = "อัพรูป ".$_FILES["pic"]["name"]." ได้";
							}else{
	                                                        $msg = "อัพรูปไม่ได้";
							}
							if(isset($_GET["content"])){
	                                                        $msg .= " มี content ";
							}else{
	                                                        $msg .= " ไม่มี content";
							}
                                                break;
                                        case 2://คดี
							$result = 1;
                                                        $msg = "ยังทำอยู่ครับ";
                                                break;
                                        case 3://แสตมป์
							$DB = new exDB;
							if($data_array["CountStamp"] > 99){//ซื้อเต็มเล่ม
								$cStamp = intval($DB->GetDataOneField("SELECT SUM(srAmount) FROM `StampRemain` WHERE srAmount = 100 AND StampRemainID BETWEEN ? AND ?",array("ss",$data_array["StartStampNumber"],$data_array["EndStampNumber"])));
								if($cStamp == $data_array["CountStamp"]){
									$idata = array(
										"ssStartNo" => $data_array["StartStampNumber"],
										"ssFinishNo" => $data_array["EndStampNumber"],
										"ssAmount" => $data_array["CountStamp"],
										"ssFactoryID" => $data_array["FactoryName"],
										"ssBuyDate" => $DB->Now()
									);
									$DB->InsertData("SaleStamp",$idata);
									$DB->DeleteData("StampRemain","StampRemainID BETWEEN ? AND ? AND srAmount = 100",array("ss",$data_array["StartStampNumber"],$data_array["EndStampNumber"]));
									$result = 0;
									$msg = "ทำการจำหน่ายแสตมป์สำเร็จ";
								}else{
									$result = 1;
									$msg = "ไม่สามารถจำหน่ายได้ เนื่องจากไม่พบแสตมป์นี้ในฐานข้อมูล";
								}
							}else{
								$cStamp = intval($DB->GetDataOneField("SELECT srAmount FROM `StampRemain` WHERE StampRemainID = ?",array("s",$data_array["StartStampNumber"])));
								if($cStamp >= $data_array["CountStamp"]){
									$idata = array(
										"ssStartNo" => $data_array["StartStampNumber"],
										"ssFinishNo" => $data_array["StartStampNumber"],
										"ssAmount" => $data_array["CountStamp"],
										"ssFactoryID" => $data_array["FactoryName"],
										"ssBuyDate" => $DB->Now()
									);
									$DB->InsertData("SaleStamp",$idata);
									if($cStamp == $data_array["CountStamp"]){
										$DB->DeleteData("StampRemain","StampRemainID BETWEEN ? AND ? AND srAmount = 100",array("ss",$data_array["StartStampNumber"],$data_array["EndStampNumber"]));
									}else{
										$DB->UpdateData("StampRemain",array("srAmount" => ($cStamp - $data_array["CountStamp"])),"StampRemainID = ?",array("s",$data_array["StartStampNumber"]));
									}
									$result = 0;
									$msg = "ทำการจำหน่ายแสตมป์สำเร็จ";
								}else{
									$result = 1;
									$msg = "ไม่สามารถจำหน่ายได้ เนื่องจากไม่พบแสตมป์นี้ในฐานข้อมูล หรือ มีจำนวนไม่เพียงพอ";
								}
							}
                                                break;
                                }
                                $data = new exResult;
                                $data->ResultCode = $result;
                                $data->ResultMsg = $msg;
                        break;

	default : $data = null;
}
header("Access-Control-Allow-Origin: *");
echo json_encode($data);
?>
