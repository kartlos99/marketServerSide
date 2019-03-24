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
    p.qrcode as qr,
    p.name,
    p.packingID as packID,
    p.image AS p_img,
    p.brandID AS brID
FROM
    `products` p
LEFT JOIN producttype pt ON
    p.typeID = pt.id
LEFT JOIN productgr pg ON
    pt.grID = pg.id
LEFT JOIN states s ON 
	p.statusID = s.ID
WHERE s.Code = 'active'
";

// *********** titoeul produqts tavisi parametrebis mnishvnelobebs vanichebt *******
$sql_param = "
SELECT pv.`id`, pv.`prodID`, pv.`paramID`, pv.`value`, p.name 
FROM `paramvalue` pv 
LEFT JOIN paramiters p ON pv.`paramID` = p.id 
ORDER by `prodID`, `paramID`
";

$result_P = $conn->query($sql_param);
$arr_p = [];
$arr_pVal = [];
$arr_pName = [];

if (!$result_P){
    die("SQL Error:\n" . $sql_param);
}else{
	if (mysqli_num_rows($result_P) > 0) {
        foreach($result_P as $row){            
            $rpID = $row["prodID"];            
            if (!isset($arr_p[$rpID])){                
                $arr_p[$rpID] = [];
                $arr_pVal[$rpID] = [];
                $arr_pName[$rpID] = [];             
            }
            array_push($arr_p[$rpID], $row["paramID"]);
            array_push($arr_pVal[$rpID], $row["value"]);
            array_push($arr_pName[$rpID], $row["name"]);
        }
    }
}

$result = $conn->query($sql);
 
$arr = [];


//  *****  titoeul produqs vabamt tavis  ***********
//  *****  parameris mnishvnelobebs  ******
if (!$result){
    die("SQL Error:\n" . $sql);
}else{
	if (mysqli_num_rows($result) > 0) {
    
        foreach($result as $row){
            $productID = $row["id"];
            if (isset($arr_pVal[$productID])){
                $row["pVal"] = $arr_pVal[$productID];
                $row["pID"] = $arr_p[$productID];
            }else{
                $row["pVal"] = [];
                $row["pID"] = [];
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

// ***********  mogvaqvs yvela brendi  ***************
$sql = "
SELECT b.id, brandName, brandNameEng FROM `brands` b
LEFT JOIN states s ON b.statusID = s.ID
WHERE s.Code = 'active'
ORDER by brandName";

$result = mysqli_query($conn, $sql);
$brands_arr = [];

if (!$result){
    die("SQL Error:\n" . $sql);
}else{
    if (mysqli_num_rows($result) > 0) {
        foreach($result as $row){
            $brands_arr[] = $row;
        }
    }
}

// ***********  mogvaqvs yvela magazia  ***************
$sql = "
SELECT m.`id`, `marketName`, `marketNameEng`, `sn`, `logo`, `image`, `address`, `locationX`, `locationY`, m.`comment` FROM `markets` m
LEFT JOIN states s ON m.statusID = s.ID
WHERE s.Code = 'active'
ORDER BY `marketName`";

$result = mysqli_query($conn, $sql);
$markets_arr = [];

if (!$result){
    die("SQL Error:\n" . $sql);
}else{
    if (mysqli_num_rows($result) > 0) {
        foreach($result as $row){
            $markets_arr[] = $row;
        }
    }
}

// **********  romel products ra parametrebi aqvs  *********
$sql_allparam = "
SELECT * FROM `prodvsparam` 
ORDER by `prodTypeID`";

// **********  romel products ra shefutvis tipi aqvs  *********
$sql_allPack = "
SELECT `prodTypeID`, p.packingID, valuetext FROM `prodvspack` p
LEFT JOIN dictionaryitems d on p.packingID = d.id
ORDER by prodTypeID, d.sortID";

$result_allParam = $conn->query($sql_allparam);
$result_allPack = $conn->query($sql_allPack);

$arr_allparam = [];
$arr_allpack = [];

if (!$result_allParam){
    die("SQL Error:\n" . $sql_allparam);
}else{
	if (mysqli_num_rows($result_allParam) > 0) {
        foreach($result_allParam as $row){            
            $typeID = $row["prodTypeID"];            
            if (!isset( $arr_allparam[ $typeID ] )){                
                $arr_allparam[$typeID] = [];                
            }
            array_push($arr_allparam[$typeID], $row["paramID"]);            
        }
    }
}

if (!$result_allPack){
    die("SQL Error:\n" . $sql_allPack);
}else{
    if (mysqli_num_rows($result_allPack) > 0) {
        foreach($result_allPack as $row){            
            $typeID = $row["prodTypeID"];            
            if (!isset($arr_allpack[$typeID])){                
                $arr_allpack[$typeID] = [];                
            }
            array_push($arr_allpack[$typeID], $row["packingID"]);            
        }
    }
}

$sql_allprodtypes = "SELECT
`id`,
`code`,
`name`,
`image`
FROM
`producttype`
WHERE
`isActive` = 1
ORDER BY
`sortID`, `id`";

$result_allprType = $conn->query($sql_allprodtypes);
$prType_arr = [];

if (!$result_allprType){
    die("SQL Error:\n" . $sql_allprodtypes);
} else {
    if (mysqli_num_rows($result_allprType) > 0) {
        foreach($result_allprType as $row){
            $prTypeID = $row["id"];
            if (isset($arr_allparam[ $prTypeID ])){
                $row["all_param"] = $arr_allparam[ $prTypeID ];                
            }else{
                $row["all_param"] = [];
            }
            if (isset($arr_allpack[ $prTypeID ])){
                $row["all_pack"] = $arr_allpack[ $prTypeID ];                
            }else{
                $row["all_pack"] = [];
            }
               
            $prType_arr[] = $row;
        }
    }
}

// ********* productebi, parametrebi, shefutvis tipebi
// ********* yvela ertad mogvaqvs  *******************

$fulldata[] = $arr;
$fulldata[] = $p_arr;
$fulldata[] = $pack_arr;
$fulldata[] = $brands_arr;
$fulldata[] = $markets_arr;
$fulldata[] = $prType_arr;

echo json_encode($fulldata);

?>