<?php
$invItemDef = json_decode(file_get_contents("invitemdef.json"),true);
foreach($invItemDef as $key => $value){
if(!file_exists('json/itemdef/' . $key . '.json')){
		file_put_contents('json/itemdef/' . $key . ".json",json_encode($invItemDef[$key]));
}
}
?>