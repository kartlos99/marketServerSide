<?php

include_once 'config.php';

$filter_text="";

if(isset($_GET["filter_text"]) && $_GET["filter_text"]!=""){
    $filter_text = $_GET["filter_text"];
}

$sql="
SELECT
    p.id,
    typeID,
    p.name,
    pt.name AS type,
    pg.name AS gr,
    p.image AS p_img,
    pt.image as pt_img,
    pg.image as pg_img
FROM
    `products` p
LEFT JOIN producttype pt ON
    p.typeID = pt.id
LEFT JOIN productgr pg ON
    pt.grID = pg.id

";
$arr = [];

$sql_P = "
SELECT * FROM `prodvsparam` 
ORDER by `prodTypeID`";

$result = $conn->query($sql);
$result_P = $conn->query($sql_P);
 

$arr_p = [];

if (!$result_P){
    die("SQL Error:\n" . $sql_P);
}else{
	if (mysqli_num_rows($result_P) > 0) {
        foreach($result_P as $row){            
            $typeID = $row["prodTypeID"];            
            if (!isset($arr_p[$typeID])){                
                $arr_p[$typeID] = [];                
            }
            array_push($arr_p[$typeID], $row["paramID"]);            
        }
    }
}

if (!$result){
    die("SQL Error:\n" . $sql);
}else{
	if (mysqli_num_rows($result) > 0) {
    
            foreach($result as $row){
                $tp = $row["typeID"];
                if (isset($arr_p[$tp])){
                    $row["param"] = $arr_p[$tp];
                }else{
                    $row["param"] = [];
                }                
            	$arr[] = $row;
            }
        }
}


echo json_encode($arr);

?>