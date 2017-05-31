<?php
	class exResult{
		public $ResultCode;//0=success
		public $ResultMsg;//ข้อความที่ให้นำไปแสดง
	}

	class exItem{
		public $id;//ID ของฉลาก
		public $value;//value ใน Option
		public $label;//ตัวหนังสือที่แสดงใน Option
	}

	class exETC{		
		const C_TH = 0;
		const C_EN = 1;
		const C_CN = 2;
		
		private $TH_MONTH_FULL;
		private $TH_MONTH_SHORT;
		private $CN_MONTH;
		
		private $TH_UNIT;
		private $EN_UNIT;
				
	    function __construct() {//Initital variable in class
			$this->TH_MONTH_FULL = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
			$this->TH_MONTH_SHORT = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$this->CN_MONTH = array("","1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月");
			$this->TH_UNIT = array("","เครื่อง","ใบ","ชิ้น","คู่","อัน","กล่อง","ตัว","หน่วย","เส้น","สาย","ชุด");
			$this->EN_UNIT = array("","device","card","piece","pair","piece","box","piece","unit","line","line","set");
		}

		function __destruct(){//Terminated DB connection
		}
		
		
		private function GetDateFromMySql($MySQLdate){//0123-56-89 12:45:78
			$strYear = substr($MySQLdate,0,4);
			$strMonth = intval(substr($MySQLdate,5,2));
			$strDay = intval(substr($MySQLdate,8,2));
			$time = substr($MySQLdate,11);
			return array($strYear,$strMonth,$strDay,$time);
		}

		public function GetMonthName($mm,$country=self::C_TH){
			switch($country){
				case self::C_TH :
						$MonthName= $this->TH_MONTH_SHORT[$mm];
					break;
				case self::C_CN :
						$MonthName= $this->CN_MONTH[$mm];
					break;
				case self::C_EN :
						$MonthName= date("F");
					break;
				default :
					$MonthName= date("F");
			}			
			return $MonthName;
		}

		public function GetMonthFullName($mm){
			return  $this->TH_MONTH_FULL[$mm];
		}

		public function GetFullDate($country,$MySQLdate=NULL,$showtime=NULL){
			if(is_null($MySQLdate)){
				$strYear = date("Y");
				$strMonth = date("n");
				$strDay = date("j");
				$time = date("H:i:s");
			}else{
				list($strYear,$strMonth,$strDay,$time) = $this->GetDateFromMySql($MySQLdate);
			}			
			switch($country){
				case self::C_TH :
						$retrunDate = $strDay." ".$this->TH_MONTH_FULL[$strMonth]." ".($strYear += 543);
					break;
				case self::C_CN :
						$retrunDate = $strYear."年".$this->CN_MONTH[$strMonth].$strDay."日";
					break;
				case self::C_EN :
						$retrunDate = date("F j, Y");
					break;
				default :
					$retrunDate = date("F j, Y");
			}			
			return $retrunDate.(is_null($showtime)?"":" ".$time);
		}
		
		public function GetShortDate($country,$MySQLdate=NULL,$showtime=NULL){
			if(is_null($MySQLdate)){
				$strYear = date("Y");
				$strMonth = date("n");
				$strDay = date("j");
				$time = date("H:i:s");
			}else{
				list($strYear,$strMonth,$strDay,$time) = $this->GetDateFromMySql($MySQLdate);
			}			
			switch($country){
				case self::C_TH :
						$retrunDate = $strDay." ".$this->TH_MONTH_SHORT[$strMonth]." ".($strYear += 543);
					break;
				case self::C_CN :
						$retrunDate = $strYear."年".$this->CN_MONTH[$strMonth].$strDay."日";
					break;
				case self::C_EN :
						$retrunDate = date("F j, Y");
					break;
				default :
					$retrunDate = date("F j, Y");
			}			
			return $retrunDate.(is_null($showtime)?"":" ".$time);
		}
		
		public function DMY2SQLDate($country,$DMY){
			switch($country){
				case self::C_TH :
						$strYear = intval(substr($DMY,6,4)) - 543;
					break;
				default : $strYear = substr($DMY,6,4);
			}
			return $strYear."-".substr($DMY,3,2)."-".substr($DMY,0,2);
		}
		
		public function GetDMY($country,$MySQLdate=NULL,$showtime=NULL){
			if(is_null($MySQLdate)){
				$strYear = date("Y");
				$strMonth = date("n");
				$strDay = date("j");
				$time = date("H:i");
			}else{
				list($strYear,$strMonth,$strDay,$time) = $this->GetDateFromMySql($MySQLdate);
				$time = substr($time,0,5);
			}
			switch($country){
				case self::C_TH :
						$retrunDate = sprintf("%02d/%02d/%04d",$strDay,$strMonth,($strYear += 543));
					break;
				default :
					$retrunDate = sprintf("%02d/%02d/%04d",$strDay,$strMonth,$strYear);
			}
			return $retrunDate.(is_null($showtime)?"":" ".$time);
		}
				
		public function GetVeryShortDate($country,$MySQLdate=NULL,$showtime=NULL){
			if(is_null($MySQLdate)){
				$strYear = date("Y");
				$strMonth = date("n");
				$strDay = date("j");
				$time = date("H:i");
			}else{
				list($strYear,$strMonth,$strDay,$time) = $this->GetDateFromMySql($MySQLdate);
				$time = substr($time,0,5);
			}
			switch($country){
				case self::C_TH :
						$retrunDate = $strDay." ".$this->TH_MONTH_SHORT[$strMonth];
					break;
				case self::C_CN :
						$retrunDate = $this->CN_MONTH[$strMonth].$strDay."日";
					break;
				case self::C_EN :
						$retrunDate = date("F j");
					break;
				default : $retrunDate = date("F j");
			}
			return $retrunDate.(is_null($showtime)?"":" ".$time);
		}
				
	}
?>
