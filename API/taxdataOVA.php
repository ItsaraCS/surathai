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
$DB->GetData("SELECT ilRegion, COUNT(ilRegion) AS C FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? GROUP BY ilRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["casec"][$sdata["ilRegion"]] = $sdata["C"];
}

$DB->GetData("SELECT ilRegion, SUM(ilComparativeMoney) AS S FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? GROUP BY ilRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["case"][$sdata["ilRegion"]] = $sdata["S"];
}

$DB->GetData("SELECT faRegion, COUNT(LabelID) AS C FROM `Label`,`Factory` WHERE lbFacCode = FactoryID AND YEAR(lbExpireDate + INTERVAL 3 MONTH) = ? GROUP BY faRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	if(isset($data["lic"][$sdata["faRegion"]])){
		$data["lic"][$sdata["faRegion"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["faRegion"]] = $sdata["C"];
	}
}

$DB->GetData("SELECT tpRegion, COUNT(TransportID) AS C FROM `Transport` WHERE YEAR(tpDate + INTERVAL 3 MONTH) = ? GROUP BY tpRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	if(isset($data["lic"][$sdata["tpRegion"]])){
		$data["lic"][$sdata["tpRegion"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["tpRegion"]] = $sdata["C"];
	}
}

$DB->GetData("SELECT faRegion, COUNT(FactoryID) AS C FROM `Factory` WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) = ? GROUP BY faRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	if(isset($data["lic"][$sdata["faRegion"]])){
		$data["lic"][$sdata["faRegion"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["faRegion"]] = $sdata["C"];
	}
}

$DB->GetData("SELECT faRegion, COUNT(SaleLicenseID) AS C FROM `SaleLicense`,`Factory` WHERE slFactoryID = FactoryID AND YEAR(slIssueDate + INTERVAL 3 MONTH) = ? GROUP BY faRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	if(isset($data["lic"][$sdata["faRegion"]])){
		$data["lic"][$sdata["faRegion"]] += $sdata["C"];
	}else{
		$data["lic"][$sdata["faRegion"]] = $sdata["C"];
	}
}

$DB->GetData("SELECT faRegion, SUM(stTax) AS S FROM `Stamp`,`Factory` WHERE stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? GROUP BY faRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["stamp"][$sdata["faRegion"]] = $sdata["S"];
}

$DB->GetData("SELECT faRegion, COUNT(faRegion) AS C FROM (SELECT faRegion FROM `Factory` WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) <= ? GROUP BY faName,faLat,faLong) AS X GROUP BY faRegion",array("i",$year));
while($sdata = $DB->FetchData()){
	$data["fac"][$sdata["faRegion"]] = $sdata["C"];
}
?>
