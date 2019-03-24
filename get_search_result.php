<?php

include_once 'config.php';

$filter_text="";
$limit = 30;
$qrcode = "";
$rp_arr = [];

if(isset($_GET["filter_text"]) && $_GET["filter_text"]!=""){
    $filter_text = $_GET["filter_text"];
}
if(isset($_GET["qrcode"]) && $_GET["qrcode"]!=""){
    $qrcode = $_GET["qrcode"];
}

$sql = "
SELECT
    rp.`id`,
    rp.`productID`,
    rp.`marketID`,
    rp.`price`,
    IFNULL(rp.`comment`, '') AS COMMENT,
    rp.`createDate`,
    rp.`createUserID`,
    rp.`image`,
    p.image AS p_image,
    IFNULL(m.marketName, '') AS marketName
FROM
    (
    SELECT
        MAX(rp.`id`) AS rp_id
    FROM
        `realproducts` rp
    LEFT JOIN states s ON
        rp.statusID = s.ID
    WHERE
        s.Code = 'active'
    GROUP BY
        marketID,
        productID
    ORDER BY
        rp_id
    ) rpid
LEFT JOIN `realproducts` rp ON
    rpid.rp_id = rp.id
LEFT JOIN products p ON
    rp.productID = p.id
LEFT JOIN markets m ON
    rp.marketID = m.id
LEFT JOIN brands b ON
    p.brandID = b.id        
LEFT JOIN (SELECT
                `prodID`,
                GROUP_CONCAT(
                    CONCAT(`paramID`, '-', `value`) SEPARATOR '|'
                ) AS allval
            FROM
                `paramvalue`
            GROUP BY
                prodID
          ) pv  ON
     p.id = pv.prodID
";

if ($filter_text != ""){
    $filter_text = str_replace(" ", "%", $filter_text);
    $sql .= "WHERE CONCAT(p.name, ' ', IFNULL(b.brandName, ''), ' ', IFNULL(pv.allval, ''), ' ', IFNULL(m.marketName, '')) LIKE '%$filter_text%' ";
}
if ($qrcode != ""){
    // e.i. qrcodi gvaqvs gadmocemuli
    $sql .= "WHERE p.qrcode = '$qrcode' ";
}

$order = "ORDER BY
marketID,
id DESC";

$sql .= $order . " limit $limit";

// die($sql);

// $sql_param = "
// SELECT pv.`id`, pv.`prodID`, pv.`paramID`, pv.`value`, p.name 
// FROM `paramvalue` pv 
// LEFT JOIN paramiters p ON pv.`paramID` = p.id 
// ORDER by `prodID`, `paramID`
// ";

// $result_P = $conn->query($sql_param);
// $arr_p = [];
// $arr_pVal = [];
// $arr_pName = [];

// if (!$result_P){
//     die("SQL Error:\n" . $sql_param);
// }else{
// 	if (mysqli_num_rows($result_P) > 0) {
//         foreach($result_P as $row){            
//             $rpID = $row["prodID"];            
//             if (!isset($arr_p[$rpID])){                
//                 $arr_p[$rpID] = [];
//                 $arr_pVal[$rpID] = [];
//                 $arr_pName[$rpID] = [];             
//             }
//             array_push($arr_p[$rpID], $row["paramID"]);
//             array_push($arr_pVal[$rpID], $row["value"]);
//             array_push($arr_pName[$rpID], $row["name"]);
//         }
//     }
// }

$result = mysqli_query($conn, $sql);

if (!$result){
    die("SQL Error:\n" . $sql);
}else{
	if (mysqli_num_rows($result) > 0) {
        foreach($result as $row){
            // $rpID = $row["id"];
            // if (isset($arr_p[$rpID])){
            //     $row["paramIDs"] = $arr_p[$rpID];
            //     $row["pVal"] = $arr_pVal[$rpID];
            //     $row["pName"] = $arr_pName[$rpID];
            // }else{
            //     $row["paramIDs"] = [];
            //     $row["pVal"] = [];
            //     $row["pName"] = [];
            // }  

        	$rp_arr[] = $row;
        }
    }
}

echo json_encode ($rp_arr);

?>