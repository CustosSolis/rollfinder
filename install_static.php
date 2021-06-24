<?php
$invItemDef = json_decode(file_get_contents("invitemdef.json"),true);
$colDef = json_decode(file_get_contents("coldef.json"),true);
$plugDef = json_decode(file_get_contents("plugdef.json"),true);
$msg="";

foreach($invItemDef as $key => $value){

// Item type must be weapon, else it could be a dummy item
if($value["itemType"] == 3){

foreach($value["sockets"]["socketEntries"] as $sockindex){
if(!isset($sockindex["randomizedPlugSetHash"])){
if(isset($value["itemCategoryHashes"])){
		$array = $value["itemCategoryHashes"];
		$i=1;

foreach($array as $x => $y){
	// Must be categorised as a weapon, else it could be a dummy item
	$cat = $y;
	if($cat == "1"){
		$oldname = strtolower($value["displayProperties"]["name"]);
		$name = preg_replace("/[^a-z0-9]/", "", $oldname);
		
		if(!file_exists('names/' . $name . '.txt')){
			file_put_contents('names/' . $name . ".txt",$key);
			
			$msg = "Static roll files were succesfully installed";
			} else {
				$msg = "Static roll files were already installed";
				}
				}
				}
				}
}
}
				}
				}
if(file_exists('names/placeholder.txt')){
	unlink('names/placeholder.txt');
}

echo $msg;

unlink('install_static.php');

?>