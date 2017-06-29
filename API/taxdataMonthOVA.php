<?php
require_once("../class/database.class.php");

$DB = new exDB;
$data = array(
	"casec" => array(),
	"case" => array(),
	"lic" => array(),
	"stamp" => array(),
	"fac" => array()
);
$AreaList = array();
$DB->GetData("SELECT LPAD(AreaID,5,0) AS ID FROM Area ORDER BY AreaID");
while($data = $DB->FetchData()){
	Array_push($AreaList,$data["ID"]);
}

$DB->GetData("SELECT LPAD(ilArea,5,0) AS ID, COUNT(IllegalID) AS C FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? GROUP BY ilArea",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["casec"][$sdata["ID"]] = $sdata["C"];
}

$DB->GetData("SELECT LPAD(ilArea,5,0) AS ID, SUM(ilComparativeMoney) AS S FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? GROUP BY ilArea",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["case"][$sdata["ID"]] = $sdata["S"];
}

$DB->GetData("SELECT LPAD(AreaID,5,0) AS ID, COUNT(SaleLicenseID) AS C FROM `SaleLicense`,`Factory`,`Area` WHERE slFactoryID = FactoryID AND arProvince = faProvince AND YEAR(slIssueDate + INTERVAL 3 MONTH) = ? GROUP BY AreaID",array("i",$year));
while($sdata = $DB->FetchData()){
	if(isset($data["lic"][$sdata["ID"]])){
		$data["lic"][$sdata["ID"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["ID"]] = $sdata["C"];
	}
}

$DB->GetData("SELECT LPAD(AreaID,5,0) AS ID, COUNT(TransportID) AS C FROM `Transport`,`Area` WHERE tpProvince = arProvince AND YEAR(tpDate + INTERVAL 3 MONTH) = ? GROUP BY AreaID",array("i",$year));
while($sdata = $DB->FetchData()){
	if(isset($data["lic"][$sdata["ID"]])){
		$data["lic"][$sdata["ID"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["ID"]] = $sdata["C"];
	}
}

$DB->GetData("SELECT LPAD(AreaID,5,0) AS ID, COUNT(lbProvince) AS C FROM (SELECT lbProvince, AreaID FROM `Label`,`Area` WHERE arProvince = lbProvince AND YEAR(lbIssueDate + INTERVAL 3 MONTH) <= ? GROUP BY lbLicense) AS X GROUP BY lbProvince",array("i",$year));
while($sdata = $DB->FetchData()){
	if(isset($data["lic"][$sdata["ID"]])){
		$data["lic"][$sdata["ID"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["ID"]] = $sdata["C"];
	}
}

$DB->GetData("SELECT LPAD(AreaID,5,0) AS ID, SUM(stTax) AS S FROM `Stamp`,`Factory`,`Area` WHERE arProvince = faProvince AND stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? GROUP BY faProvince",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["stamp"][$sdata["ID"]] = $sdata["S"];
}

$DB->GetData("SELECT LPAD(AreaID,5,0) AS ID, COUNT(faProvince) AS C FROM (SELECT faProvince, AreaID FROM `Factory`,`Area` WHERE arProvince = faProvince AND YEAR(faIssueDate + INTERVAL 3 MONTH) <= ? GROUP BY faName,faLat,faLong) AS X GROUP BY faProvince",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["fac"][$sdata["ID"]] = $sdata["C"];
	if(isset($data["lic"][$sdata["ID"]])){
		$data["lic"][$sdata["ID"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["ID"]] = $sdata["C"];
	}
}
?>
