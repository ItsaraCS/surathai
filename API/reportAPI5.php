<?php
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/report.class.php");

$fn = isset($_GET["fn"])?$_GET["fn"]:"";
$mode = isset($_GET["mode"])?$_GET["mode"]:0;

				$title = array(
					1 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","เลขรับที่และวันที่รับเรื่อง","ชื่อยี่ห้อ","ดีกรี","จำนวนขวด(ดวง)","ขนาดบรรจุ","ราคาแสตมป์ดวลละ","ปริมาณน้ำสุรา","ค่าภาษีสุรา","เล่มที่/เลขที่แสตมป์ที่จ่าย","วันที่จ่ายแสตมป์"),
					2 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตผลิต","เลขที่ใบอนุญาตผลิต","ยี่ห้อที่ผลิต","ดีกรี","ประเภท","วันที่อนุญาต","วันที่ต่อใบอนุญาต","สถานที่ตั้ง"),
					3 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตจำหน่าย","เลขที่ใบอนุญาตจำหน่ายสุรา","ประเภทใบอนุญาต","วันที่อนุญาต","วันที่ต่อใบอนุญาต","สถานที่ตั้งโรงงาน"),
					4 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","เลขรับที่และวันที่รับเรื่อง","ชื่อยี่ห้อ","ดีกรี","จำนวนขวด(ดวง)","ขนาดบรรจุ","ราคาแสตมป์ดวลละ","ปริมาณน้ำสุรา","ค่าภาษีสุรา","เล่มที่/เลขที่แสตมป์ที่จ่าย","วันที่จ่ายแสตมป์"),
					5 => array("เดือน","สุรากลั่น","สุราแช่","รวมทั้งหมด")
				);

