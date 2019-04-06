<?php

include_once 'config.php';

$arr = $_POST['data'];

if (count($arr) > 0 ){
    $values = "";
    $i=1;

    foreach($arr as $row){

        $code = $row['code'];
        $description = $row['description'];
        $name = $row['name'];
        $price = $row['price'];
        $size = $row['size'];
        $subTypeID = $row['subTypeId'];
        $subType = $row['subType'];
        $typeID = $row['typeId'];
        $type = $row['type'];
        
        $values .= "(
            '$code',
            '$description',
            '$name',
            '$price',
            '$size',
            $subTypeID,
            '$subType',
            $typeID,
            '$type'
         )";

         if ( $i < count($arr) ){
            $values .= ",";
            $i++;
         }

    }

    $sql = "
    INSERT INTO `imported`(
        `code`,
        `description`,
        `name`,
        `price`,
        `size`,
        `subtypeID`,
        `subtype`,
        `typeID`,
        `type`
    )
    VALUES" . $values;

    $result = mysqli_query($conn, $sql);
    if($result){
        echo "ok";
    }else{
        echo "ar chaiwera: " . $sql;

    }

}






?>