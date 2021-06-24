<?php
$invItemDef = json_decode(file_get_contents("invitemdef.json"),true);
$colDef = json_decode(file_get_contents("coldef.json"),true);
$plugDef = json_decode(file_get_contents("plugdef.json"),true);
$season = json_decode(file_get_contents("https://raw.githubusercontent.com/DestinyItemManager/d2-additional-info/master/data/seasons/seasons_unfiltered.json"),true);

$msg1="";
$msg2="";
$msg3="";
$msg4="";

foreach($invItemDef as $key => $value){
	if(!file_exists('json/itemdef/' . $key . '.json')){
		file_put_contents('json/itemdef/' . $key . ".json",json_encode($invItemDef[$key]));
		$msg1 = "Item definition files were succesfully installed";
		} else {
			$msg1 = "Item definition files were already installed";
			}

// Item type must be weapon, else it could be a dummy item
if($value["itemType"] == 3){

foreach($value["sockets"]["socketEntries"] as $sockindex){
if(isset($sockindex["randomizedPlugSetHash"])){
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
			
			$msg2 = "Item name files were succesfully installed";
			} else {
				$msg2 = "Item name files were already installed";
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

if(file_exists('json/itemdef/placeholder.txt')){
unlink('json/itemdef/placeholder.txt');
}
echo $msg1 . "<br>" . $msg2;

foreach($colDef as $key => $value){
	if(!file_exists('json/coldef/' . $key . '.json')){
		file_put_contents('json/coldef/' . $key . ".json",json_encode($colDef[$key]));
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
		file_put_contents('json/plugdef/' . $key . ".json",json_encode($plugDef[$key]));
		$msg4 = "Plugset definition files were succesfully installed";
		} else {
			$msg4 = "Plugset definition files were already installed";
			}
}

if(file_exists('json/plugdef/placeholder.txt')){
unlink('json/plugdef/placeholder.txt');
}
echo "<br>" . $msg4;

foreach($season as $key => $value){
	if(!file_exists('json/season/' . $key . '.json')){
		file_put_contents('json/season/' . $key . ".json",json_encode($season[$key]));
		$msg5 = "Season definition files were succesfully installed";
		} else {
			$msg5 = "Season definition files were already installed";
			}
}

unlink('install_random.php');

?>