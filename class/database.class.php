<?php 

define("_DBHOST","localhost");
define("_DBUSER","sura");
define("_DBPASSWORD","45Xc7JnWU8a3");
define("_DBNAME","surathai");

//Database Class
class exDB{	
	
	private $db;	//Database connector
	private $SQL;	//Current SQL String
	private $Result;		//Current SQL Result
	private $Columns;		//Current Column in laat query result
	private $num_rows;		//Number of rows in last query
	private $affected_rows;	//Number of affected rows in last query
	
	private $tmpData;		//temporary data
	
	protected $DBStructure = array(
			//1st = datatype[i=int,d=decimal point,s=string,b=blob]; 2nd...lastest = field
			"Act" => "is,ActID,acName",
			"Area" => "iiis,AreaID,arProvince,arRegion,arName",
			"Label" => "iiiisssddsssss,LabelID,lbDegree,lbType,lbFacCode,lbLicense,lbIssueDate,lbExpireDate,lbLat,lbLong,lbBrand,lbContact,lbFacName,lbPicture,lbAddress",
			"Province" => "iis,ProvinceID,pvRegion,pvName",
			"Region" => "isss,RegionID,rgCode,rgNameEN,rgNameTH",
			"SuraType" => "is,SuraTypeID,stName"
		);
			
	public $ErrorMsg;		//Error Message on String format
	
    function __construct() {//Initital variable in class
		$this->InitDB();
	}
	
	function __destruct(){//Terminated DB connection
		$this->db->close();
	}
	
	private function CreateStatement($data){
		$statment = "\$stmt->bind_param(";
		$this->tmpData = array();
		$i=0;
		foreach($data as $value){
			if($i==0){
				$statment .= "'$value'";
				$this->SQL .= "<br>\n Paramlist ($value)";
			}else{
				$this->SQL .= "<br>\n$i = $value";
				$this->tmpData[$i] = $value;
				$statment .= ",\$this->tmpData[$i]";
			}
			$i++;
		}
		return $statment.");";
	}
	
	public function InitDB(){
		$Host = _DBHOST;
		$User = _DBUSER;
		$Password = _DBPASSWORD;
		$DBName = _DBNAME;

		$this->SQL = "";
		$this->Result = NULL;
		$this->NumRow = 0;
		$this->EffectRow = 0;
		
		if($this->db){
			$this->db->close();
			unset($this->db);
		}

		$this->db = new MySQLi($Host,$User,$Password,$DBName);

		if(!$this->db){
			$this->ErrorMsg = "Connect Database error";
			die($this->ErrorMsg);
		}else{
			$this->Query("SET NAMES UTF8");
			$this->ErrorMsg = "";
			return $this->db;
		}
	}
	
	public function GetLastSQL(){//Get Last SQL String
		return $this->SQL;
	}

	public function Query($sql,$param=NULL){//Query with out result
		$this->SQL = $sql;
		$stmt = $this->db->prepare($sql);
		if(!is_null($param)){
			eval($this->CreateStatement($param));
		}
		$success = $stmt->execute();
		$this->affected_rows = $stmt->affected_rows;
		return $success;
	}
	
	public function GetNumRows(){
		return $this->num_rows;
	}
	
	public function GetAffectRows(){
		return $this->affected_rows;
	}
	
	public function SeekResultData($rowNo = 0/*0=1st row*/){
		$this->Result->data_seek($rowNo);
	}
	
	public function Now(){// Get Current Datetime
		return $this->GetDataOneField("SELECT NOW()");
	}

	public function Password($password){// Get Current Datetime
		return $this->GetDataOneField("SELECT PASSWORD(?)",array("s",$password));
	}
	
	public function MD5($string){// Get Current Datetime
		return $this->GetDataOneField("SELECT MD5(?)",array("s",$string));
	}
	
	public function NextID($Table){//Get Next ID
		$id = 0;
		$row = $this->GetDataOneRow("SHOW TABLE STATUS LIKE '$Table'");
		if(is_null($row["Auto_increment"])){
			list($ftype,$f1st,$trash) = explode(",",$this->DBStructure[$Table],3);
			$id = $this->GetDataOneField("SELECT MAX($f1st) + 1 FROM `$Table`");
			if(is_null($id)) $id = 1;
		}else{
			$id = $row["Auto_increment"];
		}
		return $id;
	}
	
	public function GetDataOneField($sql,$param=NULL){
		if(strpos($sql,"LIMIT")) $this->SQL = $sql;
		else  $this->SQL = $sql." LIMIT 1";
		$stmt = $this->db->prepare($sql);
		if(!is_null($param)){
			eval($this->CreateStatement($param));
		}
		if (!$stmt->execute()){
			$this->num_rows = 0;
			$this->affected_rows = 0;
			$this->Columns = '';
			$this->Result = NULL;
			return "SQL Query Error";
		}else{
			$stmt->store_result();			
			$this->Result = $stmt;
			$stmt->bind_result($data);
			$stmt->fetch();
			return $data;//return null when select found 0 row you can check with function is_null()
		}
	}

	public function GetDataOneRow($sql,$param=NULL){
		if(strpos($sql,"LIMIT")) $this->SQL = $sql;
		else  $this->SQL = $sql." LIMIT 1";
		if(is_null($param)){
			$this->GetData($sql);
		}else{
			$this->GetData($sql,$param);
		}
		return $this->FetchData();
	}

