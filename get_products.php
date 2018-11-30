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
    p.NAME,
    pt.name AS prod,
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

$result = $conn->query($sql);

 
if (!$result){
    die("SQL Error:\n" . $sql);
}else{
	if (mysqli_num_rows($result) > 0) {
    
            foreach($result as $row){
            	$arr[] = $row;
            }
        }
}

echo json_encode($arr);

?>