switch($fn){
	case "gettable" :
				$RPP = 5;
				$year = isset($_GET["year"])?$_GET["year"]:2017;
				$region = isset($_GET["region"])?$_GET["region"]:0;
				$province = isset($_GET["province"])?$_GET["province"]:0;
				$page = isset($_GET["page"])?$_GET["page"]-1:0;
				$job = isset($_GET["job"])?$_GET["job"]:0;
				if(!in_array($job,array(1,21,22,31,32,33,34,4,5))) $job = 1;

				$TitleShow = $title[$job];
				if($mode==1) $TitleShow[0] = "ปี";
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
								$DB = new exDB;
								if($mode==0){
									$total = 12;
									$rdata = array(10,11,12,1,2,3,4,5,6,7,8,9);
									$DB->GetData("SELECT MONTH(faIssueDate) AS M, faSuraType, COUNT(FactoryID) AS C FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 15 MONTH) = ? GROUP BY M, faSuraType ORDER BY faIssueDate",array("iii",$region,$province,$year));
								}else{
									$total = 5;
									$rdata = array(1,2,3,4,5);
									$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 15 MONTH) AS Y, faSuraType, COUNT(FactoryID) AS C FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 15 MONTH) BETWEEN ? AND ? GROUP BY Y,faSuraType ORDER BY faIssueDate",array("iiii",$region,$province,$year-4,$year));
								}
        
								$data = new exReport_Table;
								$data->Init(1,$total,$total);
								if($total > 0){
									$tdata = array(array());
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}

									for($x=1;$x<=$total;$x++){
										if($total==5){
											$tdata[$x][0] = "ปีงบประมาณ ".(($year + 538) + $x);
										}else{
											$tdata[$x][0] = $etcObj->GetMonthFullName($x)." ".($x>9?$year+541:$year+542);
										}
										for($y=1;$y<=3;$y++){
											$tdata[$x][$y] = "-";
										}
									}

									while($fdata = $DB->FetchData()){
										if($total==5){
											$x = $fdata["Y"] - $year+5;
											$tdata[$x][$fdata["faSuraType"]] = $fdata["C"];
											$tdata[$x][3] = intval($tdata[$x][3]) + $fdata["C"];
										}else{
											$tdata[$fdata["M"]][$fdata["faSuraType"]] = $fdata["C"];
											$tdata[$fdata["M"]][$fdata["faSuraType"]] = $fdata["C"];
											$tdata[$fdata["M"]][3] = intval($tdata[$fdata["M"]][3]) + $fdata["C"];
										}
									}

									$i=0;
									foreach($rdata as $x){
										$data->AddCell($tdata[$x][0]);
										for($y=1;$y<=3;$y++){
											$data->AddCell($tdata[$x][$y],1);
										}
									}
								}
						break;
					case 2:
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
					case 3 :
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


					if(($data->job == 1)||($data->job == 4)||($data->job == 2)){
						$sdata = new exItem;
						$sdata->id = 1;
						$sdata->value = 2017;
						$sdata->label = "ปีงบประมาณ 2560";
						array_push($data->year,$sdata);
					}elseif(($data->job == 5)){
						$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 15 MONTH) AS fYear FROM Factory GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif($data->job == 3){
						$DB->GetData("SELECT YEAR(slExtendDate) AS fYear FROM `SaleLicense` GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
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
				$mode = isset($_GET["mode"])?$_GET["mode"]:0;
				$job = isset($_GET["job"])?$_GET["job"]:0;
				$year = isset($_GET["year"])?$_GET["year"]:9999;
				$region = isset($_GET["region"])?$_GET["region"]:0;
				$province = isset($_GET["province"])?$_GET["province"]:0;
				
				$ItemTitle= $title[$job];

				$data = new exChart;
				$data->minvalue = 999999999999;
				$data->maxvalue = 0;
				if($mode==1){
					$rdata = array();
					$data->labels = array();
					for($y=$year+539;$y<$year+544;$y++){
						array_push($rdata,$y-543);
						array_push($data->labels,$y);
					}
				}else{
					$rdata = array(10,11,12,1,2,3,4,5,6,7,8,9);
					$data->labels = array("ต.ค.","พ.ย.","ธ.ค.","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.");
				}
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
							if($mode==0){
								$DB->GetData("SELECT MONTH(faIssueDate) AS H, faSuraType AS V, COUNT(FactoryID) AS S FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 15 MONTH) = ? GROUP BY V,H ORDER BY V,faIssueDate",array("iii",$region,$province,$year));
							}else{
								$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 15 MONTH) AS H, faSuraType AS V, COUNT(FactoryID) AS S FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 15 MONTH) BETWEEN ? AND ? GROUP BY V,H ORDER BY V,faIssueDate",array("iiii",$region,$province,$year-4,$year));
							}
						break;
					case 2 :
							$DB->GetData("SELECT YEAR(lbIssueDate) AS Y, MONTH(lbIssueDate) AS M,COUNT(lbIssueDate) AS S FROM (SELECT lbIssueDate FROM `Stamp`,`Label`, `SuraType` WHERE stLabel = LabelID AND lbType = SuraTypeID AND YEAR(lbIssueDate) BETWEEN ? AND ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince) GROUP BY lbLicense ORDER BY lbIssueDate) AS X GROUP BY Y,M ORDER BY Y,M",array("iiii",$year - 4,$year,$region,$province));
					case 3 :
							$DB->GetData("SELECT YEAR(slExtendDate) AS Y, MONTH(slExtendDate) AS M, COUNT(slExtendDate) AS S FROM (SELECT slExtendDate, COUNT(`SaleLicenseID`) AS C FROM `SaleLicense`,`Factory` WHERE slFactoryID = FactoryID AND YEAR(slExtendDate) BETWEEN ? AND ? AND ? IN (0,faRegion) AND ? IN (0,faProvince) GROUP BY `SaleLicenseID`) AS X GROUP BY Y,M ORDER BY Y,M",array("iiii",$year - 4,$year,$region,$province));
					default :
				}

				if($DB->GetNumRows() > 0){
				    if($job==5){
					$CurV = 0;
					$CountV = 0;
					$VList = array();
					while($fdata = $DB->FetchData()){
						if($CurV != $fdata["V"]){
							$CurV = $fdata["V"];
							array_push($VList,$CurV);
							$CountV++;
						}
						$tmpData[$fdata["V"]][$fdata["H"]] = $fdata["S"];
						if(isset($tmpData[3][$fdata["H"]])){
							$tmpData[3][$fdata["H"]] += $fdata["S"];
						}else{
							$tmpData[3][$fdata["H"]] = $fdata["S"];
						}
					}
					for($i=1;$i<count($ItemTitle);$i++){
						$sdata = new exChart_Data;
						$sdata->label = $ItemTitle[$i];
						$sdata->data = array();
						foreach($rdata as $j){
							if(isset($tmpData[$i][$j])){
								if($tmpData[$i][$j] < $data->minvalue) $data->minvalue = $tmpData[$i][$j];
								if($tmpData[$i][$j] > $data->maxvalue) $data->maxvalue = $tmpData[$i][$j];
								array_push($sdata->data,$tmpData[$i][$j]);
							}else{
								array_push($sdata->data,null);
							}
						}
						array_push($data->datasets,$sdata);
					}
				    }else{
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
								array_push($sdata->data,$tmpData[$YearList[$i]][$j]);
							}else{
								array_push($sdata->data,null);
							}
						}
						array_push($data->datasets,$sdata);
					}
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
