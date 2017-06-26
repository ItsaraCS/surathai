<?php //Report API
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");
	require_once("../class/report.class.php");

class taxmap{
	public $type;
	public $crs;
	public $features;

	function __construct() {
		$this->type = "FeatureCollection";
		$this->crs = new taxdetail;
		$this->features = array();
	}

	public function AddRegTax($c,$s,$r){
		$dat = new taxobj;
		$dat->AddProperties($c,$s,$r);
		array_push($this->features,$dat);
	}

	public function AddMonthTax($c,$s,$r,$m){
		$dat = new taxmonthobj;
		$dat->AddProperties($c,$s,$r,$m);
		array_push($this->features,$dat);
	}

	public function AddOverAllRegTax($vta,$vc,$vl,$vs,$vf,$vto,$reg){
		$dat = new OverAlltaxobj;
		$dat->AddProperties($vta,$vc,$vl,$vs,$vf,$vto,$reg);
		array_push($this->features,$dat);
	}

	public function AddAreaTax($a,$r,$p,$v){
		$dat = new TaxAreaObj;
		$dat->AddProperties($a,$r,$p,$v);
		array_push($this->features,$dat);
	}
}

class taxdetail{
	public $type;
	public $properties;

	function __construct() {
		$this->type = "name";
		$this->properties = new taxformat;
	}
}

class taxformat{
	public $name;

	function __construct() {
		$this->name = "urn:ogc:def:crs:OGC:1.3:CRS84";
	}
}

class taxobj{
	public $type;
	public $properties;
	public $geometry;

	function __construct() {
		$this->type = "Feature";
		$this->geometry = null;
	}

	public function AddProperties($c,$s,$r){
		$taxprop = new taxproperties;
		$taxprop->COUNT = $c;
		$taxprop->SUM = $s;
		$taxprop->REG_CODE = $r;
		$this->properties = $taxprop;
	}
}

class taxmonthobj{
	public $type;
	public $properties;
	public $geometry;

	function __construct() {
		$this->type = "Feature";
		$this->geometry = null;
	}

	public function AddProperties($c,$s,$r,$m){
		$taxprop = new TaxMonthProperties;
		$taxprop->COUNT = $c;
		$taxprop->SUM = $s;
		$taxprop->REG_CODE = $r;
		$taxprop->MONTH = $m;
		$this->properties = $taxprop;
	}
}

class OverAlltaxobj{
	public $type;
	public $properties;
	public $geometry;

	function __construct() {
		$this->type = "Feature";
		$this->geometry = null;
	}

	public function AddProperties($vta,$vc,$vl,$vs,$vf,$vto,$reg){
		$taxprop = new OverAllproperties;
		$taxprop->VAL_TAX = $vta;
		$taxprop->VAL_CASE = $vc;
		$taxprop->VAL_LIC = $vl;
		$taxprop->VAL_STAMP = $vs;
		$taxprop->VAL_FAC = $vf;
		$taxprop->VAL_TOTAL = $vto;
		$taxprop->REG_CODE = $reg;
		$this->properties = $taxprop;
	}
}

class TaxAreaObj{
	public $type;
	public $properties;
	public $geometry;

	function __construct() {
		$this->type = "Feature";
		$this->geometry = null;
	}

	public function AddProperties($a,$r,$p,$v){
		$taxprop = new taxareaproperties;
		$taxprop->AREA_CODE = $a;
		$taxprop->REG_CODE = $r;
		$taxprop->PROV_CODE = $p;
		$taxprop->VAL_SUM = $v;
		$this->properties = $taxprop;
	}
}

class taxproperties{
	public $COUNT;
	public $SUM;
	public $REG_CODE;
}

class TaxMonthProperties{
	public $COUNT;
	public $SUM;
	public $REG_CODE;
	public $MONTH;
}

class OverAllproperties{
	public $VAL_TAX;
	public $VAL_CASE;
	public $VAL_LIC;
	public $VAL_STAMP;
	public $VAL_FAC;
	public $VAL_TOTAL;
	public $REG_CODE;
}

class taxareaproperties{
	public $AREA_CODE;
	public $REG_CODE;
	public $PROV_CODE;
	public $VAL_SUM;
}

