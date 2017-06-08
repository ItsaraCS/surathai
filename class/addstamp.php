<?php
include("database.class.php");
$DB = new exDB;

for($x=1672558100;$x<=1672558999;$x++){
	$data = array(
		"StampRemainID" => "00".$x,
		"srBranch" => 550101,
		"srAmount" => 100
	);
	$DB->InsertData("StampRemain",$data);
}
?>