	public function GetData($sql,$param=NULL){
		$this->SQL = $sql;
		$stmt = $this->db->prepare($sql);
		if(!is_null($param)){
			eval($this->CreateStatement($param));
		}
		if (!($stmt->execute())){
			$this->num_rows = 0;
			$this->affected_rows = 0;
			$this->Columns = '';
			$this->Result = NULL;
			return false;
		}else{
			$stmt->store_result();
			$this->num_rows = $stmt->num_rows;
			$this->affected_rows = $stmt->affected_rows;

			$metaResults = $stmt->result_metadata();
		    $fields = $metaResults->fetch_fields();
		    $statementParams='';
			
			foreach($fields as $field){
    		     if(empty($statementParams)){
        		     $statementParams.="\$column['".$field->name."']";
	        	 }else{
	            	 $statementParams.=", \$column['".$field->name."']";
		         }
		    }
		
			$this->Columns = $statementParams;
			$this->Result = $stmt;
			$statment="\$this->Result->bind_result(".$this->Columns.");";
		    eval($statment);
			$this->tmpData = $column;
			return true;
		}
    }

	public function FetchData($index=NULL){//Load data in current row
		if($this->Result){
		   	if($this->Result->fetch()){
				if(is_null($index)){
					$i=0;
					foreach($this->tmpData as $value){
						$this->tmpData[$i++] = $value;
					}
				}
				return $this->tmpData;
			}else{
				return false;
			}
		}else{
			return false;
		}
    }

	public function InsertData($Table,$Data){
		if(isset($this->DBStructure[$Table])){
			$exparam = "";
			$dbsDetail = explode(",",$this->DBStructure[$Table]);
			for($x=1; $x < count($dbsDetail); $x++){
				if($x==1){
					$sql = "INSERT INTO `$Table` (".$dbsDetail[1];
					$sqlext = ") VALUES ( ?";
					$statment = "\$stmt->bind_param(\"".$dbsDetail[0]."\", \$".$dbsDetail[1];
				}else{
					$sql .= ", ".$dbsDetail[$x];
					$sqlext .= ", ?";
					$statment .= ", \$".$dbsDetail[$x];
				}
				if(isset($Data[$dbsDetail[$x]])){
					${$dbsDetail[$x]} = $Data[$dbsDetail[$x]];
					$exparam .= "<br>\n$x = ".$Data[$dbsDetail[$x]];
				}else{
					switch(substr($dbsDetail[0],$x-1,1)){
						case 'i' : ${$dbsDetail[$x]} = 0;break;
						case 'd' : ${$dbsDetail[$x]} = 0.0;break;
						case 's' : ${$dbsDetail[$x]} = '';break;
						case 'b' : ${$dbsDetail[$x]} = '';break;
					}
				}
			}
			$sql .= $sqlext.")";
			$this->SQL = $sql;
			$this->SQL .= "<br>\nParamlist (".$dbsDetail[0].")".$exparam;
			$statment .= ");";
			$stmt = $this->db->prepare($sql);
		    eval($statment);
			$success = $stmt->execute();
			$this->affected_rows = $stmt->affected_rows;
			return $success;
		}else{
			return false;
		}
	}
	
	public function UpdateData($Table,$Data,$Where,$param=NULL){
		if(isset($this->DBStructure[$Table])){			
			$sql = "UPDATE `$Table` SET ";
			$statment = "\$stmt->bind_param(\"";
			$updateType = "";
			$updateField = "";
			$exparam = "";
			$dbsDetail = explode(",",$this->DBStructure[$Table]);
			$c = 1;
			for($x=1; $x < count($dbsDetail); $x++){
				if(isset($Data[$dbsDetail[$x]])){
					$sql .= $dbsDetail[$x]." = ?, ";
					$exparam .= "<br>\n$c = ".$Data[$dbsDetail[$x]];
					$updateType .= substr($dbsDetail[0],$x-1,1);
					$updateField .= ", \$".$dbsDetail[$x];
					${$dbsDetail[$x]} = $Data[$dbsDetail[$x]];
					$c++;
				}
			}
			if(!is_null($param)){
				$this->tmpData = array();
				$updateType .= $param[0];
				for($x=1;$x < count($param);$x++){
					$exparam .= "<br>\n$c = ".$param[$x];
					$this->tmpData[$x] = $param[$x];
					$updateField .= ", \$this->tmpData[$x]";
					$c++;
				}
			}
			$sql = substr($sql,0,strlen($sql)-2)." WHERE ".$Where;
			$statment .= $updateType."\"".$updateField.");";
			$this->SQL = $sql;
			$this->SQL .= "<br>\nParamlist (".$updateType.")".$exparam;
			$stmt = $this->db->prepare($sql);
		    eval($statment);
			$success = $stmt->execute();
			$this->affected_rows = $stmt->affected_rows;
			return $success;
		}else{
			return false;
		}
	}
	
	public function DeleteData($Table,$Where,$param=NULL){
		if(is_null($param)){
			return $this->Query("DELETE FROM `$Table` WHERE $Where");
		}else{
			return $this->Query("DELETE FROM `$Table` WHERE $Where",$param);
		}
	}
}
?>
