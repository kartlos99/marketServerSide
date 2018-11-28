<?php

include_once 'config.php';

$filter_text="";

if(isset($_GET["filter_text"]) && $_GET["filter_text"]!=""){
    $filter_text = $_GET["filter_text"];
}

$sql="
SELECT p.id, typeID, name, dt.valueText AS prod, dg.valueText AS gr, dt.comment AS img FROM `products` p
LEFT JOIN dictionaryitems dt ON p.typeID = dt.id
LEFT JOIN dictionaryitems dg ON p.`groupNameID` = dg.id

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