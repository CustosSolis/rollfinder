<?php
$invItemDef = json_decode(file_get_contents("invitemdef.json"),true);
$colDef = json_decode(file_get_contents("coldef.json"),true);
$plugDef = json_decode(file_get_contents("plugdef.json"),true);

$msg1="";
$msg2="";
$msg3="";
$msg4="";

foreach($invItemDef as $key => $value){
	if(!file_exists('json/itemdef/' . $key . '.json')){
		if(file_put_contents('json/itemdef/' . $key . ".json",json_encode($invItemDef[$key])));
		$msg1 = "Item definition files were succesfully installed";
		} else {
			$msg1 = "Item definition files were already installed";
			}

// Item type must be weapon, else it could be a dummy item
if($value["itemType"] == 3){
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
			if(file_put_contents('names/' . $name . ".txt",$key));
			
			$msg2 = "Item name files were succesfully installed";
			} else {
				$msg2 = "Item name files were already installed";
				}
				}
				}
				}
				}
				}
				if(file_exists('names/placeholder.txt')){
					unlink('names/placeholder.txt');
				}
				
				if(file_exists('json/itemdef/placeholder.txt')){
				unlink('json/itemdef/placeholder.txt');
				}
				echo $msg1 . "<br>" . $msg2;

foreach($colDef as $key => $value){
	if(!file_exists('json/coldef/' . $key . '.json')){
		if(file_put_contents('json/coldef/' . $key . ".json",json_encode($colDef[$key])));
		$msg3 = "Collectible definition files were succesfully installed";
		} else {
			$msg3 = "Collectible definition files were already installed";
			}
}

if(file_exists('json/coldef/placeholder.txt')){
unlink('json/coldef/placeholder.txt');
}

echo "<br>" . $msg3;

foreach($plugDef as $key => $value){
	if(!file_exists('json/plugdef/' . $key . '.json')){
		if(file_put_contents('json/plugdef/' . $key . ".json",json_encode($plugDef[$key])));
		$msg4 = "Plugset definition files were succesfully installed";
		} else {
			$msg4 = "Plugset definition files were already installed";
			}
}

if(file_exists('json/plugdef/placeholder.txt')){
unlink('json/plugdef/placeholder.txt');
}
echo "<br>" . $msg4;

unlink('install.php');

?>