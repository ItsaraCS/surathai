<?php
	class AcitityAPI{
		function __construct(){
			if (function_exists('aaa')) {
                echo "YES";
            } else {
                echo "NO";
            }

			$this->aaa();
		}

		function aaa() {
			echo '<br>AAAAAAAAAAA';
		}

		function getActivityList($categoryParam){
			if(isset($categoryParam)){
				global $mysqli;
				$itemList = array();
				
				if(isset($categoryParam["page"]))
	  				$page = $categoryParam["page"];
	  			else
	  				$page = 1;

				$perPage = 3;
		  		$start = ($page - 1) * $perPage;

		  		$dateFirstDigit = $this->connectDb->parseArabicToThaiNumForSQL("SUBSTRING(activity_date, 9, 1)");
				$dateSecondDigit = $this->connectDb->parseArabicToThaiNumForSQL("SUBSTRING(activity_date, 10, 1)");
				$dateFormat = $dateFirstDigit.", ".$dateSecondDigit;
				$monthFormat = $this->connectDb->parseArabicToThaiMonthForSQL("SUBSTRING(activity_date, 6, 2)");
				$year = "SUBSTRING(activity_date, 1, 4) + 543";
				$yearFirstDigit = $this->connectDb->parseArabicToThaiNumForSQL("SUBSTRING(".$year.", 1, 1)");
				$yearSecondDigit = $this->connectDb->parseArabicToThaiNumForSQL("SUBSTRING(".$year.", 2, 1)");
				$yearThirdDigit = $this->connectDb->parseArabicToThaiNumForSQL("SUBSTRING(".$year.", 3, 1)");
				$yearFourthDigit = $this->connectDb->parseArabicToThaiNumForSQL("SUBSTRING(".$year.", 4, 1)");
				$yearFormat = $yearFirstDigit.", ".$yearSecondDigit.", ".$yearThirdDigit.", ".$yearFourthDigit;
				$activity_date = "CONCAT(".$dateFormat.", ' ', ".$monthFormat.", ' ', ".$yearFormat.") AS activity_date";

				$sqlCmd = "SELECT a.activity_id, a.category_id, activity_name, activity_description, ".$activity_date.", ";
				$sqlCmd .= "c.category_name, ai.activity_image_id, ai.activity_image_url ";
				$sqlCmd .= "FROM activities a ";
				$sqlCmd .= "INNER JOIN categories c ";
				$sqlCmd .= "ON a.category_id = c.category_id ";
				$sqlCmd .= "INNER JOIN activities_images ai ";
				$sqlCmd .= "ON a.activity_id = ai.activity_id ";
				$sqlCmd .= "WHERE a.category_id = '".$categoryParam["categoryID"]."' ";
				$sqlCmd .= "GROUP BY activity_id ";
				$sqlCmd .= "ORDER BY activity_id DESC ";
				$sqlCmd .= "LIMIT ".$start.", ".$perPage;
				$query = $mysqli->query($sqlCmd)
					or die("<b>SQL error</b>: \"".$sqlCmd."\"<br><b>Parse error</b>: ".$mysqli->error);

				while($item = $query->fetch_assoc()){
					if(in_array("activity_image_url", array_keys($item))){
						$sqlCmdSub = "SELECT activity_image_url ";
						$sqlCmdSub .= "FROM activities_images ";
						$sqlCmdSub .= "WHERE activity_id = '".$item["activity_id"]."' ";
						$sqlCmdSub .= "ORDER BY activity_image_id";

						$item["activity_image_url_list"] = $this->connectDb->getListObj($sqlCmdSub);
					}
					array_push($itemList, $item);
				}

				echo json_encode($itemList, JSON_UNESCAPED_UNICODE);
			}else
				return false;
		}

		function getTotalPage($categoryID){
			if(isset($categoryID)){
				global $mysqli;

				$sqlCmd = "SELECT * FROM activities ";
				$sqlCmd .= "WHERE category_id = '".$categoryID."'";
				$query = $mysqli->query($sqlCmd)
					or die("<b>SQL error</b>: \"".$sqlCmd."\"<br><b>Parse error</b>: ".$mysqli->error);
					
				$totalItem = mysqli_num_rows($query);
				$perPage = 3;
				$totalPage = ceil($totalItem / $perPage);

				echo json_encode($totalPage, JSON_UNESCAPED_UNICODE);
			}else
				return false;
		}
	}

	$self = new AcitityAPI();
?>