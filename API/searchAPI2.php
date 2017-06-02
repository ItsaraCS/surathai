<?php
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/report.class.php");

$fn = isset($_GET["fn"])?$_GET["fn"]:"";

switch($fn){
	case "gettable" :
				$RPP = 5;
				$year = isset($_GET["year"])?$_GET["year"]:2017;
				$region = isset($_GET["region"])?$_GET["region"]:0;
				$province = isset($_GET["province"])?$_GET["province"]:0;
				$page = isset($_GET["page"])?$_GET["page"]-1:0;
				$job = isset($_GET["job"])?$_GET["job"]:0;
				if(!in_array($job,array(1,21,22,31,32,33,34,4,5))) $job = 1;
				$title = array(
					1 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","เลขรับที่และวันที่รับเรื่อง","ชื่อยี่ห้อ","ดีกรี","จำนวนขวด(ดวง)","ขนาดบรรจุ","ราคาแสตมป์ดวลละ","ปริมาณน้ำสุรา","ค่าภาษีสุรา","เล่มที่/เลขที่แสตมป์ที่จ่าย","วันที่จ่ายแสตมป์"),
					4 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","เลขรับที่และวันที่รับเรื่อง","ชื่อยี่ห้อ","ดีกรี","จำนวนขวด(ดวง)","ขนาดบรรจุ","ราคาแสตมป์ดวลละ","ปริมาณน้ำสุรา","ค่าภาษีสุรา","เล่มที่/เลขที่แสตมป์ที่จ่าย","วันที่จ่ายแสตมป์"),
					5 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขอก่อตั้งโรงงาน","เลขที่ใบอนุญาตก่อตั้งโรงงาน","ประเภท","วันที่อนุญาต","สถานที่ตั้งโรงงาน"),
					21 => array("ลำดับที่","พรบ","วันที่เกิดเหตุ","ผู้กล่าวหา/ผู้ต้องหา","สถานที่เกิดเหตุ","ข้อกล่าวหา","ของกลาง/จำนวน","เปรียบเทียบ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					22 => array("ลำดับที่","พรบ","วันที่เกิดเหตุ","ผู้กล่าวหา/ผู้ต้องหา","สถานที่เกิดเหตุ","ข้อกล่าวหา","ของกลาง/จำนวน","เปรียบเทียบ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					31 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขอก่อตั้งโรงงาน","เลขที่ใบอนุญาตก่อตั้งโรงงาน","ประเภท","วันที่อนุญาต","สถานที่ตั้งโรงงาน"),
					32 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตผลิต","เลขที่ใบอนุญาตผลิต","ยี่ห้อที่ผลิต","ดีกรี","ประเภท","วันที่อนุญาต","วันที่ต่อใบอนุญาต","สถานที่ตั้ง"),
					33 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตจำหน่าย","เลขที่ใบอนุญาตจำหน่ายสุรา","ประเภทใบอนุญาต","วันที่อนุญาต","วันที่ต่อใบอนุญาต","สถานที่ตั้งโรงงาน"),
					34 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตออกใบขน","เลขที่ใบอนุญาตขนสุรา","ประเภท","วันที่ออกใบขน","ชื่อยี่ห้อสินค้า","ดีกรี","จำนวน(ขวด)","เล่มที่/เลขที่แสตมป์สุราที่ขน","สถานที่ปลายทางในการขนสุรา")
				);
				$TitleShow = $title[$job];
				$colnum = count($TitleShow);

				switch($job){
					case 1:
					case 4:
							$DB = new exDB;
							$total = $DB->GetDataOneField("SELECT count(StampID) FROM `Stamp`,`Label` WHERE stLabel = LabelID AND YEAR(stReleaseDate) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince)",array("iii",$year,$region,$province));
							$DB->GetData("SELECT lbFacName, stFacCode, stNumber, lbBrand, lbDegree, stAmount, stSize, stPrice, stVolume, stTax, stBookNo, stReleaseDate FROM `Stamp`,`Label` WHERE stLabel = LabelID AND YEAR(stReleaseDate) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince) LIMIT ?,?",array("iiiii",$year,$region,$province,$page*$RPP,$RPP));
        
							$datarow = $DB->GetNumRows();
        
							$data = new exReport_Table;
							$data->Init($page+1,$RPP,$total);
							if($total > 0){
								$etcObj = new exETC;
								for($i=0;$i<$colnum;$i++){
									$data->AddLabel($TitleShow[$i]);
								}
								for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
									$data->AddCell($x,1);
									$data->AddCell($fdata["lbFacName"]);
									$data->AddCell($fdata["stFacCode"]);
									$data->AddCell(is_null($fdata["lbNumber"])?"-":$fdata["lbNumber"],2);
									$data->AddCell($fdata["lbBrand"]);
									$data->AddCell($fdata["lbDegree"],2);
									$data->AddCell(number_format($fdata["stAmount"]),1);
									$data->AddCell(number_format($fdata["stSize"],3),1);
									$data->AddCell(number_format($fdata["stPrice"],4),1);
									$data->AddCell(number_format($fdata["stVolume"],2),1);
									$data->AddCell(number_format($fdata["stTax"],2),1);
									$data->AddCell($fdata["stBookNo"]);
									$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["stReleaseDate"]));
								}
							}
						break;
					case 5:
					case 31:
								$DB = new exDB;
								$total = $DB->GetDataOneField("SELECT count(FactoryID) FROM `Factory` WHERE YEAR(faIssueDate) = ? AND ? IN (0,faRegion) AND ? IN (0,faProvince)",array("iii",$year,$region,$province));
								$DB->GetData("SELECT faName, FactoryID, faContact, faLicenseNo, suName, faIssueDate, faAddress FROM `Factory`,`SuraType` WHERE faSuraType = SuraTypeID AND YEAR(faIssueDate) = ? AND ? IN (0,faRegion) AND ? IN (0,faProvince) LIMIT ?,?",array("iiiii",$year,$region,$province,$page*$RPP,$RPP));
        
								$datarow = $DB->GetNumRows();
        
								$data = new exReport_Table;
								$data->Init($page+1,$RPP,$total);
								if($total > 0){
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}
									for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
										$data->AddCell($x,1);
										$data->AddCell($fdata["faName"]);
										$data->AddCell($fdata["FactoryID"]);
										$data->AddCell($fdata["faContact"]);
										$data->AddCell($fdata["faLicenseNo"],2);
										$data->AddCell($fdata["suName"]);
										$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["faIssueDate"]));
										$data->AddCell($fdata["faAddress"]);
									}
								}
						break;
					case 32:
							$DB = new exDB;
							$total = $DB->GetDataOneField("SELECT COUNT(lbLicense) FROM (SELECT lbLicense FROM `Stamp`,`Label`, `SuraType` WHERE stLabel = LabelID AND lbType = SuraTypeID AND YEAR(stReleaseDate) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince) GROUP BY lbLicense ORDER BY lbLicense) AS X",array("iii",$year,$region,$province));
							$DB->GetData("SELECT lbFacName, stFacCode, lbContact, lbLicense, lbBrand, lbDegree, suName, lbIssueDate, lbExpireDate,lbAddress FROM `Stamp`,`Label`, `SuraType` WHERE stLabel = LabelID AND lbType = SuraTypeID AND YEAR(stReleaseDate) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince) GROUP BY lbLicense ORDER BY lbLicense LIMIT ?,?",array("iiiii",$year,$region,$province,$page*$RPP,$RPP));
        
							$datarow = $DB->GetNumRows();
        
							$data = new exReport_Table;
							$data->Init($page+1,$RPP,$total);
							if($total > 0){
								$etcObj = new exETC;
								for($i=0;$i<$colnum;$i++){
									$data->AddLabel($TitleShow[$i]);
								}
								for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
									$data->AddCell($x,1);
									$data->AddCell($fdata["lbFacName"]);
									$data->AddCell($fdata["stFacCode"]);
									$data->AddCell($fdata["lbContact"]);
									$data->AddCell($fdata["lbLicense"]);
									$data->AddCell($fdata["lbBrand"]);
									$data->AddCell($fdata["lbDegree"],2);
									$data->AddCell($fdata["suName"]);
									$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["lbIssueDate"]));
									$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["lbExpireDate"]));
									$data->AddCell($fdata["lbAddress"]);
								}
							}
						break;
					case 33 :
							$DB = new exDB;
							$total = $DB->GetDataOneField("SELECT COUNT(*) FROM (SELECT COUNT(`SaleLicenseID`) FROM `SaleLicense`,`Factory` WHERE slFactoryID = FactoryID AND YEAR(slExtendDate) = ? AND ? IN (0,faRegion) AND ? IN (0,faProvince) GROUP BY `SaleLicenseID`) AS X",array("iii",$year,$region,$province));
							$DB->GetData("SELECT `faName`, `FactoryID`, `faContact`, `SaleLicenseID`, `ltName`, `slIssueDate`, `slExtendDate`, `faAddress` FROM (SELECT `faRegion`,`faProvince`,`faName`, `FactoryID`, `faContact`, `SaleLicenseID`, `ltName`, `slIssueDate`, `slExtendDate`, `faAddress` FROM `SaleLicense`, `Factory`, `LicenseType` WHERE slFactoryID = FactoryID AND slType = LicenseTypeID GROUP BY SaleLicenseID ORDER BY SaleLicenseID) AS X WHERE YEAR(slExtendDate) = ? AND ? IN (0,faRegion) AND ? IN (0,faProvince) LIMIT ?,?",array("iiiii",$year,$region,$province,$page*$RPP,$RPP));
        
							$datarow = $DB->GetNumRows();
        
							$data = new exReport_Table;
							$data->Init($page+1,$RPP,$total);
							if($total > 0){
								$etcObj = new exETC;
								for($i=0;$i<$colnum;$i++){
									$data->AddLabel($TitleShow[$i]);
								}
								for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
									$data->AddCell($x,1);
									$data->AddCell($fdata["faName"]);
									$data->AddCell($fdata["FactoryID"]);
									$data->AddCell($fdata["faContact"]);
									$data->AddCell($fdata["SaleLicenseID"]);
									$data->AddCell($fdata["ltName"],2);
									$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["slIssueDate"]));
									$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["slExtendDate"]));
									$data->AddCell($fdata["faAddress"]);
								}
							}
						break;
					default:
							$data = new exReport_Table;
							$data->Init($page+1,$RPP,100);
							
							for($i=0;$i<$colnum;$i++){
								$data->AddLabel($TitleShow[$i]);
							}
							for($i=0;$i < $RPP;$i++){
								for($j=0;$j<$colnum - 1;$j++){
									$data->AddCell("dummy".rand(10000,99999));
								}
								$data->AddCell(rand(1000000,9999999)/100,1);
                                                        }
				}
			break;
	case "filter" :
				$DB = new exDB;
				if($_GET["src"] == 0){
					$data = new exFilter_Bar;
					$data->year = array();
					$data->region = array();
					$data->province = array();
					$data->job = isset($_GET["job"])?$_GET["job"]:1;


					if(($data->job == 1)||($data->job == 4)||($data->job == 32)){
						$sdata = new exItem;
						$sdata->id = 1;
						$sdata->value = 2017;
						$sdata->label = "ปีงบประมาณ 2560";
						array_push($data->year,$sdata);
					}elseif(($data->job == 5)||($data->job == 31)){
						$DB->GetData("SELECT YEAR(faIssueDate) AS fYear FROM Factory GROUP BY YEAR(faIssueDate) ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif($data->job == 33){
						$DB->GetData("SELECT YEAR(slExtendDate) AS fYear FROM `SaleLicense` GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif(($data->job == 21)||($data->job == 22)){
						$sdata = new exItem;
						$sdata->id = 1;
						$sdata->value = 2015;
						$sdata->label = "ปีงบประมาณ 2558";
						array_push($data->year,$sdata);
						$sdata = new exItem;
						$sdata->id = 2;
						$sdata->value = 2016;
						$sdata->label = "ปีงบประมาณ 2559";
						array_push($data->year,$sdata);
					}else{
						$sdata = new exItem;
						$sdata->id = 1;
						$sdata->value = 2016;
						$sdata->label = "ปีงบประมาณ 2559";
						array_push($data->year,$sdata);
					}


					$sdata = new exItem;
					$sdata->id = 0;
					$sdata->value = 0;
					$sdata->label = "ทุกภาค";
					array_push($data->region,$sdata);

					$DB->GetData("SELECT RegionID, rgNameTH FROM `Region`");
					while($fdata = $DB->FetchData()){
						$sdata = new exItem;
						$sdata->id = $fdata["RegionID"];
						$sdata->value = $fdata["RegionID"];
						$sdata->label = $fdata["rgNameTH"];
						array_push($data->region,$sdata);
					}

					$sdata = new exItem;
					$sdata->id = 0;
					$sdata->value = 0;
					$sdata->label = "ทุกจังหวัด";
					array_push($data->province,$sdata);

					$DB->GetData("SELECT `ProvinceID`, `pvName` FROM `Province`");
					while($fdata = $DB->FetchData()){
						$sdata = new exItem;
						$sdata->id = $fdata["ProvinceID"];
						$sdata->value = $fdata["ProvinceID"];
						$sdata->label = $fdata["pvName"];
						array_push($data->province,$sdata);
					}
				}else{
					$S_region = isset($_GET["value"])?intval($_GET["value"]):0;
					$DB->GetData("SELECT `ProvinceID`, `pvName` FROM `Province` WHERE ? IN (0,pvRegion)",array("i",$S_region));

					if($DB->GetNumRows()>0){
						$data = array();
						$sdata = new exItem;
						$sdata->id = 0;
						$sdata->value = 0;
						$sdata->label = "ทุกจังหวัด";
						array_push($data,$sdata);

						while($fdata = $DB->FetchData()){
							$sdata = new exItem;
							$sdata->id = $fdata["ProvinceID"];
							$sdata->value = $fdata["ProvinceID"];
							$sdata->label = $fdata["pvName"];
							array_push($data,$sdata);
						}
					}else{
						$data = null;
					}
				}
			break;
	case "getgraph" :
				$job = isset($_GET["job"])?$_GET["job"]:0;
				$year = isset($_GET["year"])?$_GET["year"]:9999;
				$region = isset($_GET["region"])?$_GET["region"]:0;
				$province = isset($_GET["province"])?$_GET["province"]:0;
				
				$data = new exChart;
				$data->minvalue = 999999999999;
				$data->maxvalue = 0;
				$data->labels = array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				$data->datasets = array();

				$DB = new exDB;
				switch($job){
					case 1 :
							$DB->GetData("SELECT YEAR(stReleaseDate) AS Y, MONTH(stReleaseDate) AS M, SUM(stTax) AS S FROM (SELECT stTax, stReleaseDate FROM `Stamp`,`Label` WHERE stLabel = LabelID AND YEAR(stReleaseDate) BETWEEN ? AND ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince)) AS X  GROUP BY Y,M ORDER BY Y,M",array("iiii",$year - 4,$year,$region,$province));
						break;
					case 4 :
							$DB->GetData("SELECT YEAR(stReleaseDate) AS Y, MONTH(stReleaseDate) AS M, SUM(stAmount) AS S FROM (SELECT stAmount, stReleaseDate FROM `Stamp`,`Label` WHERE stLabel = LabelID AND YEAR(stReleaseDate) BETWEEN ? AND ?  AND ? IN (0,lbRegion) AND ? IN (0,lbProvince)) AS X  GROUP BY Y,M ORDER BY Y,M",array("iiii",$year - 4,$year,$region,$province));
						break;
					case 5 :
					case 31 :
							$DB->GetData("SELECT YEAR(faIssueDate) AS Y, MONTH(faIssueDate) AS M, COUNT(FactoryID) AS S FROM `Factory` WHERE (YEAR(faIssueDate) BETWEEN ? AND ?) AND ? IN (0,faRegion) AND ? IN (0, faProvince) GROUP BY Y,M ORDER BY Y,M",array("iiii",$year - 4,$year,$region,$province));
						break;
					case 32 :
							$DB->GetData("SELECT YEAR(lbIssueDate) AS Y, MONTH(lbIssueDate) AS M,COUNT(lbIssueDate) AS S FROM (SELECT lbIssueDate FROM `Stamp`,`Label`, `SuraType` WHERE stLabel = LabelID AND lbType = SuraTypeID AND YEAR(lbIssueDate) BETWEEN ? AND ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince) GROUP BY lbLicense ORDER BY lbIssueDate) AS X GROUP BY Y,M ORDER BY Y,M",array("iiii",$year - 4,$year,$region,$province));
					case 33 :
							$DB->GetData("SELECT YEAR(slExtendDate) AS Y, MONTH(slExtendDate) AS M, COUNT(slExtendDate) AS S FROM (SELECT slExtendDate, COUNT(`SaleLicenseID`) AS C FROM `SaleLicense`,`Factory` WHERE slFactoryID = FactoryID AND YEAR(slExtendDate) BETWEEN ? AND ? AND ? IN (0,faRegion) AND ? IN (0,faProvince) GROUP BY `SaleLicenseID`) AS X GROUP BY Y,M ORDER BY Y,M",array("iiii",$year - 4,$year,$region,$province));
					default :
				}

				if($DB->GetNumRows() > 0){
					$CurYear = 0;
					$CountYear = 0;
					$YearList = array();
					while($fdata = $DB->FetchData()){
						if($CurYear != $fdata["Y"]){
							$CurYear = $fdata["Y"];
							array_push($YearList,$CurYear);
							$CountYear++;
						}
						$tmpData[$fdata["Y"]][$fdata["M"] - 1] = $fdata["S"];
					}

					for($i=0;$i<$CountYear;$i++){
						$sdata = new exChart_Data;
						$sdata->label = "ปี ".($YearList[$i] + 543);
						$sdata->data = array();
						for($j=0;$j<12;$j++){
							if(isset($tmpData[$YearList[$i]][$j])){
								if($tmpData[$YearList[$i]][$j] < $data->minvalue) $data->minvalue = $tmpData[$YearList[$i]][$j];
								if($tmpData[$YearList[$i]][$j] > $data->maxvalue) $data->maxvalue = $tmpData[$YearList[$i]][$j];
								//array_push($sdata->data,number_format($tmpData[$YearList[$i]][$j]));
								array_push($sdata->data,$tmpData[$YearList[$i]][$j]);
							}else{
								array_push($sdata->data,null);
							}
						}
						array_push($data->datasets,$sdata);
					}
				}else{
					if($job==0){
						for($i=0;$i<5;$i++){
							$sdata = new exChart_Data;
							$sdata->label = "ปี 255".($i+5);
							$sdata->data = array();
							for($j=0;$j<12;$j++){
								$randomValue = rand(1000000,5000000)/100;
								if($randomValue < $data->minvalue) $data->minvalue = $randomValue;
								if($randomValue > $data->maxvalue) $data->maxvalue = $randomValue;
								array_push($sdata->data,$randomValue,2);
							}
							array_push($data->datasets,$sdata);
						}
					}
				}
				if($data->minvalue == 999999999999) $data->minvalue = 0;
			break;
	default : $data = null;
}
header("Access-Control-Allow-Origin: *");
echo json_encode($data);
?>
