<?php

include_once 'config.php';



// die (print_r($_POST));
$prod_id = $_POST["prod_id"];
$comment = $_POST["comment"];
$price = $_POST["price"];
$packing_id = 0;
$need_ckeck = false;

if ($prod_id == 0){
    // aseti produqti aragvaqvs da unda davamatot bazashi 'need_check' statusit
    $comment .= " PR_NAME:" . $_POST["prod_name"];
    $prod_id = 9; // unnown produqtis ID
    $need_ckeck = true;
}else{
    $arr_pID = $_POST["paramIDs"];
    $arr_pVAL = $_POST["paramValues"];
    $packing_id = $_POST["packing_id"];    
}

$market_id = $_POST["market_id"];
if ($market_id == 0){
    $comment .= " MARKET_NAME:" . $_POST["market_name"];
    $market_id = 9;
    $need_ckeck = true;
}

$brand_id = $_POST["brand_id"];
if ($brand_id == 0){
    $comment .= " BRAND_NAME:" . $_POST["brand_name"];
    $brand_id = 9;
    $need_ckeck = true;
}

if ($need_ckeck){
    $statusID = "(SELECT s.id FROM states s LEFT JOIN objects o ON s.ObjectID = o.ID WHERE o.ObjectName = 'realproducts' AND s.Code = 'need_check')";
}else{
    $statusID = "(SELECT s.id FROM states s LEFT JOIN objects o ON s.ObjectID = o.ID WHERE o.ObjectName = 'realproducts' AND s.Code = 'active')";
}



$sql = "
INSERT INTO `realproducts`(
    `productID`,
    `marketID`,
    `price`,
    `brandID`,
    `country`,
    `packingID`,
    `comment`,
    `statusID`,
    `createUserID`
)
VALUES(
    $prod_id,
    $market_id,
    '$price',
    $brand_id,
    'ge',
    $packing_id,
    '$comment',
    $statusID,
    1
)
";
// die($sql);
$result = mysqli_query($conn, $sql);

if ($result){
    $realPrID = mysqli_insert_id($conn);
    echo $realPrID;

    if ($arr_pID != null){
        $paramSql = "INSERT INTO `paramvalue`(`realProdID`, `paramID`, `value`)
        VALUES ";
    
        $paramInsBody = "";
    
        for ($i = 0; $i < count($arr_pID); $i++) {        
            $paramInsBody .= "(" . $realPrID . ", " . $arr_pID[$i] . ", " . $arr_pVAL[$i] . ")";
            if ($i < count($arr_pID) - 1 ){
                $paramInsBody .= ", ";
            }
        }
    
        $paramSql .= $paramInsBody;
    
        if (mysqli_query($conn, $paramSql) !== true){
            echo 0;
        };
    }
    
}else{
    echo 0;
}


// echo json_encode($_POST);


?>