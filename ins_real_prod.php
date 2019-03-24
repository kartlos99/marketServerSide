<?php

include_once 'config.php';

// die (print_r($_POST));
$prod_id = $_POST["prod_id"];
$comment = $_POST["comment"];
$price = $_POST["price"];
$packing_id = 10; // unknoun shefutva
$need_ckeck = false;

$return["id"]=0;
$return["error"]="";

if ($prod_id == 0){
    // aseti produqti aragvaqvs da unda davamatot bazashi 'need_check' statusit
    $prName = $_POST["prod_name"];
    $comment .= " PR_NAME:" . $prName;
    $prod_id = 9; // unnown produqtis ID
    $arr_pID = $_POST["paramIDs"];
    $arr_pVAL = $_POST["paramValues"];
    $packing_id = $_POST["packing_id"];
    $qr_Code = $_POST["qr"];

    $brand_id = $_POST["brand_id"];
    if ($brand_id == 0){
        $comment .= " BRAND_NAME:" . $_POST["brand_name"];
        $brand_id = 9;
    }
    $params = " params:";
    for ($i = 0; $i < count($arr_pID); $i++) {        
        $params .= "(id:" . $arr_pID[$i] . ", val:" . $arr_pVAL[$i] . ")";
        if ($i < count($arr_pID) - 1 ){
            $paramInsBody .= ", ";
        }
    }
    $comment .= $params;

    $sql_ins_product = "
    INSERT INTO `products`(
        `qrcode`,
        `name`,
        `typeID`,
        `brandID`,
        `packingID`,
        `comment`,
        `statusID`,
        `createUserID`    
    )
    VALUES(
        '$qr_Code',
        '$prName',
        10,
        $brand_id,
        $packing_id,
        '$comment',
        5,
        1
    )";

    $result_ins_pr = mysqli_query($conn, $sql_ins_product);
    if ($result_ins_pr){
        $prod_id = mysqli_insert_id($conn);

        // produqtis Caweris mere vwert mis parametrebs
        $paramSql = "INSERT INTO `paramvalue`(`prodID`, `paramID`, `value`) VALUES ";
    
        $paramInsBody = "";
    
        for ($i = 0; $i < count($arr_pID); $i++) {
            if ($arr_pVAL[$i] != 0){
                if ($paramInsBody != ""){
                    $paramInsBody .= ",";    
                }
                $paramInsBody .= "(" . $prod_id . ", " . $arr_pID[$i] . ", " . $arr_pVAL[$i] . ")";
            }
        }
    
        $paramSql .= $paramInsBody;
    
        if (mysqli_query($conn, $paramSql) !== true){
            $return["error"] = "parametrebis mnishvneloba ar chaiwera!";
        };
    
    }else{
        $return['error1'] = "producti ar chaiwera:" . $sql_ins_product;
    }

    $need_ckeck = true;
}

$market_id = $_POST["market_id"];
if ($market_id == 0){
    $comment .= " MARKET_NAME:" . $_POST["market_name"];
    $market_id = 9;
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
    `comment`,
    `statusID`,
    `createUserID`
)
VALUES(
    $prod_id,
    $market_id,
    '$price',
    '$comment',
    $statusID,
    1
)
";
// die($sql);
$result = mysqli_query($conn, $sql);

if ($result){
    $realPrID = mysqli_insert_id($conn);
    $return["id"] = $realPrID;
    // echo $realPrID;

    if (isset($_POST["image"])){
        $image = $_POST["image"];
        $image_name = $realPrID;
        $upload_path = "images/$image_name.jpg";
        file_put_contents($upload_path, base64_decode($image));
    }    

}else{
    $return["error"] = "chawers problema!";
}


echo json_encode($return);


?>