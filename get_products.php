<?php

include_once 'config.php';

$filter_text="";

if(isset($_GET["filter_text"]) && $_GET["filter_text"]!=""){
    $filter_text = $_GET["filter_text"];
}

// **********  migvaqvs yvela productis chamonatvali  *********
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

// **********  romel products ra parametrebi aqvs  *********
$sql_P = "
SELECT * FROM `prodvsparam` 
ORDER by `prodTypeID`";

// **********  romel products ra shefutvis tipi aqvs  *********
$sql_Pack = "
SELECT `prodTypeID`, p.packingID, valuetext FROM `prodvspack` p
LEFT JOIN dictionaryitems d on p.packingID = d.id
ORDER by prodTypeID, d.sortID";

$result = $conn->query($sql);
$result_P = $conn->query($sql_P);
$result_Pack = $conn->query($sql_Pack);
 
$arr = [];
$arr_p = [];
$arr_pack = [];

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

if (!$result_Pack){
    die("SQL Error:\n" . $sql_Pack);
}else{
    if (mysqli_num_rows($result_Pack) > 0) {
        foreach($result_Pack as $row){            
            $typeID = $row["prodTypeID"];            
            if (!isset($arr_pack[$typeID])){                
                $arr_pack[$typeID] = [];                
            }
            array_push($arr_pack[$typeID], $row["packingID"]);            
        }
    }
}

//  *****  titoeul produqs vabamt tavis dasashveb 
//  *****  paramerebs da shefutvis tipebs  ******
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

            if (isset($arr_pack[$tp])){
                $row["packs"] = $arr_pack[$tp];
            }else{
                $row["packs"] = [];
            }       
                     
        	$arr[] = $row;
        }
    }
}

// ***********  mogvaqvs yvela paramerti ******************
$sql = "SELECT id, code, name, measureUnit FROM `paramiters`";
$result = mysqli_query($conn, $sql);
$p_arr = [];

if (!$result){
    die("SQL Error:\n" . $sql);
}else{
	if (mysqli_num_rows($result) > 0) {
        foreach($result as $row){
        	$p_arr[] = $row;
        }
    }
}

// ***********  mogvaqvs yvela shefutvis tipi  ***************
$sql = "
SELECT di.id, di.code, di.valueText FROM `dictionaryitems` di
LEFT JOIN dictionary d ON di.dictionaryID = d.id
WHERE d.`code` = 'packing_type'
ORDER by di.sortID";

$result = mysqli_query($conn, $sql);
$pack_arr = [];

if (!$result){
    die("SQL Error:\n" . $sql);
}else{
    if (mysqli_num_rows($result) > 0) {
        foreach($result as $row){
            $pack_arr[] = $row;
        }
    }
}

// ********* productebi, parametrebi, shefutvis tipebi
// ********* yvela ertad mogvaqvs  *******************

$fulldata[] = $arr;
$fulldata[] = $p_arr;
$fulldata[] = $pack_arr;

echo json_encode($fulldata);

?>