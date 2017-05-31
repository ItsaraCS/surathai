<?php
        include("database.class.php");
        $con = new exDB;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test Core Database</title>
</head>

<body>
<pre>
<?php
        $tablelist = array();
        $con->GetData("SHOW TABLES");

        while($tables = $con->FetchData()){
                array_push($tablelist,$tables[0]);
        }

        $i =0;
        $fname = "";
        $ftype = "";
        foreach($tablelist as $table){
                echo "                  \"$table\" => \"";
                $con->GetData("DESCRIBE `$table`");
                while($col = $con->FetchData()){
                        $fname .= ",".$col[0];
                        if(strstr($col[1],"int")){
                                $ftype .= "i";
                        }elseif(strstr($col[1],"decimal")){
                                $ftype .= "d";
                        }elseif(strstr($col[1],"double")){
                                $ftype .= "d";
                        }else{
                                $ftype .= "s";
                        }
                }
                echo "$ftype$fname\",\n";
                $fname = "";
                $ftype = "";
        }
?>
</pre>
</body>
</html>
