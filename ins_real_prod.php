<?php

include_once 'config.php';



$arr_pID = $_POST["paramIDs"];
$arr_pVAL = $_POST["paramValues"];
// die (print_r($_POST));
$prod_id = $_POST["prod_id"];
$market_id = $_POST["market_id"];
$price = $_POST["price"];
$brand_id = $_POST["brand_id"];
$packing_id = $_POST["packing_id"];
$comment = $_POST["comment"];

$sql = "
INSERT INTO `realproducts`(
    `productID`,
    `marketID`,
    `price`,
    `brandID`,
    `country`,
    `packingID`,
    `comment`,
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
    1
)
";
// die($sql);
$result = mysqli_query($conn, $sql);

if ($result){
    $realPrID = mysqli_insert_id($conn);
    echo $realPrID;

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
        echo "param INS ERROR: ".$paramSql;
    };
    

}else{
    echo 0;
}


// echo json_encode($_POST);


?>