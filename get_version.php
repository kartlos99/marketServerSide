<?php

include_once 'config.php';

// **********  migvasvs cxrilis versioebi rom shevadarot localur cxrilebs  *********
$sql="
SELECT `table_name`,`version`,`maxID` FROM `versions` 
LEFT JOIN max_ids ON `table_name` = tb
";

$result = $conn->query($sql);
$arr = [];

if (!$result){
    die("SQL Error:\n" . $sql_param);
}else{
	    foreach($result as $row){            
            $arr[] = $row;            
        }
}

echo json_encode($arr);