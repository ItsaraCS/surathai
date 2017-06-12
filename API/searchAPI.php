<?php
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/report.class.php");
	require_once("../class/search.class.php");

$fn = isset($_POST["fn"])?$_POST["fn"]:"";

switch($fn){
	case "gettable" :
				$RPP = 5;
				$year = isset($_POST["year"])?$_POST["year"]:0;
				$region = isset($_POST["region"])?$_POST["region"]:0;
				$province = isset($_POST["province"])?$_POST["province"]:0;
				$page = isset($_POST["page"])?$_POST["page"]-1:0;
				$job = isset($_POST["job"])?$_POST["job"]:0;
				$menu = isset($_POST["menu"])?$_POST["menu"]:0;
				$Keyword = isset($_POST["keyword"])?$_POST["keyword"]:"";
				if(!in_array($job,array(1,2,3,4,5))) $job = 1;
				$title = array(
					10 => array("ลำดับที่","ชื่อสถานประกอบการ","ค่าธรรมเนียมใบอนุญาตก่อสร้าง","ค่าธรรมเนียมใบอนุญาตผลิต","ค่าธรรมเนียมใบอนุญาตจำหน่าย","ค่าธรรมเนียมใบอนุญาตขน","จำหน่ายแสตมป์สุรา"),
					11 => array("ลำดับที่","ชื่อสถานประกอบการ","ค่าธรรมเนียมใบอนุญาตก่อสร้าง","ค่าธรรมเนียมใบอนุญาตผลิต","ค่าธรรมเนียมใบอนุญาตจำหน่าย","ค่าธรรมเนียมใบอนุญาตขน","จำหน่ายแสตมป์สุรา"),
					12 => array("ลำดับที่","ชื่อสถานประกอบการ","ค่าธรรมเนียมใบอนุญาตก่อสร้าง","ค่าธรรมเนียมใบอนุญาตผลิต","ค่าธรรมเนียมใบอนุญาตจำหน่าย","ค่าธรรมเนียมใบอนุญาตขน","จำหน่ายแสตมป์สุรา"),
					13 => array("ลำดับที่","ชื่อสถานประกอบการ","ค่าธรรมเนียมใบอนุญาตก่อสร้าง","ค่าธรรมเนียมใบอนุญาตผลิต","ค่าธรรมเนียมใบอนุญาตจำหน่าย","ค่าธรรมเนียมใบอนุญาตขน","จำหน่ายแสตมป์สุรา"),
					14 => array("ลำดับที่","ชื่อสถานประกอบการ","ค่าธรรมเนียมใบอนุญาตก่อสร้าง","ค่าธรรมเนียมใบอนุญาตผลิต","ค่าธรรมเนียมใบอนุญาตจำหน่าย","ค่าธรรมเนียมใบอนุญาตขน","จำหน่ายแสตมป์สุรา"),
					15 => array("ลำดับที่","ชื่อสถานประกอบการ","ค่าธรรมเนียมใบอนุญาตก่อสร้าง","ค่าธรรมเนียมใบอนุญาตผลิต","ค่าธรรมเนียมใบอนุญาตจำหน่าย","ค่าธรรมเนียมใบอนุญาตขน","จำหน่ายแสตมป์สุรา"),
					20 => array("ลำดับที่","พรบ","วันที่เกิดเหตุ","ผู้กล่าวหา/ผู้ต้องหา","สถานที่เกิดเหตุ","ข้อกล่าวหา","ของกลาง/จำนวน","เปรียบเทียบปรับ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					21 => array("ลำดับที่","พรบ","วันที่เกิดเหตุ","ผู้กล่าวหา/ผู้ต้องหา","สถานที่เกิดเหตุ","ข้อกล่าวหา","ของกลาง/จำนวน","เปรียบเทียบปรับ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					22 => array("ลำดับที่","พรบ","วันที่เกิดเหตุ","ผู้กล่าวหา/ผู้ต้องหา","สถานที่เกิดเหตุ","ข้อกล่าวหา","ของกลาง/จำนวน","เปรียบเทียบปรับ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					23 => array("ลำดับที่","พรบ","วันที่เกิดเหตุ","ผู้กล่าวหา/ผู้ต้องหา","สถานที่เกิดเหตุ","ข้อกล่าวหา","ของกลาง/จำนวน","เปรียบเทียบปรับ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					24 => array("ลำดับที่","พรบ","วันที่เกิดเหตุ","ผู้กล่าวหา/ผู้ต้องหา","สถานที่เกิดเหตุ","ข้อกล่าวหา","ของกลาง/จำนวน","เปรียบเทียบปรับ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					30 => array("ลำดับที่","ชื่อสถานประกอบการโรงงาน","รหัสทะเบียนโรงงาน","เลขที่ใบอนุญาตก่อตั้งโรงงาน","เลขที่ใบอนุญาตผลิต","เลขที่ใบอนุญาตจำหน่ายสุรา","เลขที่ใบอนุญาตขนสุรา"),
/*					31 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขอก่อตั้งโรงงาน","เลขที่ใบอนุญาตก่อตั้งโรงงาน","ประเภท","วันที่อนุญาต","สถานที่ตั้งโรงงาน"),
					32 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตผลิต","เลขที่ใบอนุญาตผลิต","ยี่ห่อที่ผลิต","ดีกรี","ประเภท","วันที่อนุญาต","วันที่ต่อใบอนุญาต","สถานที่ตั้ง"),
					33 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตจำหน่าย","เลขที่ใบอนุญาตจำหน่ายสุรา","ประเภทใบอนุญาต","วันที่อนุญาต","วันที่ต่อใบอนุญาต","สถานที่ตั้งโรงงาน"),
					34 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขออนุญาตออกใบขน","เลขที่ใบอนุญาตขนสุรา","ประเภท","วันที่ออกใบขน","ชื่อยี่ห่อสินค้า","ดีกรี","จำนวน(ขวด)","เล่มที่/เลขที่แสตมป์สุราที่ขน","สถานที่ปลายทางในการขนสุรา"),*/
					40 => array("ลำดับที่","เล่มที่/เลขที่แสตมป์ที่จ่าย","ชื่อสถานประกอบการโรงงาน","รหัสทะเบียนโรงงาน","เลขรับที่และวันที่รับเรื่อง","ชื่อยี่ห่อ","ดีกรี","จำนวนขวด(ดวง)","ขนาดบรรจุ","ราคาแสตมป์ดวงละ","ปริมาณน้ำสุรา","ค่าภาษีสุรา","วันที่จ่ายแสตมป์"),
					41 => array("ลำดับที่","เล่มที่/เลขที่แสตมป์ที่จ่าย","ชื่อสถานประกอบการโรงงาน","รหัสทะเบียนโรงงาน","เลขรับที่และวันที่รับเรื่อง","ชื่อยี่ห่อ","ดีกรี","จำนวนขวด(ดวง)","ขนาดบรรจุ","ราคาแสตมป์ดวงละ","ปริมาณน้ำสุรา","ค่าภาษีสุรา","วันที่จ่ายแสตมป์"),
					42 => array("ลำดับที่","เล่มที่/เลขที่แสตมป์ที่จ่าย","ชื่อสถานประกอบการโรงงาน","รหัสทะเบียนโรงงาน","เลขรับที่และวันที่รับเรื่อง","ชื่อยี่ห่อ","ดีกรี","จำนวนขวด(ดวง)","ขนาดบรรจุ","ราคาแสตมป์ดวงละ","ปริมาณน้ำสุรา","ค่าภาษีสุรา","วันที่จ่ายแสตมป์"),
					50 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขอก่อตั้งโรงงาน","เลขที่ใบอนุญาตก่อตั้งโรงงาน","ประเภท","วันที่อนุญาต","สถานที่ตั้งโรงงาน","ยี่ห้อ","รูปฉลาก","แผนผังโรงงานและอุปกรณ์","ผลตรวจโรงงาน"),
					51 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขอก่อตั้งโรงงาน","เลขที่ใบอนุญาตก่อตั้งโรงงาน","ประเภท","วันที่อนุญาต","สถานที่ตั้งโรงงาน","ยี่ห้อ","รูปฉลาก","แผนผังโรงงานและอุปกรณ์","ผลตรวจโรงงาน"),
					52 => array("ลำดับที่","ชื่อสถานประกอบการ","รหัสทะเบียนโรงงาน","ชื่อผู้ขอก่อตั้งโรงงาน","เลขที่ใบอนุญาตก่อตั้งโรงงาน","ประเภท","วันที่อนุญาต","สถานที่ตั้งโรงงาน","ยี่ห้อ","รูปฉลาก","แผนผังโรงงานและอุปกรณ์","ผลตรวจโรงงาน"),
				);
				$TitleShow = $title[$job*10 + $menu];
				$colnum = count($TitleShow);


				if($year + $region + $province == 0){
					$data = new exSearch_Table;
					$data->Init($job,$page+1,$RPP,0);
					$etcObj = new exETC;
					for($i=0;$i<$colnum;$i++){
						$data->AddLabel($TitleShow[$i]);
					}
				}else{
					switch($job){
						case 1:
								$DB = new exDB;
								list($s1,$s2,$s3,$s4,$s5,$c1,$c2,$c3,$c4,$c5) = $DB->GetDataOneRow("SELECT SUM(BIL), SUM(PRL), SUM(SAL), SUM(TPL), SUM(STL), COUNT(BIL), COUNT(PRL), COUNT(SAL), COUNT(TPL), COUNT(STL)  FROM (SELECT ? AS Y, 0 AS BIL, 0 AS PRL, 0 AS SAL, 0 AS TPL, (SELECT SUM(stTax) FROM `Stamp` WHERE YEAR(stReleaseDate - INTERVAL 3 MONTH) = Y AND stFacCode = FactoryID) AS STL FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND faName LIKE ?) AS XX",array("iiis",$year,$region,$province,"%".$Keyword."%"));
        
								$total = max($c1,$c2,$c3,$c4,$c5);
        
        
								$DB->GetData("SELECT ? AS Y, faName, 0 AS BIL, 0 AS PRL, 0 AS SAL, 0 AS TPL, (SELECT SUM(stTax) FROM `Stamp` WHERE YEAR(stReleaseDate - INTERVAL 3 MONTH) = Y AND stFacCode = FactoryID) AS STL, faLat, faLong FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND faName LIKE ? LIMIT ?,?",array("iiisii",$year,$region,$province,"%".$Keyword."%",$page*$RPP,$RPP));
        
        
								$data = new exSearch_Table;
								$data->Init(1,$page+1,$RPP,$total,array($s1,$s2,$s3,$s4,$s5,($s1+$s2+$s3+$s4+$s5)));
								if($total > 0){
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}
									for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
										$data->AddCell($x,1);
										$data->AddCell($fdata["faName"]);
										$data->AddCell(number_format($fdata["BIL"]),1);
										$data->AddCell(number_format($fdata["PRL"],3),1);
										$data->AddCell(number_format($fdata["SAL"],4),1);
										$data->AddCell(number_format($fdata["TPL"],4),1);
										$data->AddCell(number_format($fdata["STL"],2),1);
										$data->AddLatLong($x,$fdata["faLat"],$fdata["faLong"]);
									}
								}
							break;
						case 2:
								$DB = new exDB;
								$DB->GetData("SELECT ilCase, COUNT(IllegalID) AS C FROM Illegal WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? AND ? IN (0,ilRegion) AND ? IN (0,MOD(FLOOR(ilArea/10),100)) AND CONCAT(ilOrator,'#',ilSuspect) LIKE ? GROUP BY ilCase ORDER BY ilCase",array("iiis",$year,$region,$province,"%".$Keyword."%"));
                                                        
								$ldata = array();
								while ($tdata = $DB->FetchData()) {
									$ldata[$tdata["ilCase"]-1] = $tdata["C"];
								}
                                                        
								$total = $DB->GetDataOneField("SELECT COUNT(IllegalID) FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? AND ? IN (0,ilRegion) AND ? IN (0,MOD(FLOOR(ilArea/10),100)) AND ? IN (0,ilCase) AND CONCAT(ilOrator,'#',ilSuspect) LIKE ?",array("iiiis",$year,$region,$province,$menu,"%".$Keyword."%"));
                                                        
								array_push($ldata,$total);
//								array_shift($ldata);
                                                        
                                                        
								$DB->GetData("SELECT acName,ilActDate,CONCAT('\(ก\)',ilOrator,'/\(ต\)',ilSuspect) AS Person,ilAddress,ilAllegation,ilMaterial,ilComparativeMoney,ilFine,ilOfficer,ilBribe,IlReward,ilReturn,ilLat,ilLong FROM `Illegal`,`Act` WHERE ilActType = ActID AND YEAR(ilActDate + INTERVAL 3 MONTH) =  ? AND ? IN (0,ilRegion) AND ? IN (0,MOD(FLOOR(ilArea/10),100)) AND ? IN (0,ilCase) AND CONCAT(ilOrator,'#',ilSuspect) LIKE ? LIMIT ?,?",array("iiiisii",$year,$region,$province,$menu,"%".$Keyword."%",$page*$RPP,$RPP));
                                                        
								$data = new exSearch_Table;
								$data->Init(2,$page+1,$RPP,$total,$ldata);
                                                        
								if($total > 0){
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}
									for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
										$data->AddCell($x,1);
										$data->AddCell($fdata["acName"]);
										$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["ilActDate"]));
										$data->AddCell($fdata["Person"]);
										$data->AddCell($fdata["ilAddress"]);
										$data->AddCell($fdata["ilAllegation"]);
										$data->AddCell($fdata["ilMaterial"]);
										$data->AddCell(number_format($fdata["ilComparativeMoney"],2),1);
										$data->AddCell(number_format($fdata["ilFine"],2),1);
										$data->AddCell(number_format($fdata["ilOfficer"],2),1);
										$data->AddCell(number_format($fdata["ilBribe"],2),1);
										$data->AddCell(number_format($fdata["IlReward"],2),1);
										$data->AddCell(number_format($fdata["ilReturn"],2),1);
										$data->AddLatLong($x,$fdata["ilLat"],$fdata["ilLong"]);
									}
								}
							break;
						case 3:
								$DB = new exDB;
        
								$ldata = array();
        
								list($total,$ldata[0],$ldata[1],$ldata[2],$ldata[3]) = $DB->GetDataOneRow("SELECT COUNT(faName), COUNT(FL), SUM(IF(ISNULL(PL),0,1)), SUM(IF(ISNULL(TL),0,1)), SUM(IF(ISNULL(SL),0,1))  FROM (SELECT ? AS Y, faName, faCode, '' AS FL,(SELECT lbLicense FROM `Label` WHERE lbFacCode = FactoryID AND YEAR(lbExpireDate + INTERVAL 3 MONTH) = Y LIMIT 1) AS PL,(SELECT tpLicense FROM Transport WHERE tpFactory = FactoryID AND YEAR(tpDate + INTERVAL 3 MONTH) = Y LIMIT 1) AS TL, (SELECT SaleLicenseID FROM SaleLicense WHERE slFactoryID = FactoryID AND YEAR(slExtendDate + INTERVAL 3 MONTH) = Y LIMIT 1) AS SL FROM Factory WHERE ? IN (0,faRegion) AND ? IN (0,faProvince)) AS AllData WHERE CONCAT(FL,'@',IF(ISNULL(PL),'',PL),'@',IF(ISNULL(TL),'',TL),'@',IF(ISNULL(SL),'',SL)) LIKE ?",array("iiis",$year,$region,$province,"%".$Keyword."%"));
        
        
								array_push($ldata,$total);
        
        
								$DB->GetData("SELECT faName, faCode, FL, PL, TL, SL, faLat, faLong  FROM (SELECT ? AS Y, faName, faCode, faCode AS FL,(SELECT lbLicense FROM `Label` WHERE lbFacCode = FactoryID AND YEAR(lbExpireDate + INTERVAL 3 MONTH) = Y LIMIT 1) AS PL,(SELECT tpLicense FROM Transport WHERE tpFactory = FactoryID AND YEAR(tpDate + INTERVAL 3 MONTH) = Y LIMIT 1) AS TL, (SELECT SaleLicenseID FROM SaleLicense WHERE slFactoryID = FactoryID AND YEAR(slExtendDate + INTERVAL 3 MONTH) = Y LIMIT 1) AS SL, faLat, faLong FROM Factory WHERE ? IN (0,faRegion) AND ? IN (0,faProvince)) AS AllData WHERE CONCAT(FL,'@',IF(ISNULL(PL),'',PL),'@',IF(ISNULL(TL),'',TL),'@',IF(ISNULL(SL),'',SL)) LIKE ? LIMIT ?,?",array("iiisii",$year,$region,$province,"%".$Keyword."%",$page*$RPP,$RPP));
        
								$data = new exSearch_Table;
								$data->Init(3,$page+1,$RPP,$total,$ldata);
        
								if($total > 0){
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}
									for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
										$data->AddCell($x,1);
										$data->AddCell($fdata["faName"]);
										$data->AddCell($fdata["faCode"]);
										$data->AddCell(isset($fdata["FL"])?$fdata["FL"]:"-",2);
										$data->AddCell(isset($fdata["PL"])?$fdata["PL"]:"-",2);
										$data->AddCell(isset($fdata["TL"])?$fdata["TL"]:"-",2);
										$data->AddCell(isset($fdata["SL"])?$fdata["SL"]:"-",2);
										$data->AddLatLong($x,$fdata["faLat"],$fdata["faLong"]);
									}
								}
							break;
						case 4:
								$DB = new exDB;
								$total = $DB->GetDataOneField("SELECT count(StampID) FROM `Stamp`,`Label` WHERE stLabel = LabelID AND ? IN (0,lbType) AND YEAR(stReleaseDate) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince) AND stBookNo LIKE ?",array("iiiis",$menu,$year,$region,$province,"%".$Keyword."%"));
								$tdata = array(0,0,0,0,0,0,0,0,0,0,0);
								$psum = array("128"=>0,"130"=>1,"135"=>2,"140"=>3,"150"=>4,"228"=>5,"230"=>6,"235"=>7,"240"=>8,"250"=>9);
								$DB->GetData("SELECT lbDegree, lbType, SUM(stAmount) AS S FROM `Stamp` LEFT JOIN Label ON stLabel = LabelID WHERE lbDegree IN (28,30,35,40) GROUP BY lbDegree, lbType");
								while($sumx = $DB->FetchData()){
									$tdata[$psum[$sumx["lbType"].$sumx["lbDegree"]]] = $sumx["S"];
								}
								$tdata[4] = $tdata[0] + $tdata[1] + $tdata[2] + $tdata[3];
								$tdata[9] = $tdata[5] + $tdata[6] + $tdata[7] + $tdata[8];

								$DB->GetData("SELECT lbFacName, stFacCode, stNumber, lbBrand, lbDegree, stAmount, stSize, stPrice, stVolume, stTax, stBookNo, stReleaseDate, faLat, faLong FROM `Stamp`,`Label`,`Factory` WHERE FactoryID = stFacCode AND stLabel = LabelID AND ? IN (0,lbType) AND YEAR(stReleaseDate) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince) AND stBookNo LIKE ? LIMIT ?,?",array("iiiisii",$menu,$year,$region,$province,"%".$Keyword."%",$page*$RPP,$RPP));
                
								$data = new exSearch_Table;
								$data->Init(4,$page+1,$RPP,$total,$tdata);
								if($total > 0){
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}
									for($x=($page * $RPP + 1);$fdata = $DB->FetchData();$x++){
										$data->AddCell($x,1);
										$data->AddCell(is_null($fdata["stBookNo"])?"-":$fdata["stBookNo"],2);
										$data->AddCell($fdata["lbFacName"]);
										$data->AddCell($fdata["stFacCode"]);
										$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["stReleaseDate"]));
										$data->AddCell($fdata["lbBrand"]);
										$data->AddCell($fdata["lbDegree"],2);
										$data->AddCell(number_format($fdata["stAmount"]),1);
										$data->AddCell(number_format($fdata["stSize"],3),1);
										$data->AddCell(number_format($fdata["stPrice"],4),1);
										$data->AddCell(number_format($fdata["stVolume"],2),1);
										$data->AddCell(number_format($fdata["stTax"],2),1);
										$data->AddCell($etcObj->GetShortDate(exETC::C_TH,$fdata["stReleaseDate"]));
										$data->AddLatLong($x,$fdata["faLat"],$fdata["faLong"]);
									}
								}
							break;
						case 5:
									$DB = new exDB;
									$total = $DB->GetDataOneField("SELECT count(FactoryID) FROM `Factory` WHERE YEAR(faIssueDate) = ? AND ? IN (0,faRegion) AND ? IN (0,faProvince) AND ? IN (0,faSuraType) AND faName LIKE ?",array("iiiis",$year,$region,$province,$menu,"%".$Keyword."%"));

									$tdata = $DB->GetDataOneRow("SELECT ? AS Y, ? AS R, ? AS P, (SELECT COUNT(LabelID) FROM Label WHERE R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(lbExpireDate + INTERVAL 3 MONTH) = Y) AS brand, (SELECT COUNT(FactoryID) FROM `Factory` WHERE R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) = Y) AS FAC
",array("iii",$year,$region,$province));
									$DB->GetData("SELECT faName, FactoryID, faContact, faLicenseNo, faIssueDate, faAddress,suName, lbBrand, lbPicture, faLat, faLong FROM `Factory`,`SuraType`,`Label` WHERE faSuraType = SuraTypeID AND lbFacCode = FactoryID AND ? IN (0,faSuraType) AND YEAR(faIssueDate) = ? AND ? IN (0,faRegion) AND ? IN (0,faProvince) AND faName LIKE ? LIMIT ?,?",array("iiiisii",$menu,$year,$region,$province,"%".$Keyword."%",$page*$RPP,$RPP));
                
									$data = new exSearch_Table;
									$data->Init(5,$page+1,$RPP,$total,array($tdata["FAC"],$tdata["brand"],$tdata["FAC"]+$tdata["brand"]));
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
											$data->AddCell($fdata["lbBrand"]);
											$data->AddCell("",3);
											$data->AddCell("",3);
											$data->AddCell("",4);
											$data->AddLatLong($x,$fdata["faLat"],$fdata["faLong"]);
										}
									}
						break;
						default:
								$data = new exReport_Table;
								$data->Init($page+1,$RPP,100,array(1,2,3,4));
								
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
				}
			break;
	case "filter" :
				$DB = new exDB;
				if($_POST["src"] == 0){
					$data = new exFilter_Bar;
					$data->year = array();
					$data->region = array();
					$data->province = array();
					$data->job = isset($_POST["job"])?$_POST["job"]:1;


					if(($data->job == 1)||($data->job == 4)||($data->job == 3)){
						$DB->GetData("SELECT YEAR(faIssueDate) AS fYear FROM Factory GROUP BY YEAR(faIssueDate) ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
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
					}elseif($data->job == 2){
						$DB->GetData("SELECT YEAR(ilActDate + INTERVAL 3 MONTH) AS fYear FROM `Illegal` GROUP BY fYear DESC");
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
						$sdata->value = 2017;
						$sdata->label = "ปีงบประมาณ 2560";
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
					$S_region = isset($_POST["value"])?intval($_POST["value"]):0;
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

	case "autocomplete" :
				$year = isset($_POST["year"])?$_POST["year"]:date("Y");
				switch($_POST["src"]){
					case 1: //โรงงาน
							$data = array();
        
							$DB = new exDB;
							$DB->GetData("SELECT `FactoryID`, `faName` FROM `Factory` WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) = ? AND faName LIKE ? LIMIT 10",array("is",$year,"%".$_POST["value"]."%"));
        
							while($fdata = $DB->FetchData()){
								$sdata = new exItem;
								$sdata->id = $fdata["FactoryID"];
								$sdata->value = $fdata["faName"];
								$sdata->label = $fdata["faName"];
								array_push($data,$sdata);
							}
						break;
					case 2: //โรงงาน
							$data = array();
        
							$DB = new exDB;
							$DB->GetData("SELECT `IllegalID`, `ilSuspect` FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? AND ilSuspect LIKE ? LIMIT 10",array("is",$year,"%".$_POST["value"]."%"));
        
							while($fdata = $DB->FetchData()){
								$sdata = new exItem;
								$sdata->id = $fdata["IllegalID"];
								$sdata->value = $fdata["ilSuspect"];
								$sdata->label = $fdata["ilSuspect"];
								array_push($data,$sdata);
							}
						break;
					case 4:
							$data = array();
							$sdata = new exItem;
							$sdata->id = 0;
							$sdata->value = $_POST["value"];
							$sdata->label = "ค้นหาแสมป์หมายเลข ".$_POST["value"];
							array_push($data,$sdata);
						break;
					case 5: //โรงงาน
							$menu = isset($_POST["menu"])?$_POST["menu"]:0;
							$data = array();
        
							$DB = new exDB;
							if($menu < 2){
								$DB->GetData("SELECT `FactoryID` AS FID, `faName` AS FVALUE FROM `Factory` WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) = ? AND faName LIKE ? LIMIT 10",array("is",$year,"%".$_POST["value"]."%"));
							}else{
								$DB->GetData("SELECT `LabelID` AS FID, `lbBrand` AS FVALUE FROM `Label` WHERE YEAR(lbExpireDate + INTERVAL 3 MONTH) = ? AND lbBrand LIKE ?",array("is",$year,"%".$_POST["value"]."%"));
							}
        
							while($fdata = $DB->FetchData()){
								$sdata = new exItem;
								$sdata->id = $fdata["FID"];
								$sdata->value = $fdata["FVALUE"];
								$sdata->label = $fdata["FVALUE"];
								array_push($data,$sdata);
							}
						break;
					default :
							$data = array();
				}
		break;
	case "getgraph" :
				$job = isset($_POST["job"])?$_POST["job"]:0;
				$year = isset($_POST["year"])?$_POST["year"]:9999;
				$region = isset($_POST["region"])?$_POST["region"]:0;
				$province = isset($_POST["province"])?$_POST["province"]:0;
				
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