if(isset($_GET["data"])){
	$x = new taxmap;
	$year = isset($_GET["year"])?$_GET["year"]:date('Y');
	$area = isset($_GET["area"])?intval($_GET["area"]):0;
	if($_GET["data"] == "reg"){
		$DB = new exDB;
		$DB->GetData("SELECT ilRegion, COUNT(IllegalID) AS C, SUM(ilComparativeMoney + ilFine + ilBribe + ilRegion) AS S FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? GROUP BY ilRegion ORDER BY ilRegion",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddRegTax($data["C"],floatval($data["S"]),$data["ilRegion"]);
		}
	}elseif($_GET["data"] == "test"){
		echo "update api";
		die;
	}elseif($_GET["data"] == "overall_reg"){
		include("taxdataOVA.php");
		for($i=1;$i<=10;$i++){
			@$x->AddOverAllRegTax(floatval($data["case"][$i])+floatval($data["stamp"][$i]),intval($data["casec"][$i]),intval($data["lic"][$i]),floatval($data["stamp"][$i]),intval($data["fac"][$i]),$i,$i);
		}
	}elseif($_GET["data"] == "overall_month"){
			include("../data/geojson/overall_sum_by_reg_code_month.geojson");
			die;
	}elseif($_GET["data"] == "overall_area"){
			include("../data/geojson/overall_sum_by_area_code.geojson");
			die;
	}elseif($_GET["data"] == "tax_reg"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, (SELECT SUM(stTax) AS S FROM `Stamp`,`Factory` WHERE stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? AND faRegion = RegionID) AS S FROM Region ORDER BY RegionID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddRegTax(floatval($data["S"]),floatval($data["S"]),$data["RegionID"]);
		}
	}elseif($_GET["data"] == "tax_month"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, gmValue, COUNT(StampID) AS C, SUM(stTax) AS S FROM (SELECT RegionID,GovMonthID, gmValue FROM Region,GovMonth ORDER BY RegionID,GovMonthID) AS X LEFT JOIN (SELECT StampID ,faRegion,faIssueDate, stTax FROM `Stamp`, `Factory` WHERE stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ?) AS ST ON RegionID = faRegion AND MONTH(faIssueDate) = gmValue GROUP BY RegionID,GovMonthID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddMonthTax($data["C"],floatval($data["S"]),$data["RegionID"],$data["gmValue"]);
		}
	}elseif($_GET["data"] == "tax_area"){
		$DB = new exDB;
		$DB->GetData("SELECT LPAD(AreaID,5,0) AS A, LPAD(arRegion,2,0) AS R, arProvince, (SELECT SUM(stTax) AS S FROM `Stamp`,`Factory` WHERE stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? AND faProvince = arProvince) AS S FROM Area ORDER BY AreaID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddAreaTax($data["A"],$data["R"],$data["arProvince"],$data["S"]);
		}
	}elseif($_GET["data"] == "illegal_reg"){
		include("taxdataOVA.php");
		for($i=1;$i<=10;$i++){
			@$x->AddRegTax(intval($data["casec"][$i]),intval($data["casec"][$i]),$i);
		}
	}elseif($_GET["data"] == "illegal_month"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, gmValue, COUNT(IllegalID) AS C, SUM(PAY) AS S FROM (SELECT RegionID,GovMonthID, gmValue FROM Region,GovMonth ORDER BY RegionID,GovMonthID) AS X LEFT JOIN (SELECT IllegalID , ilRegion, ilActDate, (ilComparativeMoney + ilFine + ilReturn) AS PAY FROM Illegal WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ?) AS ST ON RegionID = ilRegion AND MONTH(ilActDate) = GovMonthID GROUP BY RegionID,GovMonthID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddMonthTax($data["C"],floatval($data["S"]),$data["RegionID"],$data["gmValue"]);
		}
	}elseif($_GET["data"] == "illegal_area"){
		$DB = new exDB;
		$DB->GetData("SELECT LPAD(AreaID,5,0) AS A, LPAD(arRegion,2,0) AS R, arProvince, (SELECT SUM(ilComparativeMoney) AS S FROM `Illegal` WHERE YEAR(ilActDate + INTERVAL 3 MONTH) = ? AND ilArea = AreaID) AS S FROM Area ORDER BY AreaID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddAreaTax($data["A"],$data["R"],$data["arProvince"],$data["S"]);
		}
	}elseif($_GET["data"] == "license_reg"){
		include("taxdataOVA.php");
		for($i=1;$i<=10;$i++){
			@$x->AddRegTax(intval($data["lic"][$i]),intval($data["lic"][$i]),$i);
		}
	}elseif($_GET["data"] == "license_month"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, gmValue, COUNT(D) AS C, COUNT(D) AS S FROM (SELECT RegionID,GovMonthID, gmValue FROM Region,GovMonth ORDER BY RegionID,GovMonthID) AS X LEFT JOIN ( (SELECT faIssueDate AS D ,faRegion AS R FROM Factory WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) = ?) UNION (SELECT lbExpireDate, lbRegion FROM Label WHERE YEAR(lbExpireDate + INTERVAL 3 MONTH) = ?) UNION (SELECT slExtendDate, faRegion FROM SaleLicense , Factory WHERE slFactoryID = FactoryID AND YEAR(slExtendDate + INTERVAL 3 MONTH) = ?) UNION (SELECT tpDate, tpRegion FROM Transport WHERE YEAR(tpDate + INTERVAL 3 MONTH) = ?) ) AS X2 ON RegionID = R AND MONTH(D) = gmValue GROUP BY RegionID,GovMonthID
