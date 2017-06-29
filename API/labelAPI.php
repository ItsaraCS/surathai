<?php
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/label.class.php");

$fn = isset($_POST["fn"])?$_POST["fn"]:"";

switch($fn){
	case "getdata" :
				$DB = new exDB;
				$DB->GetData("SELECT `lbFacCode`,`lbDegree`, `suName`, `FactoryID`, `lbLicense`, `lbIssueDate`, `lbExpireDate`, `faLat`, `faLong`, `lbBrand`, `lbContact`, `lbFacName`, `lbPicture`, `lbAddress` FROM `Label`, `Factory`,`SuraType` WHERE `FactoryID` = `lbFacCode` AND SuraTypeID = lbType AND lbBrand = ? ORDER BY FactoryID",array("s",$_POST["label"]));

				$data = array();
				for($i=1;$fdata = $DB->FetchData();$i++){
					$sdata = new exLabel_Data;
					$etcObj = new exETC;
					$sdata->id = $i;
					$sdata->factory_name = $fdata["lbFacName"];
					$sdata->factory_code = $fdata["lbFacCode"];
        				$sdata->contact = $fdata["lbContact"];
					$sdata->license = $fdata["lbLicense"];
					$sdata->brand = $fdata["lbBrand"];
  				      	$sdata->degree = $fdata["lbDegree"];
					$sdata->type = $fdata["suName"];
        				$sdata->issue_date = $etcObj->GetShortDate(exETC::C_TH,$fdata["lbIssueDate"]);
					$sdata->extend_date = $etcObj->GetShortDate(exETC::C_TH,$fdata["lbExpireDate"]);
  				      	$sdata->address = $fdata["lbAddress"];
					$sdata->lat = $fdata["faLat"];
        				$sdata->long = $fdata["faLong"];
					$sdata->picture = "data/label/".$fdata["lbPicture"];
					$sdata->plan = (($fdata["lbPicture"] != "") && file_exists("../data/factoryplan/".$fdata["lbPicture"])==true)?"data/factoryplan/".$fdata["lbPicture"]:"";
					array_push($data,$sdata);
				}
			break;
	case "autocomplete" :
				$data = null;
				if(strlen($_POST["label"]) > 1){ $DB = new exDB;
					$DB->GetData("SELECT `LabelID`, `lbBrand` FROM `Label` WHERE lbBrand LIKE ? GROUP BY lbBrand",array("s","%".$_POST["label"]."%"));
					if($DB->GetNumRows() > 0){
						$data = array();
						while($fdata = $DB->FetchData()){
							$sdata = new exItem;
							$sdata->id = $fdata["LabelID"];
							$sdata->value = $fdata["lbBrand"];
							$sdata->label = $fdata["lbBrand"];
							array_push($data,$sdata);
						}
					}
				}
			break;
	case "getgraph" :
			break;
	default : $data = null;
}

header("Access-Control-Allow-Origin: *");
echo json_encode($data);
?>
