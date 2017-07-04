<?php //Report API
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/report.class.php");

$fn = isset($_POST["fn"])?$_POST["fn"]:"";
$mode = isset($_POST["mode"])?$_POST["mode"]:0;

				$title = array(
					1 => array("เดือน","ค่าธรรมเนียมใบอนุญาตก่อสร้าง","ค่าธรรมเนียมใบอนุญาตผลิต","ค่าธรรมเนียมใบอนุญาตจำหน่าย","ค่าธรรมเนียมใบอนุญาตขน","จำหน่ายแสตมป์สุรา","ภาษีรวม"),
					2 => array("เดือน","จำนวนราย","เปรียบเทียบปรับ","ศาลปรับ","พนักงานสอบสวน","เงินสินบน","เงินรางวัล","เงินส่งคลัง"),
					3 => array("เดือน","จำนวนใบอนุญาตก่อสร้าง","จำนวนใบอนุญาตผลิต","จำนวนใบอนุญาตจำหน่าย","จำนวนใบอนุญาตขน","จำนวนใบอนุญาตรวม"),
					4 => array("เดือน","28","30","35","40","จำนวนทั้งหมด"),
					5 => array("เดือน","สุรากลั่น","สุราแช่","รวมทั้งหมด")
				);

switch($fn){
	case "gettable" :
				$RPP = 5;
				$year = isset($_POST["year"])?$_POST["year"]:2017;
				$region = isset($_POST["region"])?$_POST["region"]:0;
				$province = isset($_POST["province"])?$_POST["province"]:0;
				$page = isset($_POST["page"])?$_POST["page"]-1:0;
				$job = isset($_POST["job"])?intval($_POST["job"]):0;
				if(!in_array($job,array(1,2,3,4,5))) $job = 1;

				$TitleShow = $title[$job];
				if($mode==1) $TitleShow[0] = "ปี";
				$colnum = count($TitleShow);

				switch($job){
					case 1:
							$DB = new exDB;

							if($mode==0){
								$total = 12;
								$rdata = array(10,11,12,1,2,3,4,5,6,7,8,9);
								$DB->GetData("SELECT ? AS Y, ? AS R, ? AS P,gmValue AS M,(SELECT SUM(stTax) FROM (SELECT lbRegion, lbProvince, stTax, stReleaseDate FROM `Label`,`Stamp` WHERE LabelID = stLabel) AS ST WHERE MONTH(stReleaseDate) = gmValue AND R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = Y) AS STAX FROM GovMonth",array("iii",$year,$region,$province));
								$data = new exReport_Table;
								$data->Init(1,$total,$total);
								if($total > 0){
									$tdata = array(array());
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}

									for($x=1;$x<=$total;$x++){
										$tdata[$x][0] = $etcObj->GetMonthFullName($x)." ".($x>9?$year+542:$year+543);
										for($y=1;$y<=6;$y++){
											$tdata[$x][$y] = "-";
										}
									}

									while($fdata = $DB->FetchData()){
										$tdata[$fdata["M"]][1] = 0;
										$tdata[$fdata["M"]][2] = 0;
										$tdata[$fdata["M"]][3] = 0;
										$tdata[$fdata["M"]][4] = 0;
										$tdata[$fdata["M"]][5] = $fdata["STAX"];
										$tdata[$fdata["M"]][6] = $fdata["STAX"];
									}

									foreach($rdata as $x){
										$data->AddCell($tdata[$x][0]);
										for($y=1;$y<=6;$y++){
											$data->AddCell(number_format($tdata[$x][$y],2),1);
										}
									}
								}
							}else{
								$total = 5;
								$rdata = array(1,2,3,4,5);

								$data = new exReport_Table;
								$data->Init(1,$total,$total);
								$tdata = array(array());
								$etcObj = new exETC;
								for($i=0;$i<$colnum;$i++){
									$data->AddLabel($TitleShow[$i]);
								}

								for($Y=$year-4;$Y<=$year;$Y++){
									$fdata = $DB->GetDataOneRow("SELECT ? AS Y, ? AS R, ? AS P,0,0,0,0,(SELECT SUM(stTax) FROM (SELECT lbRegion, lbProvince, stTax, stReleaseDate FROM `Label`,`Stamp` WHERE LabelID = stLabel) AS ST WHERE R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = Y) AS STAX ",array("iii",$Y,$region,$province));


									$data->AddCell("ปีงบประมาณ ".($Y + 543));
									$data->AddCell("0.00",1);
									$data->AddCell("0.00",1);
									$data->AddCell("0.00",1);
									$data->AddCell("0.00",1);
									$data->AddCell(number_format($fdata["STAX"]),1);
									$data->AddCell(number_format($fdata["STAX"]),1);
								}
							}

						break;
					case 2:
								$DB = new exDB;
								if($mode==0){
									$total = 12;
									$rdata = array(10,11,12,1,2,3,4,5,6,7,8,9);

									$DB->GetData("SELECT MONTH(ilActDate) AS M, COUNT(IllegalID) ,SUM(`ilComparativeMoney`), SUM(`ilFine`), SUM(`ilOfficer`), SUM(`ilBribe`), SUM(`IlReward`), SUM(`ilReturn`) FROM `Illegal`,`Area` WHERE AreaID = ilArea AND ? IN (0,ilRegion) AND ? IN (0,arProvince) AND YEAR(ilActDate + INTERVAL 3 MONTH) = ? GROUP BY M ORDER BY ilActDate",array("iii",$region,$province,$year));
								}else{
									$total = 5;
									$rdata = array(1,2,3,4,5);
									$DB->GetData("SELECT YEAR(ilActDate + INTERVAL 3 MONTH) AS Y, COUNT(IllegalID),SUM(`ilComparativeMoney`),SUM(`ilFine`),SUM(`ilOfficer`),SUM(`ilBribe`),SUM(`IlReward`),SUM(`ilReturn`) FROM `Illegal`,`Area` WHERE AreaID = ilArea AND ? IN (0,ilRegion) AND ? IN (0,arProvince) AND YEAR(ilActDate + INTERVAL 3 MONTH) BETWEEN ? AND ? GROUP BY Y",array("iiii",$region,$province,$year-4,$year));
								}
        
								$data = new exReport_Table;
								$data->Init(1,$total,$total);


								if($total > 0){
									$tdata = array();
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}

									for($x=1;$x<=$total;$x++){
										if($total==5){
											$tdata[$x][0] = "ปีงบประมาณ ".(($year + 539) + $x);
										}else{
											$tdata[$x][0] = $etcObj->GetMonthFullName($x)." ".($x>9?$year+542:$year+543);
										}
										for($y=1;$y<=7;$y++){
											$tdata[$x][$y] = "-";
										}
									}

									while($fdata = $DB->FetchData()){
										if($total==5){
											$x = $fdata["Y"] - $year+5;
											for($y=1;$y<=7;$y++){
												$tdata[$x][$y] = $fdata[$y];
											}
										}else{
											for($y=1;$y<=7;$y++){
												$tdata[$fdata["M"]][$y] = $fdata[$y];
											}
										}
									}

									foreach($rdata as $x){
										$data->AddCell($tdata[$x][0]);
										for($y=1;$y<=7;$y++){
											if($tdata[$x][$y]=="-"){
												$data->AddCell("-",2);
											}else{
												if($y==1){
													$data->AddCell(number_format($tdata[$x][$y]),1);
												}else{
													$data->AddCell(number_format($tdata[$x][$y],2),1);
												}
											}
										}
									}
								}
						break;
					case 3:
								$DB = new exDB;

								if($mode==0){
									$total = 12;
									$rdata = array(10,11,12,1,2,3,4,5,6,7,8,9);
									$DB->GetData("SELECT ? AS Y, ? AS R, ? AS P,gmValue AS M,(SELECT COUNT(FactoryID) FROM `Factory` WHERE MONTH(faIssueDate) = gmValue AND R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) = Y) AS Construction,(SELECT COUNT(lbLicense) FROM `Label` WHERE MONTH(lbIssueDate) = gmValue AND R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(lbIssueDate + INTERVAL 3 MONTH) = Y) AS Production,(SELECT COUNT(SaleLicenseID) FROM (SELECT `SaleLicenseID`, `slIssueDate`, `faProvince`,`faRegion` FROM `SaleLicense`,`Factory` WHERE FactoryID = slFactoryID) AS SL WHERE MONTH(slIssueDate) = gmValue AND R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(slIssueDate + INTERVAL 3 MONTH) = Y) AS Sale, (SELECT COUNT(TransportID) FROM Transport WHERE MONTH(tpDate) = gmValue AND YEAR(tpDate + INTERVAL 3 MONTH) = Y AND R IN (0,tpRegion) AND P IN (0,tpProvince)) AS Transpot FROM GovMonth",array("iii",$year,$region,$province));

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
												$tdata[$x][0] = "ปีงบประมาณ ".(($year + 539) + $x);
											}else{
												$tdata[$x][0] = $etcObj->GetMonthFullName($x)." ".($x>9?$year+542:$year+543);
											}
											for($y=1;$y<=5;$y++){
												$tdata[$x][$y] = "-";
											}
										}

										while($fdata = $DB->FetchData()){
											if($total==5){
												$x = $fdata["Y"] - $year+5;
												$tdata[$x][$degree[$fdata["lbDegree"]]] = $fdata["S"];
												$tdata[$x][5] = intval($tdata[$x][5]) + $fdata["S"];
											}else{
												$tdata[$fdata["M"]][1] = $fdata["Construction"];
												$tdata[$fdata["M"]][2] = $fdata["Production"];
												$tdata[$fdata["M"]][3] = $fdata["Sale"];
												$tdata[$fdata["M"]][4] = $fdata["Transpot"];
												$tdata[$fdata["M"]][5] = $fdata["Construction"] + $fdata["Production"] + $fdata["Sale"] + $fdata["Transpot"];
											}
										}

										foreach($rdata as $x){
											$data->AddCell($tdata[$x][0]);
											for($y=1;$y<=5;$y++){
												$data->AddCell(number_format($tdata[$x][$y]),1);
											}
										}
									}
								}else{
									$total = 5;
									$rdata = array(1,2,3,4,5);

									$data = new exReport_Table;
									$data->Init(1,$total,$total);
									$tdata = array(array());
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}

									for($Y=$year-4;$Y<=$year;$Y++){
										$fdata = $DB->GetDataOneRow("SELECT ? AS Y, ? AS R, ? AS P,(SELECT COUNT(FactoryID) FROM `Factory` WHERE R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) = Y) AS Construction,(SELECT COUNT(lbLicense) FROM `Label` WHERE R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(lbIssueDate + INTERVAL 3 MONTH) = Y) AS Production,(SELECT COUNT(SaleLicenseID) FROM (SELECT `SaleLicenseID`, `slIssueDate`, `faProvince`,`faRegion` FROM `SaleLicense`,`Factory` WHERE FactoryID = slFactoryID) AS SL WHERE R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(slIssueDate + INTERVAL 3 MONTH) = Y) AS Sale, (SELECT COUNT(TransportID) FROM Transport WHERE YEAR(tpDate + INTERVAL 3 MONTH) = Y AND R IN (0,tpRegion) AND P IN (0,tpProvince)) AS Transpot ",array("iii",$Y,$region,$province));


										$data->AddCell("ปีงบประมาณ ".($Y + 543));
										$data->AddCell(number_format($fdata["Construction"]),1);
										$data->AddCell(number_format($fdata["Production"]),1);
										$data->AddCell(number_format($fdata["Sale"]),1);
										$data->AddCell(number_format($fdata["Transpot"]),1);
										$data->AddCell(number_format($fdata["Construction"] + $fdata["Production"] + $fdata["Sale"] + $fdata["Transpot"]),1);
									}
								}
						break;
					case 4:
								$DB = new exDB;

								if($mode==0){
									$total = 12;
									$rdata = array(10,11,12,1,2,3,4,5,6,7,8,9);
									$DB->GetData("SELECT MONTH(stReleaseDate) AS M, lbDegree, SUM(stAmount) AS S FROM (SELECT lbDegree, stAmount, stReleaseDate FROM `Stamp`,`Label` WHERE stLabel = LabelID AND lbDegree > 27 AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince)) AS X GROUP BY M, lbDegree ORDER BY stReleaseDate",array("iii",$year,$region,$province));
								}else{
									$total = 5;
									$rdata = array(1,2,3,4,5);
									$DB->GetData("SELECT YEAR(stReleaseDate + INTERVAL 3 MONTH) AS Y, lbDegree, SUM(stAmount) AS S FROM (SELECT lbDegree, stAmount, stReleaseDate FROM `Stamp`,`Label` WHERE stLabel = LabelID AND lbDegree > 27 AND YEAR(stReleaseDate + INTERVAL 3 MONTH) BETWEEN ? AND ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince)) AS X GROUP BY Y,lbDegree ORDER BY stReleaseDate",array("iiii",$year - 4,$year,$region,$province));
								}
        
								$data = new exReport_Table;
								$data->Init(1,$total,$total);
								if($total > 0){
									$tdata = array(array());
									$etcObj = new exETC;
									for($i=0;$i<$colnum;$i++){
										$data->AddLabel($TitleShow[$i]);
									}
									$degree = array(28=>1,30=>2,35=>3,40=>4);

									for($x=1;$x<=$total;$x++){
										if($total==5){
											$tdata[$x][0] = "ปีงบประมาณ ".(($year + 539) + $x);
										}else{
											$tdata[$x][0] = $etcObj->GetMonthFullName($x)." ".($x>9?$year+542:$year+543);
										}
										for($y=1;$y<=5;$y++){
											$tdata[$x][$y] = "-";
										}
									}

									while($fdata = $DB->FetchData()){
										if($total==5){
											$x = $fdata["Y"] - $year+5;
											$tdata[$x][$degree[$fdata["lbDegree"]]] = $fdata["S"];
											$tdata[$x][5] = intval($tdata[$x][5]) + $fdata["S"];
										}else{
											$tdata[$fdata["M"]][$degree[$fdata["lbDegree"]]] = $fdata["S"];
											$tdata[$fdata["M"]][5] = intval($tdata[$fdata["M"]][5]) + $fdata["S"];
										}
									}

									foreach($rdata as $x){
										$data->AddCell($tdata[$x][0]);
										for($y=1;$y<=5;$y++){
											$data->AddCell($tdata[$x][$y],1);
										}
									}
								}
						break;
					case 5:
								$DB = new exDB;
								if($mode==0){
									$total = 12;
									$rdata = array(10,11,12,1,2,3,4,5,6,7,8,9);
									$DB->GetData("SELECT MONTH(faIssueDate) AS M, faSuraType, COUNT(FactoryID) AS C FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) = ? GROUP BY M, faSuraType ORDER BY faIssueDate",array("iii",$region,$province,$year));
								}else{
									$total = 5;
									$rdata = array(1,2,3,4,5);
									$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS Y, faSuraType, COUNT(FactoryID) AS C FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) BETWEEN ? AND ? GROUP BY Y,faSuraType ORDER BY Y, faSuraType",array("iiii",$region,$province,$year-4,$year));
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
											$tdata[$x][0] = $etcObj->GetMonthFullName($x)." ".($x>9?$year+542:$year+543);
										}
										for($y=1;$y<=3;$y++){
											$tdata[$x][$y] = "-";
										}
									}

									while($fdata = $DB->FetchData()){
										if($total==5){
											$x = $fdata["Y"] - $year+5;
											if($fdata["faSuraType"] == 3){
												$tdata[$x][1] += $fdata["C"];
												$tdata[$x][2] += $fdata["C"];
												$tdata[$x][3] = intval($tdata[$x][3]) + $fdata["C"]*2;
											}else{
												$tdata[$x][$fdata["faSuraType"]] = $fdata["C"];
												$tdata[$x][3] = intval($tdata[$x][3]) + $fdata["C"];
											}
										}else{
											if($fdata["faSuraType"] == 3){
												$tdata[$fdata["M"]][1] += $fdata["C"];
												$tdata[$fdata["M"]][2] += $fdata["C"];
												$tdata[$fdata["M"]][3] = intval($tdata[$fdata["M"]][3]) + $fdata["C"]*2;
												$tdata[$x][3] = intval($tdata[$x][3]) + $fdata["C"]*2;
											}else{
												$tdata[$fdata["M"]][$fdata["faSuraType"]] = $fdata["C"];
												$tdata[$fdata["M"]][3] = intval($tdata[$fdata["M"]][3]) + $fdata["C"];
											}
										}
									}

									foreach($rdata as $x){
										$data->AddCell($tdata[$x][0]);
										for($y=1;$y<=3;$y++){
											$data->AddCell($tdata[$x][$y],1);
										}
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
				if($_POST["src"] == 0){
					$data = new exFilter_Bar;
					$data->year = array();
					$data->region = array();
					$data->province = array();
					$data->job = isset($_POST["job"])?$_POST["job"]:1;


					if($data->job == 1){
						$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS fYear FROM Factory WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) BETWEEN 1900 AND YEAR(NOW() + INTERVAL 3 MONTH) GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif($data->job == 2){
						$DB->GetData("SELECT YEAR(ilActDate + INTERVAL 3 MONTH) AS fYear FROM Illegal WHERE YEAR(ilActDate + INTERVAL 3 MONTH) BETWEEN 1900 AND YEAR(NOW() + INTERVAL 3 MONTH) GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif($data->job == 3){
						$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS fYear FROM Factory WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) BETWEEN 1900 AND YEAR(NOW() + INTERVAL 3 MONTH) GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif(($data->job == 4)){
						$DB->GetData("SELECT YEAR(stReleaseDate + INTERVAL 3 MONTH) AS fYear FROM `Stamp` WHERE YEAR(stReleaseDate + INTERVAL 3 MONTH) BETWEEN 1900 AND YEAR(NOW() + INTERVAL 3 MONTH) GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif(($data->job == 5)){
						$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS fYear FROM Factory WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) BETWEEN 1900 AND YEAR(NOW() + INTERVAL 3 MONTH) GROUP BY fYear ORDER BY fYear DESC");
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
						$sdata->value = date("Y");
						$sdata->label = "ปีงบประมาณ ".(date("Y") + 543);
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

					$filterLabel = "ทุกจังหวัด";
					$DB->GetData("SELECT `ProvinceID`, `pvName` FROM `Province` WHERE ? IN (0,pvRegion)",array("i",$S_region));

					if($DB->GetNumRows()>0){
						$data = array();
						$sdata = new exItem;
						$sdata->id = 0;
						$sdata->value = 0;
						$sdata->label = $filterLabel;
						array_push($data,$sdata);

						while($fdata = $DB->FetchData()){
							$sdata = new exItem;
							$sdata->id = $fdata[0];
							$sdata->value = $fdata[0];
							$sdata->label = $fdata[1];
							array_push($data,$sdata);
						}
					}else{
						$data = null;
					}
				}
			break;
	case "getgraph" :
				$mode = isset($_POST["mode"])?$_POST["mode"]:0;
				$job = isset($_POST["job"])?$_POST["job"]:0;
				$year = isset($_POST["year"])?$_POST["year"]:date('Y');
				$region = isset($_POST["region"])?$_POST["region"]:0;
				$province = isset($_POST["province"])?$_POST["province"]:0;
				
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
							$tmpData = array(array());
							if($mode==0){
								$DB->GetData("SELECT ? AS Y, ? AS R, ? AS P,gmValue AS H,0 AS CS,0 AS PD,0 AS SL, 0 AS TP,(SELECT SUM(stTax) FROM (SELECT lbRegion, lbProvince, stTax, stReleaseDate FROM `Label`,`Stamp` WHERE LabelID = stLabel) AS ST WHERE MONTH(stReleaseDate) = gmValue AND R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = Y) FROM GovMonth",array("iii",$year,$region,$province));
								while($fdata = $DB->FetchData()){
									for($x=4;$x<=8;$x++){
										$tmpData[$x-3][$fdata["H"]] = $fdata[$x];
									}
									$tmpData[6][$fdata["H"]] = ($fdata[5]+$fdata[6]+$fdata[7]+$fdata[8]);
									
								}
							}else{
								$fdata = $DB->GetDataOneRow("SELECT ? AS Y, ? AS R, ? AS P,0,0,0,0,(SELECT SUM(stTax) FROM (SELECT lbRegion, lbProvince, stTax, stReleaseDate FROM `Label`,`Stamp` WHERE LabelID = stLabel) AS ST WHERE R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = Y) AS STAX ",array("iii",$year,$region,$province));
								for($Y=$year-4;$Y<=$year;$Y++){
									$fdata = $DB->GetDataOneRow("SELECT ? AS Y, ? AS R, ? AS P,0 AS Construction,0 AS Production,0 AS Sale, 0 AS Transpot,(SELECT SUM(stTax) FROM (SELECT lbRegion, lbProvince, stTax, stReleaseDate FROM `Label`,`Stamp` WHERE LabelID = stLabel) AS ST WHERE R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = Y) AS STAX",array("iii",$Y,$region,$province));


									$tmpData[1][$Y] = $fdata["Construction"];
									$tmpData[2][$Y] = $fdata["Production"];
									$tmpData[3][$Y] = $fdata["Sale"];
									$tmpData[4][$Y] = $fdata["Transpot"];
									$tmpData[5][$Y] = $fdata["STAX"];
									$tmpData[6][$Y] = $fdata["Construction"] + $fdata["Production"] + $fdata["Sale"] + $fdata["Transpot"] + $fdata["STAX"];
								}
							}
						break;
					case 2 :
							if($mode==0){
								$DB->GetData("SELECT MONTH(ilActDate) AS H, COUNT(IllegalID) ,SUM(`ilComparativeMoney`), SUM(`ilFine`), SUM(`ilOfficer`), SUM(`ilBribe`), SUM(`IlReward`), SUM(`ilReturn`) FROM `Illegal` WHERE ? IN (0,ilRegion) AND ? IN (0,ilArea) AND YEAR(ilActDate + INTERVAL 3 MONTH) = ? GROUP BY H ORDER BY ilActDate",array("iii",$region,$province,$year));
							}else{
								$DB->GetData("SELECT YEAR(ilActDate + INTERVAL 3 MONTH) AS H, COUNT(IllegalID),SUM(`ilComparativeMoney`),SUM(`ilFine`),SUM(`ilOfficer`),SUM(`ilBribe`),SUM(`IlReward`),SUM(`ilReturn`) FROM `Illegal` WHERE ? IN (0,ilRegion) AND ? IN (0,ilArea) AND YEAR(ilActDate + INTERVAL 3 MONTH) BETWEEN ? AND ? GROUP BY H",array("iiii",$region,$province,$year-4,$year));
							}
						break;
					case 3 :
							$tmpData = array(array());
							if($mode==0){
								$DB->GetData("SELECT ? AS Y, ? AS R, ? AS P,gmValue AS H,(SELECT COUNT(FactoryID) FROM `Factory` WHERE MONTH(faIssueDate) = gmValue AND R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) = Y) AS Construction,(SELECT COUNT(lbLicense) FROM `Label` WHERE MONTH(lbIssueDate) = gmValue AND R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(lbIssueDate + INTERVAL 3 MONTH) = Y) AS Production,(SELECT COUNT(SaleLicenseID) FROM (SELECT `SaleLicenseID`, `slIssueDate`, `faProvince`,`faRegion` FROM `SaleLicense`,`Factory` WHERE FactoryID = slFactoryID) AS SL WHERE MONTH(slIssueDate) = gmValue AND R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(slIssueDate + INTERVAL 3 MONTH) = Y) AS Sale, (SELECT COUNT(TransportID) FROM `Transport` WHERE MONTH(tpDate) = gmValue AND R IN (0,tpRegion) AND P IN (0,tpProvince) AND YEAR(tpDate + INTERVAL 3 MONTH) = Y) AS Transpot FROM GovMonth",array("iii",$year,$region,$province));
								while($fdata = $DB->FetchData()){
									for($x=3;$x<=7;$x++){
										$tmpData[$x-3][$fdata["H"]] = $fdata[$x];
									}
									$tmpData[5][$fdata["H"]] = ($fdata[7]+$fdata[4]+$fdata[5]+$fdata[6]);
									
								}
							}else{
								for($Y=$year-4;$Y<=$year;$Y++){
									$fdata = $DB->GetDataOneRow("SELECT ? AS Y, ? AS R, ? AS P,(SELECT COUNT(FactoryID) FROM `Factory` WHERE R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) = Y) AS Construction,(SELECT COUNT(lbLicense) FROM `Label` WHERE R IN (0,lbRegion) AND P IN (0,lbProvince) AND YEAR(lbIssueDate + INTERVAL 3 MONTH) = Y) AS Production,(SELECT COUNT(SaleLicenseID) FROM (SELECT `SaleLicenseID`, `slIssueDate`, `faProvince`,`faRegion` FROM `SaleLicense`,`Factory` WHERE FactoryID = slFactoryID) AS SL WHERE R IN (0,faRegion) AND P IN (0,faProvince) AND YEAR(slIssueDate + INTERVAL 3 MONTH) = Y) AS Sale,(SELECT COUNT(TransportID) FROM `Transport` WHERE R IN (0,tpRegion) AND P IN (0,tpProvince) AND YEAR(tpDate + INTERVAL 3 MONTH) = Y) AS Transpot ",array("iii",$Y,$region,$province));


									$tmpData[1][$Y] = $fdata["Construction"];
									$tmpData[2][$Y] = $fdata["Production"];
									$tmpData[3][$Y] = $fdata["Sale"];
									$tmpData[4][$Y] = $fdata["Transpot"];
									$tmpData[5][$Y] = $fdata["Construction"] + $fdata["Production"] + $fdata["Sale"] + $fdata["Transpot"];
								}
							}
						break;
					case 4 :
							if($mode==0){
								$DB->GetData("SELECT MONTH(stReleaseDate) AS H, lbDegree AS V, SUM(stAmount) AS S FROM (SELECT lbDegree, stAmount, stReleaseDate FROM `Stamp`,`Label` WHERE stLabel = LabelID AND lbDegree > 27 AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince)) AS X GROUP BY V,H ORDER BY V,lbDegree",array("iii",$year,$region,$province));
							}else{
								$DB->GetData("SELECT YEAR(stReleaseDate + INTERVAL 3 MONTH) AS H, lbDegree AS V, SUM(stAmount) AS S FROM (SELECT lbDegree, stAmount, stReleaseDate FROM `Stamp`,`Label` WHERE stLabel = LabelID AND lbDegree > 27 AND YEAR(stReleaseDate + INTERVAL 3 MONTH) BETWEEN ? AND ? AND ? IN (0,lbRegion) AND ? IN (0,lbProvince)) AS X GROUP BY V ORDER BY V,lbDegree",array("iiii",$year - 4,$year,$region,$province));
							}
						break;
					case 5 :
							if($mode==0){
								$DB->GetData("SELECT MONTH(faIssueDate) AS H, faSuraType AS V, COUNT(FactoryID) AS S FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) = ? GROUP BY V,H ORDER BY V,faIssueDate",array("iii",$region,$province,$year));
							}else{
								$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS H, faSuraType AS V, COUNT(FactoryID) AS S FROM `Factory` WHERE ? IN (0,faRegion) AND ? IN (0,faProvince) AND YEAR(faIssueDate + INTERVAL 3 MONTH) BETWEEN ? AND ? GROUP BY V,H ORDER BY V,faIssueDate",array("iiii",$region,$province,$year-4,$year));
							}
						break;
					default :
				}

				if($DB->GetNumRows() > 0){
					if($job==1){
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
					}elseif($job==2){
						$tmpData = array(array());
						while($fdata = $DB->FetchData()){
							for($x=1;$x<=7;$x++){
								$tmpData[$x][$fdata["H"]] = $fdata[$x];
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
					}elseif($job==3){
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
					}elseif($job==4){
						$CurV = 0;
						$CountV = 0;
						$VList = array();
						$degree = array("title"=>0,"28"=>1,"30"=>2,"35"=>3,"40"=>4);
						while($fdata = $DB->FetchData()){
							if($CurV != $fdata["V"]){
								$CurV = $fdata["V"];
								array_push($VList,$CurV);
								$CountV++;
							}
							$tmpData[$degree[$fdata["V"]]][$fdata["H"]] = $fdata["S"];
							if(isset($tmpData[5][$fdata["H"]])){
								$tmpData[5][$fdata["H"]] += $fdata["S"];
							}else{
								$tmpData[5][$fdata["H"]] = $fdata["S"];
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
				    }elseif($job==5){
						$CurV = 0;
						$CountV = 0;
						$VList = array();
						while($fdata = $DB->FetchData()){
							if($CurV != $fdata["V"]){
								$CurV = $fdata["V"];
								array_push($VList,$CurV);
								$CountV++;
							}
							if($fdata["V"] == 3){
								$tmpData[1][$fdata["H"]] += $fdata["S"];
								$tmpData[2][$fdata["H"]] += $fdata["S"];
								if(isset($tmpData[3][$fdata["H"]])){
									$tmpData[3][$fdata["H"]] += $fdata["S"]*2;
								}else{
									$tmpData[3][$fdata["H"]] = $fdata["S"]*2;
								}
							}else{
								$tmpData[$fdata["V"]][$fdata["H"]] = $fdata["S"];
								if(isset($tmpData[3][$fdata["H"]])){
									$tmpData[3][$fdata["H"]] += $fdata["S"];
								}else{
									$tmpData[3][$fdata["H"]] = $fdata["S"];
								}
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