",array("iiii",$year,$year,$year,$year));
		while($data = $DB->FetchData()){
			$x->AddMonthTax($data["C"],floatval($data["S"]),$data["RegionID"],$data["gmValue"]);
		}
	}elseif($_GET["data"] == "license_area"){
		$DB = new exDB;
		$DB->GetData("SELECT LPAD(AreaID,5,0) AS A, LPAD(arRegion,2,0) AS R, arProvince, (SELECT COUNT(SaleLicenseID) AS C FROM `SaleLicense`,`Factory` WHERE slFactoryID = FactoryID AND YEAR(slIssueDate + INTERVAL 3 MONTH) = ? AND faProvince = arProvince) AS C FROM Area ORDER BY AreaID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddAreaTax($data["A"],$data["R"],$data["arProvince"],$data["C"]);
		}
	}elseif($_GET["data"] == "stamp_reg"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, (SELECT SUM(stTax) AS S FROM `Stamp`,`Factory` WHERE stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? AND faRegion = RegionID) AS S FROM Region ORDER BY RegionID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddRegTax(floatval($data["S"]),floatval($data["S"]),$data["RegionID"]);
		}
	}elseif($_GET["data"] == "stamp_month"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, gmValue, COUNT(StampID) AS C, SUM(stTax) AS S FROM (SELECT RegionID,GovMonthID, gmValue FROM Region,GovMonth ORDER BY RegionID,GovMonthID) AS X LEFT JOIN (SELECT StampID ,faRegion,faIssueDate, stTax FROM `Stamp`, `Factory` WHERE stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ?) AS ST ON RegionID = faRegion AND MONTH(faIssueDate) = gmValue GROUP BY RegionID,GovMonthID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddMonthTax($data["C"],floatval($data["S"]),$data["RegionID"],$data["gmValue"]);
		}
	}elseif($_GET["data"] == "stamp_area"){
		$DB = new exDB;
		$DB->GetData("SELECT LPAD(AreaID,5,0) AS A, LPAD(arRegion,2,0) AS R, arProvince, (SELECT SUM(stTax) AS S FROM `Stamp`,`Factory` WHERE stFacCode = FactoryID AND YEAR(stReleaseDate + INTERVAL 3 MONTH) = ? AND faProvince = arProvince) AS S FROM Area ORDER BY AreaID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddAreaTax($data["A"],$data["R"],$data["arProvince"],$data["S"]);
		}
	}elseif($_GET["data"] == "fac_reg"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, (SELECT COUNT(FactoryID) FROM `Factory` WHERE faRegion = RegionID AND YEAR(faIssueDate + INTERVAL 3 MONTH) <= ?) AS C FROM Region",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddRegTax($data["C"],floatval($data["C"]),$data["RegionID"]);
		}
	}elseif($_GET["data"] == "fac_month"){
		$DB = new exDB;
		$DB->GetData("SELECT RegionID, gmValue, COUNT(FactoryID) AS C FROM (SELECT RegionID,GovMonthID, gmValue FROM Region,GovMonth ORDER BY RegionID,GovMonthID) AS X LEFT JOIN (SELECT FactoryID,faRegion,faIssueDate FROM Factory  WHERE YEAR(faIssueDate + INTERVAL 3 MONTH) = ?) AS F ON RegionID = faRegion AND  MONTH(faIssueDate) = gmValue  GROUP BY RegionID,GovMonthID",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddMonthTax($data["C"],floatval($data["C"]),$data["RegionID"],$data["gmValue"]);
		}
	}elseif($_GET["data"] == "fac_area"){
		$DB = new exDB;
		$DB->GetData("SELECT LPAD(AreaID,5,0) AS A, LPAD(arRegion,2,0) AS R, arProvince, (SELECT COUNT(FactoryID) FROM `Factory` WHERE faProvince = arProvince AND YEAR(faIssueDate + INTERVAL 3 MONTH) <= ?) AS C FROM Area",array("i",$year));
		while($data = $DB->FetchData()){
			$x->AddAreaTax($data["A"],$data["R"],$data["arProvince"],$data["C"]);
		}
	}elseif($_GET["data"] == "area"){
?>{
"type": "FeatureCollection",
"crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
"features": [
<?php
		$DB = new exDB;
		$DB->GetData("SELECT LPAD(ilArea,5,'0') AS A, LPAD(ilRegion,2,'0') AS R, arProvince AS P, SUM(ilComparativeMoney + ilFine + ilBribe + ilRegion) AS S FROM `Illegal`,`Area` WHERE ilArea = AreaID GROUP BY ilArea ORDER BY ilRegion,ilArea");
		while($data = $DB->FetchData()){
?>{ "type": "Feature", "properties": { "AREA_CODE": "<?php echo $data["A"]?>", "REG_CODE": "<?php echo $data["R"]?>", "PROV_CODE": <?php echo $data["P"]?>.0, "VAL_SUM": <?php echo $data["S"]?> }, "geometry": null },
<?php
		}?>
]
}
<?php	}else{
		die;
?>{
"type": "FeatureCollection",
"crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
"features": [
{ "type": "Feature", "properties": { "COUNT": 1086, "SUM": 5, "REG_CODE": 1, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1035, "SUM": 5, "REG_CODE": 1, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1923, "SUM": 7, "REG_CODE": 1, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 422, "SUM": 10, "REG_CODE": 1, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1135, "SUM": 25, "REG_CODE": 1, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1104, "SUM": 4, "REG_CODE": 1, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1522, "SUM": 7, "REG_CODE": 1, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2284, "SUM": 1, "REG_CODE": 1, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 853, "SUM": 90, "REG_CODE": 1, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 763, "SUM": 18, "REG_CODE": 1, "MONTH": 12 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1320, "SUM": 36, "REG_CODE": 2, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 942, "SUM": null, "REG_CODE": 2, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2972, "SUM": null, "REG_CODE": 2, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 577, "SUM": null, "REG_CODE": 2, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1456, "SUM": null, "REG_CODE": 2, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 968, "SUM": null, "REG_CODE": 2, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1797, "SUM": null, "REG_CODE": 2, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2462, "SUM": null, "REG_CODE": 2, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 209, "SUM": null, "REG_CODE": 2, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1405, "SUM": null, "REG_CODE": 2, "MONTH": 10 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1592, "SUM": null, "REG_CODE": 3, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 383, "SUM": null, "REG_CODE": 3, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2806, "SUM": null, "REG_CODE": 3, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2468, "SUM": null, "REG_CODE": 3, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1270, "SUM": null, "REG_CODE": 3, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2488, "SUM": null, "REG_CODE": 3, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1366, "SUM": null, "REG_CODE": 3, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1798, "SUM": null, "REG_CODE": 3, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2430, "SUM": null, "REG_CODE": 3, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2026, "SUM": null, "REG_CODE": 3, "MONTH": 10 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2966, "SUM": null, "REG_CODE": 3, "MONTH": 12 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 540, "SUM": null, "REG_CODE": 4, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 989, "SUM": null, "REG_CODE": 4, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2031, "SUM": null, "REG_CODE": 4, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1104, "SUM": null, "REG_CODE": 4, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2449, "SUM": null, "REG_CODE": 4, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2991, "SUM": null, "REG_CODE": 4, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 192, "SUM": null, "REG_CODE": 4, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 498, "SUM": null, "REG_CODE": 4, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1866, "SUM": null, "REG_CODE": 4, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1202, "SUM": null, "REG_CODE": 4, "MONTH": 10 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 28, "SUM": null, "REG_CODE": 4, "MONTH": 12 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 275, "SUM": null, "REG_CODE": 5, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 493, "SUM": null, "REG_CODE": 5, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1610, "SUM": null, "REG_CODE": 5, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1022, "SUM": null, "REG_CODE": 5, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 22, "SUM": null, "REG_CODE": 5, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 359, "SUM": null, "REG_CODE": 5, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 327, "SUM": null, "REG_CODE": 5, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1566, "SUM": null, "REG_CODE": 5, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1323, "SUM": null, "REG_CODE": 5, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 120, "SUM": null, "REG_CODE": 5, "MONTH": 10 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2301, "SUM": null, "REG_CODE": 6, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2052, "SUM": null, "REG_CODE": 6, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2502, "SUM": null, "REG_CODE": 6, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1001, "SUM": null, "REG_CODE": 6, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1985, "SUM": null, "REG_CODE": 6, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1900, "SUM": null, "REG_CODE": 6, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1358, "SUM": null, "REG_CODE": 6, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1235, "SUM": null, "REG_CODE": 6, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 507, "SUM": null, "REG_CODE": 6, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2586, "SUM": null, "REG_CODE": 6, "MONTH": 11 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2897, "SUM": null, "REG_CODE": 6, "MONTH": 12 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2, "SUM": null, "REG_CODE": 7, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1201, "SUM": null, "REG_CODE": 7, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1789, "SUM": null, "REG_CODE": 7, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 413, "SUM": null, "REG_CODE": 7, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 883, "SUM": null, "REG_CODE": 7, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2906, "SUM": null, "REG_CODE": 7, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 88, "SUM": null, "REG_CODE": 7, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1945, "SUM": null, "REG_CODE": 7, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2321, "SUM": null, "REG_CODE": 7, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2948, "SUM": null, "REG_CODE": 8, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1193, "SUM": null, "REG_CODE": 8, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2968, "SUM": null, "REG_CODE": 8, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2898, "SUM": null, "REG_CODE": 8, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2457, "SUM": null, "REG_CODE": 8, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1836, "SUM": null, "REG_CODE": 8, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1040, "SUM": null, "REG_CODE": 8, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 525, "SUM": null, "REG_CODE": 8, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 13, "SUM": null, "REG_CODE": 8, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2311, "SUM": null, "REG_CODE": 8, "MONTH": 10 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 221, "SUM": null, "REG_CODE": 9, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2439, "SUM": null, "REG_CODE": 9, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 967, "SUM": null, "REG_CODE": 9, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1909, "SUM": null, "REG_CODE": 9, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 187, "SUM": null, "REG_CODE": 9, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 99, "SUM": null, "REG_CODE": 9, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 707, "SUM": null, "REG_CODE": 9, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1755, "SUM": null, "REG_CODE": 9, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2511, "SUM": null, "REG_CODE": 9, "MONTH": 9 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 893, "SUM": null, "REG_CODE": 9, "MONTH": 10 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2080, "SUM": null, "REG_CODE": 10, "MONTH": 1 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 709, "SUM": 5, "REG_CODE": 10, "MONTH": 2 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2263, "SUM": 6, "REG_CODE": 10, "MONTH": 3 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2479, "SUM": 7, "REG_CODE": 10, "MONTH": 4 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2148, "SUM": 8, "REG_CODE": 10, "MONTH": 5 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1792, "SUM": 9, "REG_CODE": 10, "MONTH": 6 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1643, "SUM": 11, "REG_CODE": 10, "MONTH": 7 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 2984, "SUM": 30, "REG_CODE": 10, "MONTH": 8 }, "geometry": null },
{ "type": "Feature", "properties": { "COUNT": 1220, "SUM": 10, "REG_CODE": 10, "MONTH": 9 }, "geometry": null }
]
}
<?php
	}
	header("Access-Control-Allow-Origin: *");
	echo json_encode($x);
	die;
}

