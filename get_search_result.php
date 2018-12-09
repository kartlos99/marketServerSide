<?php

include_once 'config.php';

$filter_text="";

if(isset($_GET["filter_text"]) && $_GET["filter_text"]!=""){
    $filter_text = $_GET["filter_text"];
}

$sql = "
SELECT
    rp.`id`,
    rp.`productID`,
    rp.`marketID`,
    rp.`price`,
    rp.`brandID`,
    rp.`country`,
    rp.`packingID`,
    rp.`image`,
    ifnull(rp.`comment`, '') AS comment,
    rp.`createDate`,
    rp.`createUserID`,
    p.name AS product_name,
    p.image AS p_image,
    ifnull(m.marketName, '') AS marketName,
    ifnull(b.brandName, '') AS brandName,
    ifnull(di.valueText, '') AS packing
FROM
    `realproducts` rp
LEFT JOIN products p ON
    rp.productID = p.id
LEFT JOIN markets m ON
    rp.marketID = m.id
LEFT JOIN brands b ON
    rp.brandID = b.id
LEFT JOIN dictionaryitems di ON
    rp.packingID = di.id
";

if ($filter_text != ""){
    $sql .= "WHERE p.name LIKE '$filter_text%'";
}

$sql_param = "
SELECT pv.`id`, pv.`realProdID`, pv.`paramID`, pv.`value`, p.name FROM `paramvalue` pv LEFT JOIN paramiters p ON pv.`paramID` = p.id ORDER by `realProdID`, `paramID`
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
            $rpID = $row["realProdID"];            
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

$result = mysqli_query($conn, $sql);
$rp_arr = [];

if (!$result){
    die("SQL Error:\n" . $sql);
}else{
	if (mysqli_num_rows($result) > 0) {
        foreach($result as $row){
            $rpID = $row["id"];
            if (isset($arr_p[$rpID])){
                $row["paramIDs"] = $arr_p[$rpID];
                $row["pVal"] = $arr_pVal[$rpID];
                $row["pName"] = $arr_pName[$rpID];
            }else{
                $row["paramIDs"] = [];
                $row["pVal"] = [];
                $row["pName"] = [];
            }  

        	$rp_arr[] = $row;
        }
    }
}

echo json_encode ($rp_arr);


?>