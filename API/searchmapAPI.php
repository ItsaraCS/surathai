<?php //SearchMap API
	require_once("../class/database.class.php");
	require_once("../class/util.class.php");

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
	public function AddOverAllRegTax($vta,$vc,$vl,$vs,$vf,$vto,$reg){
		$dat = new OverAlltaxobj;
		$dat->AddProperties($vta,$vc,$vl,$vs,$vf,$vto,$reg);
		array_push($this->features,$dat);
	}
	public function AddSearchMap($facdata){
		$dat = new SearchObj;
		$id = count($this->features)+1;
		$dat->AddProperties($id,$facdata);
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

class SearchObj{
        public $type;
        public $properties;
        public $geometry;

        function __construct() {
                $this->type = "Feature";
                $this->geometry = new GeoObj;
        }

	public function AddProperties($id,$facdata){
		$searchprop = new searchproperties;
		$searchprop->ID = $id;
		$searchprop->FACTORY_TNAME = $facdata["faName"];
		$searchprop->REGISTER_CODE = $facdata["faCode"];
		$searchprop->FOUNDER_TNAME = $facdata["faContact"];
		$searchprop->CLASS = $facdata["suName"];
		$searchprop->ADDRESS = $facdata["faAddress"];
		$searchprop->LAT = floatval($facdata["faLat"]);
		$searchprop->LON = floatval($facdata["faLong"]);
		$searchprop->AMP_TNAME = "";
		$searchprop->PROV_TNAME = $facdata["pvName"];
		$searchprop->REG_TNAME = $facdata["reg"];
		$searchprop->AREA = "";
		$searchprop->BRAN_TNAME = "";
		$searchprop->NOTE = null;
		$this->properties = $searchprop;
		$this->geometry->SetGeo($facdata["faLat"],$facdata["faLong"]);
	}
}

class taxproperties{
	public $COUNT;
	public $SUM;
	public $REG_CODE;
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

class searchproperties{
	public $ID;
	public $FACTORY_TNAME;
	public $REGISTER_CODE;
	public $FOUNDER_TNAME;
	public $CLASS;
	public $ADDRESS;
	public $LAT;
	public $LON;
	public $AMP_TNAME;
	public $PROV_TNAME;
	public $REG_TNAME;
	public $AREA;
	public $BRAN_TNAME;
	public $NOTE;
}

class GeoObj{
	public $type;
	public $coordinates;
	public function SetGeo($lat,$long){
		$this->type = "Point";
		$this->coordinates = array(floatval($long),floatval($lat));
	}
}

$x = new taxmap;
$DB = new exDB;
$DB->GetData("SELECT FactoryID, faName,faCode,faContact,suName,faAddress,faLat,faLong,pvName,CONCAT('ภาค',faRegion) AS reg FROM `Factory` LEFT JOIN `SuraType` ON faSuraType = SuraTypeID LEFT JOIN Province ON faProvince = ProvinceID");
while($data = $DB->FetchData()){
	$x->AddSearchMap($data);
}
echo json_encode($x);
?>