$fn = isset($_POST["fn"])?$_POST["fn"]:"";
$mode = isset($_POST["mode"])?$_POST["mode"]:0;
switch($fn){
	case "getGPS" :
		break;
	case "getdata" :
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
						$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS fYear FROM Factory GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif($data->job == 2){
						$DB->GetData("SELECT YEAR(ilActDate + INTERVAL 3 MONTH) AS fYear FROM Illegal GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif($data->job == 3){
						$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS fYear FROM Factory GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif(($data->job == 4)){
						$DB->GetData("SELECT YEAR(lbExpireDate + INTERVAL 3 MONTH) AS fYear FROM `Label` GROUP BY fYear ORDER BY fYear DESC");
						for($x=1;$fdata = $DB->FetchData();$x++){
							$sdata = new exItem;
							$sdata->id = $x;
							$sdata->value = $fdata["fYear"];
							$sdata->label = "ปีงบประมาณ ".($fdata["fYear"] + 543);
							array_push($data->year,$sdata);
						}
					}elseif(($data->job == 5)){
						$DB->GetData("SELECT YEAR(faIssueDate + INTERVAL 3 MONTH) AS fYear FROM Factory GROUP BY fYear ORDER BY fYear DESC");
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
						$sdata = new exItem;
						$sdata->id = 1;
						$sdata->value = 2016;
						$sdata->label = "ปีงบประมาณ 2559";
						array_push($data->year,$sdata);
					}


					$sdata = new exItem;
					$sdata->id = 0;
					$sdata->value = "00";
					$sdata->label = "ทุกภาค";
					array_push($data->region,$sdata);

					$DB->GetData("SELECT RegionID, rgNameTH FROM `Region`");
					while($fdata = $DB->FetchData()){
						$sdata = new exItem;
						//$sdata->id = $fdata["RegionID"];
						$sdata->id = substr(100 + $fdata["RegionID"],1);
						$sdata->value = substr(100 + $fdata["RegionID"],1);
						$sdata->label = "สรรพสามิต".$fdata["rgNameTH"];
						array_push($data->region,$sdata);
					}

					$sdata = new exItem;
					$sdata->id = 0;
					$sdata->value = "00";
					$sdata->label = "ทุกพื้นที่";
					array_push($data->province,$sdata);

					$DB->GetData("SELECT `AreaID`, `arName` FROM `Area`");
					while($fdata = $DB->FetchData()){
						$sdata = new exItem;
						$sdata->id = $fdata["AreaID"];
						$sdata->value = substr(100000 + $fdata["AreaID"],1);
						$sdata->label = $fdata["arName"];
						array_push($data->province,$sdata);
					}
				}else{
					$S_region = isset($_POST["value"])?intval($_POST["value"]):0;

					$filterLabel = "ทุกพื้นที่";
					$DB->GetData("SELECT `AreaID`, `arName` FROM `Area` WHERE ? IN (0,arRegion)",array("i",$S_region));

					if($DB->GetNumRows()>0){
						$data = array();
						$sdata = new exItem;
						$sdata->id = 0;
						$sdata->value = "00";
						$sdata->label = $filterLabel;
						array_push($data,$sdata);

						while($fdata = $DB->FetchData()){
							$sdata = new exItem;
							$sdata->id = $fdata[0];
							$sdata->value = substr(100000 + $fdata[0],1);
							$sdata->label = $fdata[1];
							array_push($data,$sdata);
						}
					}else{
						$data = null;
					}
				}
			break;
	default : $data = null;
}
header("Access-Control-Allow-Origin: *");
echo json_encode($data);
?>
